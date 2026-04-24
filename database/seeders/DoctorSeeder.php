<?php

namespace Database\Seeders;

use App\Models\Doctor;
use Illuminate\Database\Seeder;

class DoctorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $doctors = [
            [
                'name' => 'dr. Ahmad Rasyid, Sp.OG',
                'license_no' => 'SIP-OG/2023/12345',
                'hospital' => 'Rumah Sakit X',
                'signature_path' => null,
            ],
            [
                'name' => 'dr. Budi Santoso, Sp.OG',
                'license_no' => 'SIP-OG/2023/12346',
                'hospital' => 'Rumah Sakit X',
                'signature_path' => null,
            ],
            [
                'name' => 'dr. Citra Dewi, Sp.OG',
                'license_no' => 'SIP-OG/2024/23456',
                'hospital' => 'Rumah Sakit X',
                'signature_path' => null,
            ],
            [
                'name' => 'dr. Diana Putri, Sp.OG',
                'license_no' => 'SIP-OG/2024/23457',
                'hospital' => 'Rumah Sakit X',
                'signature_path' => null,
            ],
            [
                'name' => 'dr. Eko Prasetyo, Sp.OG(K)',
                'license_no' => 'SIP-OG/2022/34567',
                'hospital' => 'Rumah Sakit X',
                'signature_path' => null,
            ],
        ];

        foreach ($doctors as $doctor) {
            Doctor::firstOrCreate(
                ['license_no' => $doctor['license_no']],
                $doctor
            );
        }

        $this->command->info('Doctors seeded successfully! Total: ' . count($doctors) . ' doctors');
    }
}
