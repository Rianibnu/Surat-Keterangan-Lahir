<?php

namespace App\Services\Teramedik;

/**
 * Mock Teramedik Service
 * 
 * Menyediakan data dummy untuk development dan testing
 * tanpa koneksi ke Teramedik API sebenarnya.
 */
class MockTeramedikService
{
    /**
     * Sample mock patients data.
     */
    protected array $mockPatients = [
        [
            'id' => 'TRM-P-001',
            'no_rm' => 'RM-2024-001234',
            'nama_pasien' => 'Siti Rahayu',
            'no_ktp' => '3201234567890001',
            'tgl_lahir' => '1990-05-15',
            'jenis_kelamin' => 'P',
            'alamat' => 'Jl. Merdeka No. 123, Bandung',
            'no_telp' => '08123456789',
            'gol_darah' => 'O',
            'pekerjaan' => 'Ibu Rumah Tangga',
        ],
        [
            'id' => 'TRM-P-002',
            'no_rm' => 'RM-2024-001235',
            'nama_pasien' => 'Dewi Lestari',
            'no_ktp' => '3201234567890002',
            'tgl_lahir' => '1992-08-20',
            'jenis_kelamin' => 'P',
            'alamat' => 'Jl. Sudirman No. 456, Bandung',
            'no_telp' => '08198765432',
            'gol_darah' => 'A',
            'pekerjaan' => 'Guru',
        ],
        [
            'id' => 'TRM-P-003',
            'no_rm' => 'RM-2024-001236',
            'nama_pasien' => 'Ahmad Hidayat',
            'no_ktp' => '3201234567890003',
            'tgl_lahir' => '1988-03-10',
            'jenis_kelamin' => 'L',
            'alamat' => 'Jl. Merdeka No. 123, Bandung',
            'no_telp' => '08111222333',
            'gol_darah' => 'B',
            'pekerjaan' => 'Wiraswasta',
        ],
    ];

    /**
     * Sample mock doctors data.
     */
    protected array $mockDoctors = [
        [
            'id' => 'TRM-D-001',
            'nama_dokter' => 'dr. Andi Pratama, Sp.OG',
            'no_sip' => 'SIP-449/SPOG/2020',
            'spesialisasi' => 'Obstetri dan Ginekologi',
        ],
        [
            'id' => 'TRM-D-002',
            'nama_dokter' => 'dr. Budi Santoso, Sp.OG',
            'no_sip' => 'SIP-550/SPOG/2019',
            'spesialisasi' => 'Obstetri dan Ginekologi',
        ],
        [
            'id' => 'TRM-D-003',
            'nama_dokter' => 'dr. Citra Dewi, Sp.A',
            'no_sip' => 'SIP-661/SPA/2021',
            'spesialisasi' => 'Pediatri',
        ],
    ];

    /**
     * Sample mock encounters (kunjungan/rawat inap).
     */
    protected array $mockEncounters = [
        [
            'id' => 'TRM-E-001',
            'id_pasien' => 'TRM-P-001',
            'id_dokter' => 'TRM-D-001',
            'tgl_masuk' => '2024-12-18',
            'tgl_keluar' => null,
            'unit' => 'Rawat Inap - Kebidanan',
            'kamar' => 'VIP-01',
            'status' => 'active',
            'diagnosis' => 'Partus Normal',
        ],
        [
            'id' => 'TRM-E-002',
            'id_pasien' => 'TRM-P-002',
            'id_dokter' => 'TRM-D-002',
            'tgl_masuk' => '2024-12-17',
            'tgl_keluar' => '2024-12-19',
            'unit' => 'Rawat Inap - Kebidanan',
            'kamar' => 'Kelas-1-05',
            'status' => 'discharged',
            'diagnosis' => 'Sectio Caesarea',
        ],
    ];

    /**
     * Get mock response based on endpoint.
     */
    public function getMockResponse(string $method, string $endpoint, array $urlParams, array $data): array
    {
        // Determine which mock data to return based on endpoint pattern
        if (str_contains($endpoint, 'patients/search')) {
            return $this->searchPatients($data);
        }

        if (str_contains($endpoint, 'patients/by-rm')) {
            return $this->getPatientByRM($urlParams['medical_record_no'] ?? '');
        }

        if (str_contains($endpoint, 'patients/by-nik')) {
            return $this->getPatientByNIK($urlParams['nik'] ?? '');
        }

        if (str_contains($endpoint, 'patients')) {
            if (isset($urlParams['id'])) {
                return $this->getPatientById($urlParams['id']);
            }
            return ['data' => $this->mockPatients];
        }

        if (str_contains($endpoint, 'doctors/by-sip')) {
            return $this->getDoctorBySIP($urlParams['license_no'] ?? '');
        }

        if (str_contains($endpoint, 'doctors')) {
            if (isset($urlParams['id'])) {
                return $this->getDoctorById($urlParams['id']);
            }
            return ['data' => $this->mockDoctors];
        }

        if (str_contains($endpoint, 'encounters/active')) {
            return $this->getActiveEncounters();
        }

        if (str_contains($endpoint, 'encounters/by-patient')) {
            return $this->getEncountersByPatient($urlParams['patient_id'] ?? '');
        }

        if (str_contains($endpoint, 'encounters')) {
            if (isset($urlParams['id'])) {
                return $this->getEncounterById($urlParams['id']);
            }
            return ['data' => $this->mockEncounters];
        }

        if (str_contains($endpoint, 'birth-records') && $method === 'POST') {
            return $this->createBirthRecord($data);
        }

        // Default: return empty success response
        return [
            'success' => true,
            'message' => 'Mock response for: ' . $method . ' ' . $endpoint,
            'data' => [],
        ];
    }

