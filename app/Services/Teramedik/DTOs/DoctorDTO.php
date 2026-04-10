<?php

namespace App\Services\Teramedik\DTOs;

class DoctorDTO
{
    public function __construct(
        public readonly ?string $teramedikId,
        public readonly string $name,
        public readonly ?string $licenseNo,
        public readonly ?string $specialization,
    ) {}

    /**
     * Create DTO from Teramedik API response.
     */
    public static function fromTeramedik(array $data): self
    {
        $mapping = config('teramedik.field_mapping.doctor');

        return new self(
            teramedikId: $data[$mapping['id']] ?? $data['id'] ?? null,
            name: $data[$mapping['name']] ?? $data['nama_dokter'] ?? 'Unknown',
            licenseNo: $data[$mapping['license_no']] ?? $data['no_sip'] ?? null,
            specialization: $data[$mapping['specialization']] ?? $data['spesialisasi'] ?? null,
        );
    }

    /**
     * Create DTO from array (cached data).
     */
    public static function fromArray(array $data): self
    {
        return new self(
            teramedikId: $data['teramedik_id'] ?? null,
            name: $data['name'] ?? 'Unknown',
            licenseNo: $data['license_no'] ?? null,
            specialization: $data['specialization'] ?? null,
        );
    }

    /**
     * Convert to array.
     */
    public function toArray(): array
    {
        return [
            'teramedik_id' => $this->teramedikId,
            'name' => $this->name,
            'license_no' => $this->licenseNo,
            'specialization' => $this->specialization,
        ];
    }

    /**
     * Convert to local Doctor model format.
     */
    public function toLocalModelData(): array
    {
        return [
            'name' => $this->name,
            'license_no' => $this->licenseNo,
        ];
    }
}
