<?php

namespace App\Services\Teramedik;

use App\Services\Teramedik\DTOs\EncounterDTO;
use Illuminate\Support\Facades\Cache;

class EncounterService
{
    public function __construct(
        protected TeramedikClient $client
    ) {}

    /**
     * Get active encounters (pasien rawat inap aktif).
     * Useful for finding mothers currently in the maternity ward.
     */
    public function getActiveEncounters(): array
    {
        $cacheKey = config('teramedik.cache.prefix') . 'encounters_active';
        $cacheTtl = 300; // 5 minutes for active encounters

        if (config('teramedik.cache.enabled')) {
            $cached = Cache::get($cacheKey);
            if ($cached) {
                return array_map(fn($e) => EncounterDTO::fromArray($e), $cached);
            }
        }

        $endpoint = config('teramedik.endpoints.encounters.active');
        
        $response = $this->client->get($endpoint);

        $dtos = array_map(
            fn($encounter) => EncounterDTO::fromTeramedik($encounter),
            $response['data'] ?? []
        );

        if (config('teramedik.cache.enabled')) {
            Cache::put($cacheKey, array_map(fn($e) => $e->toArray(), $dtos), $cacheTtl);
        }

        return $dtos;
    }

    /**
     * Get active maternity/delivery encounters.
     * Filter by unit: Kebidanan, NIFAS, VK, etc.
     */
    public function getActiveMaternityEncounters(): array
    {
        $allActive = $this->getActiveEncounters();

        // Filter for maternity-related units
        $maternityUnits = ['kebidanan', 'nifas', 'vk', 'delivery', 'obgyn', 'obgin'];

        return array_filter($allActive, function (EncounterDTO $encounter) use ($maternityUnits) {
            $unit = strtolower($encounter->department ?? '');
            foreach ($maternityUnits as $keyword) {
                if (str_contains($unit, $keyword)) {
                    return true;
                }
            }
            return false;
        });
    }

    /**
     * Get encounters by patient ID.
     */
    public function getByPatientId(string $patientId): array
    {
        $endpoint = config('teramedik.endpoints.encounters.by_patient');
        
        $response = $this->client->get($endpoint, ['patient_id' => $patientId]);

        return array_map(
            fn($encounter) => EncounterDTO::fromTeramedik($encounter),
            $response['data'] ?? []
        );
    }

    /**
     * Get encounter detail by ID.
     */
    public function getById(string $id): ?EncounterDTO
    {
        $endpoint = config('teramedik.endpoints.encounters.detail');
        
        $response = $this->client->get($endpoint, ['id' => $id]);

        if (empty($response['data'])) {
            return null;
        }

        return EncounterDTO::fromTeramedik($response['data']);
    }

    /**
     * Clear encounters cache.
     */
    public function clearCache(): void
    {
        Cache::forget(config('teramedik.cache.prefix') . 'encounters_active');
    }
}
