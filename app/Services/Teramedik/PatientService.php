<?php

namespace App\Services\Teramedik;

use App\Services\Teramedik\DTOs\PatientDTO;
use Illuminate\Support\Facades\Cache;

class PatientService
{
    public function __construct(
        protected TeramedikClient $client
    ) {}

    /**
     * Search patients in Teramedik.
     */
    public function search(string $query): array
    {
        $endpoint = config('teramedik.endpoints.patients.search');
        
        $response = $this->client->get($endpoint, [], ['q' => $query]);

        return array_map(
            fn($patient) => PatientDTO::fromTeramedik($patient),
            $response['data'] ?? []
        );
    }

    /**
     * Get patient by Medical Record Number.
     */
    public function getByMedicalRecordNo(string $rmNumber): ?PatientDTO
    {
        $cacheKey = config('teramedik.cache.prefix') . 'patient_rm_' . $rmNumber;

        if (config('teramedik.cache.enabled')) {
            $cached = Cache::get($cacheKey);
            if ($cached) {
                return PatientDTO::fromArray($cached);
            }
        }

        $endpoint = config('teramedik.endpoints.patients.by_rm');
        
        $response = $this->client->get($endpoint, ['medical_record_no' => $rmNumber]);

        if (empty($response['data'])) {
            return null;
        }

        $dto = PatientDTO::fromTeramedik($response['data']);

        if (config('teramedik.cache.enabled')) {
            Cache::put($cacheKey, $dto->toArray(), config('teramedik.cache.ttl'));
        }

        return $dto;
    }

    /**
     * Get patient by NIK (No. KTP).
     */
    public function getByNIK(string $nik): ?PatientDTO
    {
        $cacheKey = config('teramedik.cache.prefix') . 'patient_nik_' . $nik;

        if (config('teramedik.cache.enabled')) {
            $cached = Cache::get($cacheKey);
            if ($cached) {
                return PatientDTO::fromArray($cached);
            }
        }

        $endpoint = config('teramedik.endpoints.patients.by_nik');
        
        $response = $this->client->get($endpoint, ['nik' => $nik]);

        if (empty($response['data'])) {
            return null;
        }

        $dto = PatientDTO::fromTeramedik($response['data']);

        if (config('teramedik.cache.enabled')) {
            Cache::put($cacheKey, $dto->toArray(), config('teramedik.cache.ttl'));
        }

        return $dto;
    }

    /**
     * Get patient by Teramedik ID.
     */
    public function getById(string $id): ?PatientDTO
    {
        $endpoint = config('teramedik.endpoints.patients.detail');
        
        $response = $this->client->get($endpoint, ['id' => $id]);

        if (empty($response['data'])) {
            return null;
        }

        return PatientDTO::fromTeramedik($response['data']);
    }

    /**
     * Clear patient cache.
     */
    public function clearCache(?string $rmNumber = null, ?string $nik = null): void
    {
        if ($rmNumber) {
            Cache::forget(config('teramedik.cache.prefix') . 'patient_rm_' . $rmNumber);
        }
        if ($nik) {
            Cache::forget(config('teramedik.cache.prefix') . 'patient_nik_' . $nik);
        }
    }
}