    /**
     * Search patients by name or RM number.
     */
    protected function searchPatients(array $query): array
    {
        $search = strtolower($query['q'] ?? $query['search'] ?? '');

        if (empty($search)) {
            return ['data' => $this->mockPatients];
        }

        $results = array_filter($this->mockPatients, function ($patient) use ($search) {
            return str_contains(strtolower($patient['nama_pasien']), $search)
                || str_contains(strtolower($patient['no_rm']), $search)
                || str_contains($patient['no_ktp'], $search);
        });

        return ['data' => array_values($results)];
    }

    /**
     * Get patient by medical record number.
     */
    protected function getPatientByRM(string $rmNumber): array
    {
        foreach ($this->mockPatients as $patient) {
            if ($patient['no_rm'] === $rmNumber) {
                return ['data' => $patient];
            }
        }

        return [
            'data' => null,
            'message' => 'Patient not found with RM: ' . $rmNumber,
        ];
    }

    /**
     * Get patient by NIK.
     */
    protected function getPatientByNIK(string $nik): array
    {
        foreach ($this->mockPatients as $patient) {
            if ($patient['no_ktp'] === $nik) {
                return ['data' => $patient];
            }
        }

        return [
            'data' => null,
            'message' => 'Patient not found with NIK: ' . $nik,
        ];
    }

    /**
     * Get patient by ID.
     */
    protected function getPatientById(string $id): array
    {
        foreach ($this->mockPatients as $patient) {
            if ($patient['id'] === $id) {
                return ['data' => $patient];
            }
        }

        return [
            'data' => null,
            'message' => 'Patient not found with ID: ' . $id,
        ];
    }

    /**
     * Get doctor by SIP number.
     */
    protected function getDoctorBySIP(string $sipNumber): array
    {
        foreach ($this->mockDoctors as $doctor) {
            if ($doctor['no_sip'] === $sipNumber) {
                return ['data' => $doctor];
            }
        }

        return [
            'data' => null,
            'message' => 'Doctor not found with SIP: ' . $sipNumber,
        ];
    }

    /**
     * Get doctor by ID.
     */
    protected function getDoctorById(string $id): array
    {
        foreach ($this->mockDoctors as $doctor) {
            if ($doctor['id'] === $id) {
                return ['data' => $doctor];
            }
        }

        return [
            'data' => null,
            'message' => 'Doctor not found with ID: ' . $id,
        ];
    }

    /**
     * Get active encounters (pasien rawat inap aktif).
     */
    protected function getActiveEncounters(): array
    {
        $active = array_filter($this->mockEncounters, function ($enc) {
            return $enc['status'] === 'active';
        });

        // Attach patient and doctor data
        foreach ($active as &$encounter) {
            $encounter['pasien'] = $this->getPatientById($encounter['id_pasien'])['data'] ?? null;
            $encounter['dokter'] = $this->getDoctorById($encounter['id_dokter'])['data'] ?? null;
        }

        return ['data' => array_values($active)];
    }

    /**
     * Get encounters by patient ID.
     */
    protected function getEncountersByPatient(string $patientId): array
    {
        $results = array_filter($this->mockEncounters, function ($enc) use ($patientId) {
            return $enc['id_pasien'] === $patientId;
        });

        return ['data' => array_values($results)];
    }

    /**
     * Get encounter by ID.
     */
    protected function getEncounterById(string $id): array
    {
        foreach ($this->mockEncounters as $encounter) {
            if ($encounter['id'] === $id) {
                $encounter['pasien'] = $this->getPatientById($encounter['id_pasien'])['data'] ?? null;
                $encounter['dokter'] = $this->getDoctorById($encounter['id_dokter'])['data'] ?? null;
                return ['data' => $encounter];
            }
        }

        return [
            'data' => null,
            'message' => 'Encounter not found with ID: ' . $id,
        ];
    }

    /**
     * Mock creating a birth record in Teramedik.
     */
    protected function createBirthRecord(array $data): array
    {
        // Simulate successful creation
        return [
            'success' => true,
            'message' => 'Birth record created successfully in Teramedik (MOCK)',
            'data' => [
                'id' => 'TRM-BR-' . uniqid(),
                'no_rm_bayi' => 'RM-' . date('Y') . '-' . rand(100000, 999999),
                'created_at' => now()->toISOString(),
                ...$data,
            ],
        ];
    }
}
