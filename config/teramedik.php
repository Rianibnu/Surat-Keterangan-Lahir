<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Teramedik SIMRS API Configuration
    |--------------------------------------------------------------------------
    |
    | Konfigurasi untuk integrasi dengan Teramedik SIMRS dari PT Terakorp.
    | Pastikan untuk mendapatkan credentials dari Tim IT RS atau langsung
    | dari vendor Teramedik.
    |
    */

    // Master switch - set true setelah dapat API credentials dari Teramedik
    'enabled' => env('TERAMEDIK_ENABLED', false),

    // API Base URL (development/production)
    'base_url' => env('TERAMEDIK_API_URL', 'https://api.teramedik.local'),

    // OAuth2 Credentials
    'client_id' => env('TERAMEDIK_CLIENT_ID', ''),
    'client_secret' => env('TERAMEDIK_CLIENT_SECRET', ''),

    // Kode Faskes/RS di Teramedik
    'facility_code' => env('TERAMEDIK_FACILITY_CODE', ''),

    // HTTP Settings
    'timeout' => env('TERAMEDIK_TIMEOUT', 30),
    'retry_attempts' => env('TERAMEDIK_RETRY_ATTEMPTS', 3),
    'retry_delay' => env('TERAMEDIK_RETRY_DELAY', 1000), // milliseconds

    // Cache Settings
    'cache' => [
        'enabled' => env('TERAMEDIK_CACHE_ENABLED', true),
        'ttl' => env('TERAMEDIK_CACHE_TTL', 3600), // 1 hour
        'prefix' => 'teramedik_',
    ],

    // Mode: 'live' atau 'mock' (untuk development tanpa koneksi Teramedik)
    'mode' => env('TERAMEDIK_MODE', 'mock'),

    // Logging
    'logging' => [
        'enabled' => env('TERAMEDIK_LOG_ENABLED', true),
        'channel' => env('TERAMEDIK_LOG_CHANNEL', 'teramedik'),
    ],

    /*
    |--------------------------------------------------------------------------
    | API Endpoints
    |--------------------------------------------------------------------------
    |
    | Endpoint paths untuk berbagai service di Teramedik.
    | Path ini akan digabungkan dengan base_url.
    | CATATAN: Sesuaikan dengan dokumentasi resmi Teramedik.
    |
    */
    'endpoints' => [
        // Authentication
        'auth' => [
            'token' => '/oauth/token',
            'refresh' => '/oauth/refresh',
        ],

        // Master Data
        'patients' => [
            'search' => '/api/v1/patients/search',
            'detail' => '/api/v1/patients/{id}',
            'by_rm' => '/api/v1/patients/by-rm/{medical_record_no}',
            'by_nik' => '/api/v1/patients/by-nik/{nik}',
        ],

        'doctors' => [
            'list' => '/api/v1/doctors',
            'detail' => '/api/v1/doctors/{id}',
            'by_sip' => '/api/v1/doctors/by-sip/{license_no}',
        ],

        // Transactional Data
        'encounters' => [
            'list' => '/api/v1/encounters',
            'detail' => '/api/v1/encounters/{id}',
            'by_patient' => '/api/v1/encounters/by-patient/{patient_id}',
            'active' => '/api/v1/encounters/active',
        ],

        // Birth/Delivery Records
        'birth' => [
            'create' => '/api/v1/birth-records',
            'update' => '/api/v1/birth-records/{id}',
            'detail' => '/api/v1/birth-records/{id}',
        ],

        // Webhooks (jika Teramedik support)
        'webhooks' => [
            'register' => '/api/v1/webhooks/register',
            'unregister' => '/api/v1/webhooks/unregister',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Data Mapping
    |--------------------------------------------------------------------------
    |
    | Mapping field antara sistem SKL dan Teramedik.
    | Sesuaikan dengan struktur data Teramedik sebenarnya.
    |
    */
    'field_mapping' => [
        'patient' => [
            'medical_record_no' => 'no_rm',
            'name' => 'nama_pasien',
            'nik' => 'no_ktp',
            'birth_date' => 'tgl_lahir',
            'gender' => 'jenis_kelamin',
            'address' => 'alamat',
            'phone' => 'no_telp',
            'blood_type' => 'gol_darah',
            'occupation' => 'pekerjaan',
        ],

        'doctor' => [
            'id' => 'id_dokter',
            'name' => 'nama_dokter',
            'license_no' => 'no_sip',
            'specialization' => 'spesialisasi',
        ],

        'encounter' => [
            'id' => 'id_kunjungan',
            'patient_id' => 'id_pasien',
            'doctor_id' => 'id_dokter',
            'admission_date' => 'tgl_masuk',
            'discharge_date' => 'tgl_keluar',
            'department' => 'unit',
            'room' => 'kamar',
        ],
    ],
];
