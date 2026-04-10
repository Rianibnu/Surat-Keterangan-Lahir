<?php

namespace App\Services\Teramedik\DTOs;

class PatientDTO
{
    public function __construct(
        public readonly ?string $teramedikId,
        public readonly ?string $medicalRecordNo,
        public readonly string $name,
        public readonly ?string $nik,
        public readonly ?string $birthDate,
        public readonly ?string $gender,
        public readonly ?string $address,
        public readonly ?string $phone,
        public readonly ?string $bloodType,
        public readonly ?string $occupation,
    ) {}

    /**
     * Create DTO from Teramedik API response.
     */
    public static function fromTeramedik(array $data): self
    {
        $mapping = config('teramedik.field_mapping.patient');

        return new self(
            teramedikId: $data['id'] ?? null,
            medicalRecordNo: $data[$mapping['medical_record_no']] ?? $data['no_rm'] ?? null,
            name: $data[$mapping['name']] ?? $data['nama_pasien'] ?? 'Unknown',
            nik: $data[$mapping['nik']] ?? $data['no_ktp'] ?? null,
            birthDate: $data[$mapping['birth_date']] ?? $data['tgl_lahir'] ?? null,
            gender: $data[$mapping['gender']] ?? $data['jenis_kelamin'] ?? null,
            address: $data[$mapping['address']] ?? $data['alamat'] ?? null,
            phone: $data[$mapping['phone']] ?? $data['no_telp'] ?? null,
            bloodType: $data[$mapping['blood_type']] ?? $data['gol_darah'] ?? null,
            occupation: $data[$mapping['occupation']] ?? $data['pekerjaan'] ?? null,
        );
    }

    /**
     * Create DTO from array (cached data).
     */
    public static function fromArray(array $data): self
    {
        return new self(
            teramedikId: $data['teramedik_id'] ?? null,
            medicalRecordNo: $data['medical_record_no'] ?? null,
            name: $data['name'] ?? 'Unknown',
            nik: $data['nik'] ?? null,
            birthDate: $data['birth_date'] ?? null,
            gender: $data['gender'] ?? null,
            address: $data['address'] ?? null,
            phone: $data['phone'] ?? null,
            bloodType: $data['blood_type'] ?? null,
            occupation: $data['occupation'] ?? null,
        );
    }

    /**
     * Convert to array.
     */
    public function toArray(): array
    {
        return [
            'teramedik_id' => $this->teramedikId,
            'medical_record_no' => $this->medicalRecordNo,
            'name' => $this->name,
            'nik' => $this->nik,
            'birth_date' => $this->birthDate,
            'gender' => $this->gender,
            'address' => $this->address,
            'phone' => $this->phone,
            'blood_type' => $this->bloodType,
            'occupation' => $this->occupation,
        ];
    }

    /**
     * Convert to format suitable for mother data in BirthRecord form.
     */
    public function toMotherFormData(): array
    {
        return [
            'mother_name' => $this->name,
            'mother_medical_record_no' => $this->medicalRecordNo,
            'mother_ktp' => $this->nik,
            'mother_address' => $this->address,
            'mother_occupation' => $this->occupation,
            'mother_blood_type' => $this->bloodType,
        ];
    }

    /**
     * Convert to format suitable for father data in BirthRecord form.
     */
    public function toFatherFormData(): array
    {
        return [
            'father_name' => $this->name,
            'father_ktp' => $this->nik,
            'father_address' => $this->address,
            'father_occupation' => $this->occupation,
            'father_blood_type' => $this->bloodType,
        ];
    }

    /**
     * Get gender label in Indonesian.
     */
    public function getGenderLabel(): string
    {
        return match ($this->gender) {
            'L' => 'Laki-laki',
            'P' => 'Perempuan',
            default => $this->gender ?? '-',
        };
    }
}
