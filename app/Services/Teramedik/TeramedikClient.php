<?php

namespace App\Services\Teramedik;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use App\Services\Teramedik\Exceptions\TeramedikApiException;
use App\Services\Teramedik\Exceptions\TeramedikAuthException;

class TeramedikClient
{
    protected ?string $accessToken = null;
    protected ?int $tokenExpiresAt = null;

    /**
     * Create a new TeramedikClient instance.
     */
    public function __construct()
    {
        // Token will be fetched on first API call
    }

    /**
     * Check if we're in mock mode.
     */
    public function isMockMode(): bool
    {
        return config('teramedik.mode') === 'mock';
    }

    /**
     * Get the base URL for API calls.
     */
    protected function getBaseUrl(): string
    {
        return rtrim(config('teramedik.base_url'), '/');
    }

    /**
     * Build full URL for an endpoint.
     */
    protected function buildUrl(string $path, array $params = []): string
    {
        $url = $this->getBaseUrl() . '/' . ltrim($path, '/');

        // Replace URL parameters
        foreach ($params as $key => $value) {
            $url = str_replace("{{$key}}", $value, $url);
        }

        return $url;
    }

    /**
     * Get or refresh the access token.
     */
    public function getAccessToken(): string
    {
        // Check if we have a valid cached token
        $cachedToken = Cache::get('teramedik_access_token');
        if ($cachedToken) {
            return $cachedToken;
        }

        // Request new token
        return $this->refreshAccessToken();
    }

    /**
     * Request a new access token from Teramedik.
     */
    protected function refreshAccessToken(): string
    {
        $tokenEndpoint = config('teramedik.endpoints.auth.token');
        $url = $this->buildUrl($tokenEndpoint);

        $this->logRequest('POST', $url, ['grant_type' => 'client_credentials']);

        $response = Http::timeout(config('teramedik.timeout'))
            ->asForm()
            ->post($url, [
                'grant_type' => 'client_credentials',
                'client_id' => config('teramedik.client_id'),
                'client_secret' => config('teramedik.client_secret'),
            ]);

        $this->logResponse($response);

        if (!$response->successful()) {
            throw new TeramedikAuthException(
                'Failed to authenticate with Teramedik API: ' . $response->body(),
                $response->status()
            );
        }

        $data = $response->json();
        $token = $data['access_token'] ?? null;
        $expiresIn = $data['expires_in'] ?? 3600;

        if (!$token) {
            throw new TeramedikAuthException('No access token returned from Teramedik API');
        }

        // Cache the token (with 5 minute buffer before actual expiry)
        Cache::put('teramedik_access_token', $token, $expiresIn - 300);

        return $token;
    }

