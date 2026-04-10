# Dokumentasi Integrasi Teramedik SIMRS

## 📋 Daftar Isi

1. [Overview](#overview)
2. [Struktur File](#struktur-file)
3. [Konfigurasi](#konfigurasi)
4. [Penggunaan API](#penggunaan-api)
5. [Mock Mode](#mock-mode)
6. [Langkah Implementasi Live](#langkah-implementasi-live)

---

## Overview

Integrasi ini menyediakan koneksi antara aplikasi Surat Keterangan Lahir (SKL) dengan **Teramedik SIMRS** untuk:

1. **Fetch Data Pasien** - Mengambil data ibu/ayah dari Teramedik berdasarkan No. RM atau NIK
2. **Sync Data Dokter** - Sinkronisasi data dokter dari Teramedik ke database lokal
3. **Monitor Rawat Inap** - Melihat pasien aktif di unit kebidanan/NIFAS
4. **Push Data Kelahiran** - Mengirim data bayi baru lahir ke Teramedik

---

## Struktur File

```
app/
├── Http/Controllers/
│   └── TeramedikController.php          # API endpoints
│
├── Providers/
│   └── TeramedikServiceProvider.php     # Service registration
│
└── Services/Teramedik/
    ├── TeramedikClient.php              # HTTP Client + Auth
    ├── MockTeramedikService.php         # Mock data untuk development
    ├── PatientService.php               # Layanan data pasien
    ├── DoctorService.php                # Layanan data dokter
    ├── EncounterService.php             # Layanan rawat inap
    ├── BirthRecordSyncService.php       # Push data kelahiran
    │
    ├── DTOs/
    │   ├── PatientDTO.php               # Data Transfer Object pasien
    │   ├── DoctorDTO.php                # DTO dokter
    │   └── EncounterDTO.php             # DTO kunjungan
    │
    └── Exceptions/
        ├── TeramedikApiException.php    # Exception API
        └── TeramedikAuthException.php   # Exception autentikasi

config/
└── teramedik.php                        # Konfigurasi

database/migrations/
├── 2025_12_19_010000_create_teramedik_sync_records_table.php
└── 2025_12_19_010001_create_teramedik_api_logs_table.php
```

---

## Konfigurasi

### Environment Variables

Tambahkan ke file `.env`:

```env
# Mode: 'mock' untuk development, 'live' untuk production
TERAMEDIK_MODE=mock

# API Configuration (didapat dari Teramedik)
TERAMEDIK_API_URL=https://api.teramedik.com
TERAMEDIK_CLIENT_ID=your-client-id
TERAMEDIK_CLIENT_SECRET=your-client-secret
TERAMEDIK_FACILITY_CODE=RS001

# Optional settings
TERAMEDIK_TIMEOUT=30
TERAMEDIK_RETRY_ATTEMPTS=3
TERAMEDIK_CACHE_ENABLED=true
TERAMEDIK_CACHE_TTL=3600
TERAMEDIK_LOG_ENABLED=true
```

---

## Penggunaan API

### 1. Health Check

```http
GET /teramedik/health
```

Response:
```json
{
  "success": true,
  "data": {
    "status": "ok",
    "mode": "mock",
    "message": "Running in mock mode"
  }
}
```

### 2. Search Pasien

```http
GET /teramedik/patients/search?q=Siti
```

### 3. Get Data Ibu (untuk form autofill)

```http
GET /teramedik/mother-data?rm=RM-2024-001234
# atau
GET /teramedik/mother-data?nik=3201234567890001
```

Response:
```json
{
  "success": true,
  "data": {
    "mother_name": "Siti Rahayu",
    "mother_medical_record_no": "RM-2024-001234",
    "mother_ktp": "3201234567890001",
    "mother_address": "Jl. Merdeka No. 123",
    "mother_occupation": "Ibu Rumah Tangga",
    "mother_blood_type": "O"
  },
  "source": "teramedik"
}
```

### 4. Get Data Ayah

```http
GET /teramedik/father-data?nik=3201234567890003
```

### 5. Get Daftar Dokter

```http
GET /teramedik/doctors
```

### 6. Sync Dokter ke Database Lokal

```http
POST /teramedik/doctors/sync
```

### 7. Get Pasien Rawat Inap Kebidanan

```http
GET /teramedik/encounters/maternity
```

### 8. Push Data Kelahiran ke Teramedik

```http
POST /teramedik/birth-records/{id}/push
```

### 9. Cek Status Sync

```http
GET /teramedik/birth-records/{id}/sync-status
```

---

## Mock Mode

Saat `TERAMEDIK_MODE=mock`, sistem menggunakan data dummy dari `MockTeramedikService`.

### Data Mock yang Tersedia:

**Pasien:**
| No. RM | Nama | NIK | Gender |
|--------|------|-----|--------|
| RM-2024-001234 | Siti Rahayu | 3201234567890001 | P |
| RM-2024-001235 | Dewi Lestari | 3201234567890002 | P |
| RM-2024-001236 | Ahmad Hidayat | 3201234567890003 | L |

**Dokter:**
| Nama | No. SIP | Spesialisasi |
|------|---------|--------------|
| dr. Andi Pratama, Sp.OG | SIP-449/SPOG/2020 | Obstetri dan Ginekologi |
| dr. Budi Santoso, Sp.OG | SIP-550/SPOG/2019 | Obstetri dan Ginekologi |
| dr. Citra Dewi, Sp.A | SIP-661/SPA/2021 | Pediatri |

---

## Langkah Implementasi Live

### 1. Request Dokumentasi API dari Teramedik

Hubungi Tim IT RS Maranatha untuk meminta:
- [ ] API Documentation
- [ ] Client ID & Client Secret
- [ ] Sandbox/Development Environment URL
- [ ] Facility Code

### 2. Update Konfigurasi

```env
TERAMEDIK_MODE=live
TERAMEDIK_API_URL=https://actual-teramedik-api-url.com
TERAMEDIK_CLIENT_ID=real-client-id
TERAMEDIK_CLIENT_SECRET=real-client-secret
TERAMEDIK_FACILITY_CODE=actual-facility-code
```

### 3. Sesuaikan Field Mapping

Edit `config/teramedik.php` bagian `field_mapping` sesuai dengan struktur data Teramedik yang sebenarnya.

### 4. Test Koneksi

```bash
# Via Artisan Tinker
php artisan tinker
>>> app(\App\Services\Teramedik\TeramedikClient::class)->healthCheck()
```

### 5. Enable Production Mode

```env
TERAMEDIK_MODE=live
TERAMEDIK_LOG_ENABLED=true  # Untuk audit
```

---

## Penggunaan di Frontend (Vue)

### Contoh: Autofill Data Ibu

```javascript
// Di form BirthRecord/Create.vue

const fetchMotherData = async (rmNumber) => {
    try {
        const response = await axios.get('/teramedik/mother-data', {
            params: { rm: rmNumber }
        });
        
        if (response.data.success) {
            // Autofill form fields
            Object.assign(form, response.data.data);
        }
    } catch (error) {
        console.error('Gagal fetch data ibu:', error);
    }
};
```

---

## Troubleshooting

### Error: "Failed to authenticate"
- Pastikan `TERAMEDIK_CLIENT_ID` dan `TERAMEDIK_CLIENT_SECRET` benar
- Cek apakah token sudah expired

### Error: "Connection timeout"
- Increase `TERAMEDIK_TIMEOUT` value
- Cek koneksi jaringan ke server Teramedik

### Data tidak tersimpan ke sync_records
- Pastikan migration sudah dijalankan
- Cek permission database

---

## Kontak

Untuk bantuan teknis terkait integrasi Teramedik:
- **Tim IT RS Maranatha**: [kontak IT]
- **Teramedik Support**: support@terakorp.com
