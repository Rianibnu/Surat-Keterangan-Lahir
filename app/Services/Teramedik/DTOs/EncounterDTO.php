<?php

namespace App\Services\Teramedik\DTOs;

class EncounterDTO
{
    public function __construct(
        public readonly ?string $teramedikId,
        public readonly ?string $patientId,
        public readonly ?string $doctorId,
        public readonly ?string $admissionDate,
        public readonly ?string $dischargeDate,
        public readonly ?string $department,
        public readonly ?string $room,
        public readonly ?string $status,
        public readonly ?string $diagnosis,
        public readonly ?PatientDTO $patient,
        public readonly ?DoctorDTO $doctor,
    ) {}

    /**
     * Create DTO from Teramedik API response.
     */
    public static function fromTeramedik(array $data): self
    {
        $mapping = config('teramedik.field_mapping.encounter');

        $patient = null;
        if (!empty($data['pasien'])) {
            $patient = PatientDTO::fromTeramedik($data['pasien']);
        }

        $doctor = null;
        if (!empty($data['dokter'])) {
            $doctor = DoctorDTO::fromTeramedik($data['dokter']);
        }

        return new self(
            teramedikId: $data[$mapping['id']] ?? $data['id'] ?? null,
            patientId: $data[$mapping['patient_id']] ?? $data['id_pasien'] ?? null,
            doctorId: $data[$mapping['doctor_id']] ?? $data['id_dokter'] ?? null,
            admissionDate: $data[$mapping['admission_date']] ?? $data['tgl_masuk'] ?? null,
            dischargeDate: $data[$mapping['discharge_date']] ?? $data['tgl_keluar'] ?? null,
            department: $data[$mapping['department']] ?? $data['unit'] ?? null,
            room: $data[$mapping['room']] ?? $data['kamar'] ?? null,
            status: $data['status'] ?? null,
            diagnosis: $data['diagnosis'] ?? null,
            patient: $patient,
            doctor: $doctor,
        );
    }

    /**
     * Create DTO from array (cached data).
     */
    public static function fromArray(array $data): self
    {
        $patient = null;
        if (!empty($data['patient'])) {
            $patient = PatientDTO::fromArray($data['patient']);
        }

        $doctor = null;
        if (!empty($data['doctor'])) {
            $doctor = DoctorDTO::fromArray($data['doctor']);
        }

        return new self(
            teramedikId: $data['teramedik_id'] ?? null,
            patientId: $data['patient_id'] ?? null,
            doctorId: $data['doctor_id'] ?? null,
            admissionDate: $data['admission_date'] ?? null,
            dischargeDate: $data['discharge_date'] ?? null,
            department: $data['department'] ?? null,
            room: $data['room'] ?? null,
            status: $data['status'] ?? null,
            diagnosis: $data['diagnosis'] ?? null,
            patient: $patient,
            doctor: $doctor,
        );
    }

    /**
     * Convert to array.
     */
    public function toArray(): array
    {
        return [
            'teramedik_id' => $this->teramedikId,
            'patient_id' => $this->patientId,
            'doctor_id' => $this->doctorId,
            'admission_date' => $this->admissionDate,
            'discharge_date' => $this->dischargeDate,
            'department' => $this->department,
            'room' => $this->room,
            'status' => $this->status,
            'diagnosis' => $this->diagnosis,
            'patient' => $this->patient?->toArray(),
            'doctor' => $this->doctor?->toArray(),
        ];
    }

    /**
     * Check if this encounter is still active (patient not discharged).
     */
    public function isActive(): bool
    {
        return $this->status === 'active' || empty($this->dischargeDate);
    }

    /**
     * Check if this is a maternity/delivery encounter.
     */
    public function isMaternity(): bool
    {
        $maternityKeywords = ['kebidanan', 'nifas', 'vk', 'delivery', 'obgyn', 'obgin'];
        $department = strtolower($this->department ?? '');

        foreach ($maternityKeywords as $keyword) {
            if (str_contains($department, $keyword)) {
                return true;
            }
        }

        return false;
    }
}
