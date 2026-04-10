<?php

namespace App\Services\Teramedik;

use App\Services\Teramedik\DTOs\DoctorDTO;
use Illuminate\Support\Facades\Cache;

class DoctorService
{
    public function __construct(
        protected TeramedikClient $client
    ) {}

    /**
     * Get all doctors from Teramedik.
     */
    public function getAll(): array
    {
        $cacheKey = config('teramedik.cache.prefix') . 'doctors_all';

        if (config('teramedik.cache.enabled')) {
            $cached = Cache::get($cacheKey);
            if ($cached) {
                return array_map(fn($d) => DoctorDTO::fromArray($d), $cached);
            }
        }

        $endpoint = config('teramedik.endpoints.doctors.list');
        
        $response = $this->client->get($endpoint);

        $dtos = array_map(
            fn($doctor) => DoctorDTO::fromTeramedik($doctor),
            $response['data'] ?? []
        );

        if (config('teramedik.cache.enabled')) {
            Cache::put($cacheKey, array_map(fn($d) => $d->toArray(), $dtos), config('teramedik.cache.ttl'));
        }

        return $dtos;
    }

    /**
     * Get doctor by SIP (license number).
     */
    public function getBySIP(string $sipNumber): ?DoctorDTO
    {
        $cacheKey = config('teramedik.cache.prefix') . 'doctor_sip_' . $sipNumber;

        if (config('teramedik.cache.enabled')) {
            $cached = Cache::get($cacheKey);
            if ($cached) {
                return DoctorDTO::fromArray($cached);
            }
        }

        $endpoint = config('teramedik.endpoints.doctors.by_sip');
        
        $response = $this->client->get($endpoint, ['license_no' => $sipNumber]);

        if (empty($response['data'])) {
            return null;
        }

        $dto = DoctorDTO::fromTeramedik($response['data']);

        if (config('teramedik.cache.enabled')) {
            Cache::put($cacheKey, $dto->toArray(), config('teramedik.cache.ttl'));
        }

        return $dto;
    }

    /**
     * Get doctor by Teramedik ID.
     */
    public function getById(string $id): ?DoctorDTO
    {
        $endpoint = config('teramedik.endpoints.doctors.detail');
        
        $response = $this->client->get($endpoint, ['id' => $id]);

        if (empty($response['data'])) {
            return null;
        }

        return DoctorDTO::fromTeramedik($response['data']);
    }

    /**
     * Sync doctors from Teramedik to local database.
     */
    public function syncToLocal(): array
    {
        $teramedikDoctors = $this->getAll();
        $synced = [];
        $created = 0;
        $updated = 0;

        foreach ($teramedikDoctors as $dto) {
            $doctor = \App\Models\Doctor::updateOrCreate(
                ['license_no' => $dto->licenseNo],
                [
                    'name' => $dto->name,
                    'hospital' => config('app.hospital_name', 'RS Maranatha'),
                ]
            );

            // Track if created or updated
            if ($doctor->wasRecentlyCreated) {
                $created++;
            } else {
                $updated++;
            }

            $synced[] = $doctor;
        }

        // Clear cache after sync
        Cache::forget(config('teramedik.cache.prefix') . 'doctors_all');

        return [
            'doctors' => $synced,
            'created' => $created,
            'updated' => $updated,
            'total' => count($synced),
        ];
    }

    /**
     * Clear doctor cache.
     */
    public function clearCache(?string $sipNumber = null): void
    {
        if ($sipNumber) {
            Cache::forget(config('teramedik.cache.prefix') . 'doctor_sip_' . $sipNumber);
        }
        Cache::forget(config('teramedik.cache.prefix') . 'doctors_all');
    }
}
