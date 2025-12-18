<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DoctorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Doctor::insert([
            [
                'name' => 'Dr. Budi Santoso, Sp.OG',
                'license_no' => 'SIP.123.456.789',
                'hospital' => 'RS Unggul Karsa Medika',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Dr. Siti Aminah, Sp.A',
                'license_no' => 'SIP.987.654.321',
                'hospital' => 'RS Unggul Karsa Medika',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Dr. Andi Pratama, Sp.OG',
                'license_no' => 'SIP.456.123.789',
                'hospital' => 'RS Unggul Karsa Medika',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