    /**
     * Create an authenticated HTTP client.
     */
    protected function httpClient(): PendingRequest
    {
        $token = $this->getAccessToken();

        return Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'X-Facility-Code' => config('teramedik.facility_code'),
        ])
        ->timeout(config('teramedik.timeout'))
        ->retry(
            config('teramedik.retry_attempts'),
            config('teramedik.retry_delay'),
            function ($exception, $request) {
                // Retry on connection errors or 5xx responses
                return $exception instanceof \Illuminate\Http\Client\ConnectionException
                    || ($exception instanceof \Illuminate\Http\Client\RequestException 
                        && $exception->response->status() >= 500);
            }
        );
    }

    /**
     * Make a GET request to Teramedik API.
     */
    public function get(string $endpoint, array $urlParams = [], array $query = []): array
    {
        if ($this->isMockMode()) {
            return $this->getMockResponse('GET', $endpoint, $urlParams, $query);
        }

        $url = $this->buildUrl($endpoint, $urlParams);
        
        $this->logRequest('GET', $url, $query);

        $response = $this->httpClient()->get($url, $query);

        $this->logResponse($response);
        $this->recordApiCall('GET', $url, $query, $response);

        return $this->handleResponse($response);
    }

    /**
     * Make a POST request to Teramedik API.
     */
    public function post(string $endpoint, array $urlParams = [], array $data = []): array
    {
        if ($this->isMockMode()) {
            return $this->getMockResponse('POST', $endpoint, $urlParams, $data);
        }

        $url = $this->buildUrl($endpoint, $urlParams);

        $this->logRequest('POST', $url, $data);

        $response = $this->httpClient()->post($url, $data);

        $this->logResponse($response);
        $this->recordApiCall('POST', $url, $data, $response);

        return $this->handleResponse($response);
    }

    /**
     * Make a PUT request to Teramedik API.
     */
    public function put(string $endpoint, array $urlParams = [], array $data = []): array
    {
        if ($this->isMockMode()) {
            return $this->getMockResponse('PUT', $endpoint, $urlParams, $data);
        }

        $url = $this->buildUrl($endpoint, $urlParams);

        $this->logRequest('PUT', $url, $data);

        $response = $this->httpClient()->put($url, $data);

        $this->logResponse($response);
        $this->recordApiCall('PUT', $url, $data, $response);

        return $this->handleResponse($response);
    }

    /**
     * Handle API response.
     */
    protected function handleResponse(Response $response): array
    {
        if ($response->unauthorized()) {
            // Clear cached token and retry once
            Cache::forget('teramedik_access_token');
            throw new TeramedikAuthException('Unauthorized - Token may have expired');
        }

        if (!$response->successful()) {
            throw new TeramedikApiException(
                'Teramedik API error: ' . $response->body(),
                $response->status()
            );
        }

        return $response->json() ?? [];
    }

    /**
     * Get mock response for development/testing.
     */
    protected function getMockResponse(string $method, string $endpoint, array $urlParams, array $data): array
    {
        // Delegate to MockTeramedikService
        return app(MockTeramedikService::class)->getMockResponse($method, $endpoint, $urlParams, $data);
    }

    /**
     * Log API request.
     */
    protected function logRequest(string $method, string $url, array $data = []): void
    {
        if (!config('teramedik.logging.enabled')) {
            return;
        }

        Log::channel(config('teramedik.logging.channel', 'stack'))->info('Teramedik API Request', [
            'method' => $method,
            'url' => $url,
            'data' => $this->sanitizeLogData($data),
        ]);
    }

    /**
     * Log API response.
     */
    protected function logResponse(Response $response): void
    {
        if (!config('teramedik.logging.enabled')) {
            return;
        }

        Log::channel(config('teramedik.logging.channel', 'stack'))->info('Teramedik API Response', [
            'status' => $response->status(),
            'body' => substr($response->body(), 0, 1000), // Limit log size
        ]);
    }

    /**
     * Sanitize data for logging (remove sensitive info).
     */
    protected function sanitizeLogData(array $data): array
    {
        $sensitiveKeys = ['client_secret', 'password', 'token', 'access_token'];

        foreach ($sensitiveKeys as $key) {
            if (isset($data[$key])) {
                $data[$key] = '***REDACTED***';
            }
        }

        return $data;
    }

    /**
     * Record API call to database for auditing.
     */
    protected function recordApiCall(string $method, string $url, array $request, Response $response): void
    {
        try {
            \DB::table('teramedik_api_logs')->insert([
                'method' => $method,
                'endpoint' => $url,
                'request_payload' => json_encode($this->sanitizeLogData($request)),
                'response_payload' => substr($response->body(), 0, 5000),
                'response_code' => $response->status(),
                'duration_ms' => $response->transferStats?->getTransferTime() * 1000 ?? 0,
                'created_at' => now(),
            ]);
        } catch (\Exception $e) {
            Log::warning('Failed to record Teramedik API call: ' . $e->getMessage());
        }
    }

    /**
     * Check connection to Teramedik API.
     */
    public function healthCheck(): array
    {
        if ($this->isMockMode()) {
            return [
                'status' => 'ok',
                'mode' => 'mock',
                'message' => 'Running in mock mode - no actual connection to Teramedik',
            ];
        }

        try {
            $token = $this->getAccessToken();
            return [
                'status' => 'ok',
                'mode' => 'live',
                'message' => 'Successfully connected to Teramedik API',
                'has_token' => !empty($token),
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'mode' => 'live',
                'message' => $e->getMessage(),
            ];
        }
    }
}
