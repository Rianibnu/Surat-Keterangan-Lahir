# METODOLOGI PENELITIAN
## Sistem Informasi Surat Keterangan Lahir (SKL) Berbasis Web pada Rumah Sakit

---

## BAB III: METODOLOGI PENELITIAN

### 3.1 Metode Pengembangan Sistem

Pengembangan Sistem Informasi Surat Keterangan Lahir (SKL) ini menggunakan metode **Waterfall (Air Terjun)**. Metode ini dipilih karena cocok untuk proyek dengan requirement yang sudah jelas dan stabil. Tahapan-tahapan dalam metode Waterfall adalah sebagai berikut:

#### 3.1.1 Tahapan Metode Waterfall

```
┌─────────────────────────────────────────────────────────────┐
│                    1. REQUIREMENT ANALYSIS                   │
│                   (Analisis Kebutuhan)                       │
└─────────────────────────┬───────────────────────────────────┘
                          ▼
┌─────────────────────────────────────────────────────────────┐
│                    2. SYSTEM DESIGN                          │
│                   (Desain Sistem)                            │
└─────────────────────────┬───────────────────────────────────┘
                          ▼
┌─────────────────────────────────────────────────────────────┐
│                    3. IMPLEMENTATION                         │
│                   (Implementasi/Pengkodean)                  │
└─────────────────────────┬───────────────────────────────────┘
                          ▼
┌─────────────────────────────────────────────────────────────┐
│                    4. TESTING                                │
│                   (Pengujian)                                │
└─────────────────────────┬───────────────────────────────────┘
                          ▼
┌─────────────────────────────────────────────────────────────┐
│                    5. DEPLOYMENT & MAINTENANCE               │
│                   (Implementasi & Pemeliharaan)              │
└─────────────────────────────────────────────────────────────┘
```

**Penjelasan setiap tahapan:**

1. **Requirement Analysis (Analisis Kebutuhan)**
   - Mengidentifikasi kebutuhan pengguna sistem
   - Menganalisis proses bisnis penerbitan SKL yang berjalan saat ini
   - Menentukan fitur-fitur yang diperlukan dalam sistem
   - Melakukan wawancara dengan stakeholder (bidan, admin RS, dokter)

2. **System Design (Desain Sistem)**
   - Perancangan arsitektur sistem
   - Perancangan database (ERD - Entity Relationship Diagram)
   - Perancangan antarmuka pengguna (UI/UX Design)
   - Perancangan alur sistem (Flowchart, Use Case Diagram, Activity Diagram)

3. **Implementation (Implementasi/Pengkodean)**
   - Pengkodean backend menggunakan Laravel 12
   - Pengkodean frontend menggunakan Vue.js 3
   - Integrasi antara backend dan frontend menggunakan Inertia.js
   - Implementasi fitur-fitur sesuai kebutuhan

4. **Testing (Pengujian)**
   - Pengujian fungsional menggunakan metode Black Box Testing
   - Pengujian validasi input data
   - Pengujian keamanan (autentikasi dan otorisasi)
   - Pengujian User Acceptance Test (UAT)

5. **Deployment & Maintenance (Implementasi & Pemeliharaan)**
   - Deployment sistem ke server produksi
   - Pelatihan pengguna
   - Pemeliharaan dan perbaikan bug

---

### 3.2 Metode Pengumpulan Data

Dalam penelitian ini, pengumpulan data dilakukan dengan beberapa metode:

#### 3.2.1 Observasi
Pengamatan langsung terhadap proses penerbitan Surat Keterangan Lahir yang berlaku saat ini di rumah sakit. Observasi dilakukan untuk:
- Mengidentifikasi alur kerja penerbitan SKL
- Mengetahui dokumen-dokumen yang diperlukan
- Menganalisis permasalahan yang terjadi pada sistem manual

#### 3.2.2 Wawancara
Melakukan wawancara dengan pihak-pihak terkait:
- **Bidan**: Untuk memahami proses input data kelahiran
- **Admin Rumah Sakit**: Untuk memahami proses administrasi dan dokumentasi
- **Dokter Penanggung Jawab**: Untuk memahami proses validasi dan penandatanganan

#### 3.2.3 Studi Pustaka
Mengumpulkan referensi dari berbagai sumber:
- Jurnal ilmiah terkait sistem informasi rumah sakit
- Buku-buku tentang pengembangan aplikasi web
- Dokumentasi resmi framework Laravel dan Vue.js
- Peraturan pemerintah terkait Surat Keterangan Lahir

---

### 3.3 Teknologi dan Alat Pengembangan

#### 3.3.1 Teknologi Backend

| Teknologi | Versi | Fungsi |
|-----------|-------|--------|
| **PHP** | 8.2+ | Bahasa pemrograman server-side |
| **Laravel Framework** | 12.x | Framework PHP untuk pengembangan web |
| **Laravel Fortify** | 1.30 | Authentication scaffolding |
| **Spatie Laravel Permission** | 6.24 | Manajemen Role & Permission (RBAC) |
| **Spatie Activitylog** | 4.10 | Pencatatan audit trail aktivitas sistem |
| **Barryvdh Laravel DomPDF** | 3.1 | Generate dokumen PDF |
| **Inertia.js Laravel** | 2.0 | Bridge antara Laravel dan Vue.js |

#### 3.3.2 Teknologi Frontend

| Teknologi | Versi | Fungsi |
|-----------|-------|--------|
| **Vue.js** | 3.5 | Framework JavaScript reaktif |
| **TypeScript** | 5.2 | Superset JavaScript dengan static typing |
| **Tailwind CSS** | 4.1 | Utility-first CSS framework |
| **Inertia.js Vue** | 2.1 | Client-side adapter Inertia.js |
| **Lucide Icons** | 0.468 | Library ikon vektor |
| **QRCode.vue** | 3.6 | Generator QR Code |
| **Vite** | 7.0 | Build tool dan development server |

#### 3.3.3 Database

| Teknologi | Fungsi |
|-----------|--------|
| **MySQL/MariaDB/PostgreSQL** | Database relasional untuk penyimpanan data |
| **SQLite** | Database untuk development dan testing |

#### 3.3.4 Tools Pengembangan

| Tools | Fungsi |
|-------|--------|
| **Visual Studio Code** | Code editor utama |
| **Git** | Version control system |
| **Composer** | Dependency manager untuk PHP |
| **NPM** | Dependency manager untuk JavaScript |
| **Postman/Insomnia** | Testing API |
| **phpMyAdmin/DBeaver** | Database administration |
| **Figma** | UI/UX Design |

---

### 3.4 Arsitektur Sistem

#### 3.4.1 Arsitektur Aplikasi

Sistem ini menggunakan arsitektur **Monolithic Modern** dengan pendekatan **Single Page Application (SPA)** yang diimplementasikan menggunakan **Inertia.js**:

```
┌─────────────────────────────────────────────────────────────┐
│                        CLIENT (Browser)                      │
│                                                              │
│  ┌────────────────────────────────────────────────────────┐ │
│  │                Vue.js 3 + Inertia.js                   │ │
│  │   • Composition API                                     │ │
│  │   • Component-based Architecture                        │ │
│  │   • Tailwind CSS Styling                                │ │
│  └────────────────────────────────────────────────────────┘ │
└──────────────────────────┬──────────────────────────────────┘
                           │ HTTP Request
                           ▼
┌─────────────────────────────────────────────────────────────┐
│                     SERVER (Laravel 12)                      │
│                                                              │
│  ┌────────────────────────────────────────────────────────┐ │
│  │                    Middleware Layer                     │ │
│  │   • Authentication (Fortify)                            │ │
│  │   • Authorization (Spatie Permission)                   │ │
│  │   • CSRF Protection                                     │ │
│  └────────────────────────────────────────────────────────┘ │
│                           │                                  │
│  ┌────────────────────────────────────────────────────────┐ │
│  │                    Controller Layer                     │ │
│  │   • BirthRecordController                               │ │
│  │   • DoctorController                                    │ │
│  │   • SklPdfController                                    │ │
│  │   • UserController                                      │ │
│  │   • ActivityLogController                               │ │
│  └────────────────────────────────────────────────────────┘ │
│                           │                                  │
│  ┌────────────────────────────────────────────────────────┐ │
│  │                      Model Layer                        │ │
│  │   • BirthRecord                                         │ │
│  │   • Doctor                                              │ │
│  │   • Skl                                                 │ │
│  │   • User                                                │ │
│  └────────────────────────────────────────────────────────┘ │
│                           │                                  │
│  ┌────────────────────────────────────────────────────────┐ │
│  │                    Eloquent ORM                         │ │
│  └────────────────────────────────────────────────────────┘ │
└──────────────────────────┬──────────────────────────────────┘
                           │
                           ▼
┌─────────────────────────────────────────────────────────────┐
│                      DATABASE (MySQL)                        │
│                                                              │
│   Tables: users, birth_records, doctors, skls,              │
│           activity_log, roles, permissions                   │
└─────────────────────────────────────────────────────────────┘
```

#### 3.4.2 Pola Desain (Design Pattern)

Sistem ini mengimplementasikan beberapa design pattern:

1. **MVC (Model-View-Controller)**
   - **Model**: Representasi data dan logika bisnis (Eloquent ORM)
   - **View**: Tampilan antarmuka pengguna (Vue.js Components)
   - **Controller**: Penghubung antara Model dan View

2. **Repository Pattern**
   - Memisahkan logika akses data dari logika bisnis

3. **Service Layer Pattern**
   - Services untuk logika bisnis kompleks (TeramedikService, dll)

4. **RBAC (Role-Based Access Control)**
   - Menggunakan Spatie Laravel Permission untuk manajemen hak akses

---

### 3.5 Perancangan Database

#### 3.5.1 Entity Relationship Diagram (ERD)

```
┌─────────────────┐          ┌─────────────────┐
│     USERS       │          │     DOCTORS     │
├─────────────────┤          ├─────────────────┤
│ id (PK)         │          │ id (PK)         │
│ name            │          │ name            │
│ email           │          │ license_no      │
│ password        │          │ hospital        │
│ role            │          │ signature_path  │
│ created_at      │          │ created_at      │
│ updated_at      │          │ updated_at      │
└────────┬────────┘          └────────┬────────┘
         │                            │
         │ 1                          │ 1
         │                            │
         ▼ M                          ▼ M
┌─────────────────────────────────────────────────────────┐
│                     BIRTH_RECORDS                        │
├─────────────────────────────────────────────────────────┤
│ id (PK)                                                  │
│ baby_name                   │ mother_name                │
│ medical_record_no           │ mother_medical_record_no   │
│ birth_date                  │ mother_id_number           │
│ birth_time                  │ mother_address             │
│ gender                      │ mother_occupation          │
│ child_order                 │ mother_blood_type          │
│ delivery_method             │ father_name                │
│ blood_type                  │ father_id_number           │
│ weight                      │ father_address             │
│ length                      │ father_occupation          │
│ head_circumference          │ father_blood_type          │
│ chest_circumference         │ doctor_id (FK)             │
│ hospital_name               │ created_by_user_id (FK)    │
│ created_at                  │ updated_at                 │
└─────────────────────────────┴────────────────────────────┘
         │
         │ 1
         │
         ▼ 1
┌─────────────────────────────────────────────────────────┐
│                         SKLS                             │
├─────────────────────────────────────────────────────────┤
│ id (PK)                                                  │
│ birth_record_id (FK)                                     │
│ uuid (Unique)                                            │
│ document_number                                          │
│ issue_date                                               │
│ signer_name                                              │
│ signer_role                                              │
│ is_signed                                                │
│ created_at                                               │
│ updated_at                                               │
└─────────────────────────────────────────────────────────┘
```

#### 3.5.2 Relasi Antar Tabel

| Tabel Asal | Relasi | Tabel Tujuan | Keterangan |
|------------|--------|--------------|------------|
| users | One to Many | birth_records | Satu user dapat membuat banyak data kelahiran |
| doctors | One to Many | birth_records | Satu dokter dapat menangani banyak kelahiran |
| birth_records | One to One | skls | Satu data kelahiran memiliki satu SKL |

#### 3.5.3 Class Diagram

```
┌──────────────────────────────────────────────────────────────────────────────────────────────┐
│                                        CLASS DIAGRAM                                          │
│                              SISTEM INFORMASI SURAT KETERANGAN LAHIR                          │
└──────────────────────────────────────────────────────────────────────────────────────────────┘

┌─────────────────────────────────┐      ┌─────────────────────────────────┐
│            «Model»              │      │            «Model»              │
│             User                │      │            Doctor               │
├─────────────────────────────────┤      ├─────────────────────────────────┤
│ - id: int                       │      │ - id: int                       │
│ - name: string                  │      │ - name: string                  │
│ - email: string                 │      │ - license_no: string            │
│ - password: string              │      │ - hospital: string              │
│ - email_verified_at: timestamp  │      │ - signature_path: string        │
│ - remember_token: string        │      │ - created_at: timestamp         │
│ - created_at: timestamp         │      │ - updated_at: timestamp         │
│ - updated_at: timestamp         │      ├─────────────────────────────────┤
├─────────────────────────────────┤      │ + birthRecords(): HasMany       │
│ + birthRecords(): HasMany       │      │ + getSignatureUrl(): string     │
│ + hasRole(): bool               │      └─────────────────────────────────┘
│ + hasPermission(): bool         │                    │
│ + assignRole(): void            │                    │ 1
│ + roles(): BelongsToMany        │                    │
└─────────────────────────────────┘                    │
              │                                        │
              │ 1                                      │
              │                                        ▼ *
              ▼ *                      ┌─────────────────────────────────┐
┌─────────────────────────────────┐    │            «Model»              │
│            «Model»              │    │          BirthRecord            │
│          BirthRecord            │◄───┤─────────────────────────────────│
├─────────────────────────────────┤    │ - id: int                       │
│ DATA BAYI:                      │    │ - baby_name: string             │
│ - baby_name: string             │    │ - medical_record_no: string     │
│ - medical_record_no: string     │    │ - birth_date: date              │
│ - birth_date: date              │    │ - birth_time: time              │
│ - birth_time: time              │    │ - gender: enum                  │
│ - gender: enum                  │    │ - child_order: int              │
│ - child_order: int              │    │ - delivery_method: enum         │
│ - delivery_method: enum         │    │ - blood_type: enum              │
│ - blood_type: enum              │    │ - weight: decimal               │
│ - weight: decimal (gram)        │    │ - length: decimal               │
│ - length: decimal (cm)          │    │ - head_circumference: decimal   │
│ - head_circumference: decimal   │    │ - chest_circumference: decimal  │
│ - chest_circumference: decimal  │    │ - hospital_name: string         │
│ - hospital_name: string         │    │                                 │
├─────────────────────────────────┤    │ DATA IBU:                       │
│ DATA IBU:                       │    │ - mother_name: string           │
│ - mother_name: string           │    │ - mother_medical_record_no      │
│ - mother_medical_record_no      │    │ - mother_id_number: string      │
│ - mother_id_number: string      │    │ - mother_address: text          │
│ - mother_address: text          │    │ - mother_occupation: string     │
│ - mother_occupation: string     │    │ - mother_blood_type: enum       │
│ - mother_blood_type: enum       │    │                                 │
├─────────────────────────────────┤    │ DATA AYAH:                      │
│ DATA AYAH:                      │    │ - father_name: string           │
│ - father_name: string           │    │ - father_id_number: string      │
│ - father_id_number: string      │    │ - father_address: text          │
│ - father_address: text          │    │ - father_occupation: string     │
│ - father_occupation: string     │    │ - father_blood_type: enum       │
│ - father_blood_type: enum       │    │                                 │
├─────────────────────────────────┤    │ FOREIGN KEYS:                   │
│ FOREIGN KEYS:                   │    │ - doctor_id: int (FK)           │
│ - doctor_id: int (FK)           │    │ - created_by_user_id: int (FK)  │
│ - created_by_user_id: int (FK)  │    ├─────────────────────────────────┤
├─────────────────────────────────┤    │ + doctor(): BelongsTo           │
│ + doctor(): BelongsTo           │    │ + createdBy(): BelongsTo        │
│ + createdBy(): BelongsTo        │    │ + skl(): HasOne                 │
│ + skl(): HasOne                 │    └─────────────────────────────────┘
└─────────────────────────────────┘                    │
              │                                        │ 1
              │ 1                                      │
              │                                        ▼ 1
              ▼ 1                      ┌─────────────────────────────────┐
┌─────────────────────────────────┐    │            «Model»              │
│            «Model»              │    │              Skl                │
│              Skl                │◄───┤─────────────────────────────────│
├─────────────────────────────────┤    │ - id: int                       │
│ - id: int                       │    │ - birth_record_id: int (FK)     │
│ - birth_record_id: int (FK)     │    │ - uuid: string (unique)         │
│ - uuid: string (unique)         │    │ - document_number: string       │
│ - document_number: string       │    │ - issue_date: date              │
│ - issue_date: date              │    │ - signer_name: string           │
│ - signer_name: string           │    │ - signer_role: string           │
│ - signer_role: string           │    │ - is_signed: boolean            │
│ - is_signed: boolean            │    │ - created_at: timestamp         │
│ - created_at: timestamp         │    │ - updated_at: timestamp         │
│ - updated_at: timestamp         │    ├─────────────────────────────────┤
├─────────────────────────────────┤    │ + birthRecord(): BelongsTo      │
│ + birthRecord(): BelongsTo      │    │ + getVerificationUrl(): string  │
│ + getVerificationUrl(): string  │    │ + generateDocumentNumber()      │
│ + generateDocumentNumber()      │    │ + generateQrCode(): string      │
│ + generateQrCode(): string      │    └─────────────────────────────────┘
└─────────────────────────────────┘

┌──────────────────────────────────────────────────────────────────────────────────────────────┐
│                                        CONTROLLER CLASSES                                     │
└──────────────────────────────────────────────────────────────────────────────────────────────┘

┌─────────────────────────────────┐      ┌─────────────────────────────────┐
│         «Controller»            │      │         «Controller»            │
│     BirthRecordController       │      │       DoctorController          │
├─────────────────────────────────┤      ├─────────────────────────────────┤
│ + index(): Response             │      │ + index(): Response             │
│ + create(): Response            │      │ + create(): Response            │
│ + store(Request): Response      │      │ + store(Request): Response      │
│ + show(id): Response            │      │ + show(id): Response            │
│ + edit(id): Response            │      │ + edit(id): Response            │
│ + update(Request, id): Response │      │ + update(Request, id): Response │
│ + destroy(id): Response         │      │ + destroy(id): Response         │
└─────────────────────────────────┘      └─────────────────────────────────┘

┌─────────────────────────────────┐      ┌─────────────────────────────────┐
│         «Controller»            │      │         «Controller»            │
│       SklPdfController          │      │      PublicSklController        │
├─────────────────────────────────┤      ├─────────────────────────────────┤
│ + generate(id): Response        │      │ + verify(uuid): Response        │
│ + preview(id): Response         │      │ + validateQrCode(data): bool    │
│ + download(id): Response        │      └─────────────────────────────────┘
│ + generateQrCode(skl): string   │
└─────────────────────────────────┘

┌─────────────────────────────────┐      ┌─────────────────────────────────┐
│         «Controller»            │      │         «Controller»            │
│        UserController           │      │    ActivityLogController        │
├─────────────────────────────────┤      ├─────────────────────────────────┤
│ + index(): Response             │      │ + index(): Response             │
│ + create(): Response            │      │ + show(id): Response            │
│ + store(Request): Response      │      │ + filter(Request): Response     │
│ + edit(id): Response            │      │ + export(): Response            │
│ + update(Request, id): Response │      └─────────────────────────────────┘
│ + destroy(id): Response         │
│ + assignRole(id, role): void    │
└─────────────────────────────────┘
```

#### 3.5.4 Sequence Diagram

**A. Sequence Diagram: Proses Login**

```
┌──────────┐          ┌──────────────┐          ┌────────────────┐          ┌──────────┐
│  User    │          │  LoginPage   │          │ AuthController │          │ Database │
│ (Bidan)  │          │   (Vue.js)   │          │   (Laravel)    │          │  (MySQL) │
└────┬─────┘          └──────┬───────┘          └───────┬────────┘          └────┬─────┘
     │                       │                          │                        │
     │  1. Akses Halaman     │                          │                        │
     │──────────────────────▶│                          │                        │
     │                       │                          │                        │
     │  2. Tampilkan Form    │                          │                        │
     │◀──────────────────────│                          │                        │
     │                       │                          │                        │
     │  3. Input Email &     │                          │                        │
     │     Password          │                          │                        │
     │──────────────────────▶│                          │                        │
     │                       │                          │                        │
     │                       │  4. POST /login          │                        │
     │                       │  (email, password)       │                        │
     │                       │─────────────────────────▶│                        │
     │                       │                          │                        │
     │                       │                          │  5. SELECT user        │
     │                       │                          │  WHERE email = ?       │
     │                       │                          │───────────────────────▶│
     │                       │                          │                        │
     │                       │                          │  6. Return User Data   │
     │                       │                          │◀───────────────────────│
     │                       │                          │                        │
     │                       │                          │  7. Verify Password    │
     │                       │                          │  (bcrypt compare)      │
     │                       │                          │─────────┐              │
     │                       │                          │         │              │
     │                       │                          │◀────────┘              │
     │                       │                          │                        │
     │                       │  8. Create Session       │                        │
     │                       │  Return Auth Token       │                        │
     │                       │◀─────────────────────────│                        │
     │                       │                          │                        │
     │                       │  9. Log Activity         │                        │
     │                       │  (login success)         │                        │
     │                       │─────────────────────────▶│                        │
     │                       │                          │───────────────────────▶│
     │                       │                          │                        │
     │  10. Redirect to      │                          │                        │
     │      Dashboard        │                          │                        │
     │◀──────────────────────│                          │                        │
     │                       │                          │                        │
     ▼                       ▼                          ▼                        ▼
```

**B. Sequence Diagram: Proses Input Data Kelahiran**

```
┌──────────┐      ┌──────────────┐      ┌────────────────────┐      ┌──────────┐
│  Bidan   │      │  CreatePage  │      │ BirthRecordController│    │ Database │
└────┬─────┘      └──────┬───────┘      └─────────┬──────────┘      └────┬─────┘
     │                   │                        │                      │
     │ 1. Klik Tambah    │                        │                      │
     │    Data Baru      │                        │                      │
     │──────────────────▶│                        │                      │
     │                   │                        │                      │
     │                   │ 2. GET /birth-records  │                      │
     │                   │    /create             │                      │
     │                   │───────────────────────▶│                      │
     │                   │                        │                      │
     │                   │                        │ 3. SELECT doctors    │
     │                   │                        │───────────────────── ▶│
     │                   │                        │                      │
     │                   │                        │ 4. Return Doctors    │
     │                   │                        │◀─────────────────────│
     │                   │                        │                      │
     │                   │ 5. Return Form +       │                      │
     │                   │    Doctors List        │                      │
     │                   │◀───────────────────────│                      │
     │                   │                        │                      │
     │ 6. Tampilkan      │                        │                      │
     │    Form Input     │                        │                      │
     │◀──────────────────│                        │                      │
     │                   │                        │                      │
     │ 7. Input Data:    │                        │                      │
     │    - Data Bayi    │                        │                      │
     │    - Data Ibu     │                        │                      │
     │    - Data Ayah    │                        │                      │
     │    - Pilih Dokter │                        │                      │
     │──────────────────▶│                        │                      │
     │                   │                        │                      │
     │ 8. Klik Submit    │                        │                      │
     │──────────────────▶│                        │                      │
     │                   │                        │                      │
     │                   │ 9. POST /birth-records │                      │
     │                   │    (form data)         │                      │
     │                   │───────────────────────▶│                      │
     │                   │                        │                      │
     │                   │                        │ 10. Validate Input   │
     │                   │                        │─────────┐            │
     │                   │                        │         │            │
     │                   │                        │◀────────┘            │
     │                   │                        │                      │
     │                   │                        │ 11. INSERT           │
     │                   │                        │     birth_records    │
     │                   │                        │─────────────────────▶│
     │                   │                        │                      │
     │                   │                        │ 12. Return ID        │
     │                   │                        │◀─────────────────────│
     │                   │                        │                      │
     │                   │                        │ 13. Generate SKL     │
     │                   │                        │     Number & UUID    │
     │                   │                        │─────────┐            │
     │                   │                        │         │            │
     │                   │                        │◀────────┘            │
     │                   │                        │                      │
     │                   │                        │ 14. INSERT skls      │
     │                   │                        │─────────────────────▶│
     │                   │                        │                      │
     │                   │                        │ 15. Log Activity     │
     │                   │                        │─────────────────────▶│
     │                   │                        │                      │
     │                   │ 16. Success Response   │                      │
     │                   │     + Redirect         │                      │
     │                   │◀───────────────────────│                      │
     │                   │                        │                      │
     │ 17. Tampilkan     │                        │                      │
     │     Notifikasi    │                        │                      │
     │     Sukses        │                        │                      │
     │◀──────────────────│                        │                      │
     │                   │                        │                      │
     ▼                   ▼                        ▼                      ▼
```

**C. Sequence Diagram: Proses Generate & Cetak PDF SKL**

```
┌──────────┐      ┌──────────────┐      ┌────────────────┐      ┌──────────┐      ┌─────────┐
│  Bidan   │      │  DetailPage  │      │ SklPdfController│     │ Database │      │ DomPDF  │
└────┬─────┘      └──────┬───────┘      └───────┬────────┘      └────┬─────┘      └────┬────┘
     │                   │                      │                    │                 │
     │ 1. Klik Cetak     │                      │                    │                 │
     │    SKL            │                      │                    │                 │
     │──────────────────▶│                      │                    │                 │
     │                   │                      │                    │                 │
     │                   │ 2. GET /skl/{id}/pdf │                    │                 │
     │                   │─────────────────────▶│                    │                 │
     │                   │                      │                    │                 │
     │                   │                      │ 3. SELECT          │                 │
     │                   │                      │    birth_record    │                 │
     │                   │                      │    + skl + doctor  │                 │
     │                   │                      │───────────────────▶│                 │
     │                   │                      │                    │                 │
     │                   │                      │ 4. Return Data     │                 │
     │                   │                      │◀───────────────────│                 │
     │                   │                      │                    │                 │
     │                   │                      │ 5. Generate        │                 │
     │                   │                      │    QR Code URL     │                 │
     │                   │                      │─────────┐          │                 │
     │                   │                      │         │          │                 │
     │                   │                      │◀────────┘          │                 │
     │                   │                      │                    │                 │
     │                   │                      │ 6. Load PDF        │                 │
     │                   │                      │    Template        │                 │
     │                   │                      │────────────────────────────────────▶│
     │                   │                      │                    │                 │
     │                   │                      │ 7. Render with     │                 │
     │                   │                      │    Data + QR       │                 │
     │                   │                      │────────────────────────────────────▶│
     │                   │                      │                    │                 │
     │                   │                      │ 8. Return PDF      │                 │
     │                   │                      │    Binary Stream   │                 │
     │                   │                      │◀────────────────────────────────────│
     │                   │                      │                    │                 │
     │                   │                      │ 9. Log Activity    │                 │
     │                   │                      │    (print SKL)     │                 │
     │                   │                      │───────────────────▶│                 │
     │                   │                      │                    │                 │
     │                   │ 10. Return PDF       │                    │                 │
     │                   │     Response         │                    │                 │
     │                   │◀─────────────────────│                    │                 │
     │                   │                      │                    │                 │
     │ 11. Display PDF   │                      │                    │                 │
     │     / Download    │                      │                    │                 │
     │◀──────────────────│                      │                    │                 │
     │                   │                      │                    │                 │
     ▼                   ▼                      ▼                    ▼                 ▼
```

**D. Sequence Diagram: Proses Verifikasi SKL via QR Code**

```
┌───────────┐     ┌──────────────┐     ┌────────────────────┐     ┌──────────┐
│  Publik   │     │   Browser    │     │ PublicSklController│     │ Database │
│(Pihak Ke-3)│    │              │     │                    │     │          │
└─────┬─────┘     └──────┬───────┘     └─────────┬──────────┘     └────┬─────┘
      │                  │                       │                     │
      │ 1. Scan QR Code  │                       │                     │
      │    pada SKL      │                       │                     │
      │─────────┐        │                       │                     │
      │         │        │                       │                     │
      │◀────────┘        │                       │                     │
      │                  │                       │                     │
      │ 2. Open URL      │                       │                     │
      │    /verify/{uuid}│                       │                     │
      │─────────────────▶│                       │                     │
      │                  │                       │                     │
      │                  │ 3. GET /verify/{uuid} │                     │
      │                  │──────────────────────▶│                     │
      │                  │                       │                     │
      │                  │                       │ 4. SELECT skl       │
      │                  │                       │    WHERE uuid = ?   │
      │                  │                       │────────────────────▶│
      │                  │                       │                     │
      │                  │                       │ 5. Return SKL +     │
      │                  │                       │    BirthRecord      │
      │                  │                       │◀────────────────────│
      │                  │                       │                     │
      │                  │                       │ 6. Validate SKL     │
      │                  │                       │    Authenticity     │
      │                  │                       │─────────┐           │
      │                  │                       │         │           │
      │                  │                       │◀────────┘           │
      │                  │                       │                     │
      │                  │ 7. Return             │                     │
      │                  │    Verification Page  │                     │
      │                  │    (Valid/Invalid)    │                     │
      │                  │◀──────────────────────│                     │
      │                  │                       │                     │
      │ 8. Tampilkan     │                       │                     │
      │    Status        │                       │                     │
      │    Verifikasi    │                       │                     │
      │    + Data SKL    │                       │                     │
      │◀─────────────────│                       │                     │
      │                  │                       │                     │
      ▼                  ▼                       ▼                     ▼
```

---

### 3.6 Perancangan Alur Sistem

#### 3.6.1 Use Case Diagram

```
                              ┌─────────────────────────────────────┐
                              │         SISTEM INFORMASI SKL        │
                              │                                     │
    ┌────────┐               │  ┌───────────────────────────────┐  │
    │        │───────────────┼─▶│         Login Sistem          │  │
    │ Bidan  │               │  └───────────────────────────────┘  │
    │        │               │                                     │
    │        │───────────────┼─▶│     Input Data Kelahiran      │  │
    │        │               │  └───────────────────────────────┘  │
    │        │               │                                     │
    │        │───────────────┼─▶│       Lihat Data Kelahiran    │  │
    │        │               │  └───────────────────────────────┘  │
    │        │               │                                     │
    │        │───────────────┼─▶│        Cetak SKL (PDF)        │  │
    └────────┘               │  └───────────────────────────────┘  │
                              │                                     │
    ┌────────┐               │  ┌───────────────────────────────┐  │
    │        │───────────────┼─▶│      Manajemen Pengguna       │  │
    │ Admin  │               │  └───────────────────────────────┘  │
    │        │               │                                     │
    │        │───────────────┼─▶│      Manajemen Dokter         │  │
    │        │               │  └───────────────────────────────┘  │
    │        │               │                                     │
    │        │───────────────┼─▶│    Lihat Activity Log         │  │
    │        │               │  └───────────────────────────────┘  │
    │        │               │                                     │
    │        │───────────────┼─▶│          Dashboard            │  │
    └────────┘               │  └───────────────────────────────┘  │
                              │                                     │
    ┌────────┐               │  ┌───────────────────────────────┐  │
    │ Super  │───────────────┼─▶│      Akses Penuh Sistem       │  │
    │ Admin  │               │  └───────────────────────────────┘  │
    └────────┘               │                                     │
                              │                                     │
    ┌────────┐               │  ┌───────────────────────────────┐  │
    │ Publik │───────────────┼─▶│    Verifikasi SKL (QR Code)   │  │
    └────────┘               │  └───────────────────────────────┘  │
                              │                                     │
                              └─────────────────────────────────────┘
```

#### 3.6.2 Activity Diagram - Proses Penerbitan SKL

```
┌─────────┐     ┌─────────┐     ┌─────────────┐
│  Bidan  │     │  Sistem │     │  Database   │
└────┬────┘     └────┬────┘     └──────┬──────┘
     │               │                  │
     │  Login        │                  │
     ├──────────────▶│                  │
     │               │  Validasi        │
     │               ├─────────────────▶│
     │               │                  │
     │  ◀────────────┤  Hasil Validasi  │
     │               │◀─────────────────┤
     │               │                  │
     │  Input Data   │                  │
     │  Kelahiran    │                  │
     ├──────────────▶│                  │
     │               │                  │
     │               │  Validasi Input  │
     │               ├─────────────────▶│
     │               │                  │
     │               │  Simpan Data     │
     │               ├─────────────────▶│
     │               │                  │
     │               │  Generate Nomor  │
     │               │  SKL & UUID      │
     │               ├─────────────────▶│
     │               │                  │
     │  ◀────────────┤  Sukses          │
     │               │                  │
     │  Cetak PDF    │                  │
     ├──────────────▶│                  │
     │               │                  │
     │               │  Generate PDF    │
     │               │  + QR Code       │
     │               ├─────────────────▶│
     │               │                  │
     │  ◀────────────┤  PDF Ready       │
     │               │                  │
     ▼               ▼                  ▼
```

#### 3.6.3 Flowchart Detail Proses Sistem

**A. Flowchart: Proses Login Sistem**

```
                              ┌─────────────────┐
                              │     MULAI       │
                              └────────┬────────┘
                                       │
                                       ▼
                              ┌─────────────────┐
                              │  Akses Halaman  │
                              │     Login       │
                              └────────┬────────┘
                                       │
                                       ▼
                              ┌─────────────────┐
                              │ Tampilkan Form  │
                              │     Login       │
                              └────────┬────────┘
                                       │
                                       ▼
                         ┌─────────────────────────────┐
                         │   Input Email & Password    │
                         └─────────────┬───────────────┘
                                       │
                                       ▼
                              ┌─────────────────┐
                              │  Klik Tombol    │
                              │     Login       │
                              └────────┬────────┘
                                       │
                                       ▼
                    ┌──────────────────────────────────────┐
                    │         Validasi Format Input        │
                    │    (Email valid? Password >= 8?)     │
                    └──────────────────┬───────────────────┘
                                       │
                           ┌───────────┴───────────┐
                           │                       │
                       Tidak Valid              Valid
                           │                       │
                           ▼                       ▼
              ┌─────────────────────┐    ┌─────────────────────┐
              │  Tampilkan Pesan   │    │   Query Database    │
              │  Error Validasi    │    │   Cari User by      │
              └─────────┬──────────┘    │   Email             │
                        │               └──────────┬──────────┘
                        │                          │
                        │              ┌───────────┴───────────┐
                        │              │                       │
                        │        User Tidak Ada           User Ditemukan
                        │              │                       │
                        │              ▼                       ▼
                        │    ┌─────────────────┐    ┌─────────────────────┐
                        │    │ Tampilkan Pesan │    │  Verifikasi Password │
                        │    │ "User tidak     │    │  (bcrypt compare)    │
                        │    │  ditemukan"     │    └──────────┬───────────┘
                        │    └─────────┬───────┘               │
                        │              │           ┌───────────┴───────────┐
                        │              │           │                       │
                        │              │      Tidak Cocok              Cocok
                        │              │           │                       │
                        │              │           ▼                       ▼
                        │              │  ┌─────────────────┐    ┌─────────────────────┐
                        │              │  │ Tampilkan Pesan │    │   Log Activity      │
                        │              │  │ "Password       │    │   "Login Success"   │
                        │              │  │  salah"         │    └──────────┬──────────┘
                        │              │  └─────────┬───────┘               │
                        │              │            │                       ▼
                        │              │            │          ┌─────────────────────┐
                        │              │            │          │   Buat Session &    │
                        │              │            │          │   Auth Token        │
                        │              │            │          └──────────┬──────────┘
                        │              │            │                     │
                        │              │            │                     ▼
                        │◀─────────────┴────────────┘          ┌─────────────────────┐
                        │                                      │  Cek Role User      │
                        │                                      └──────────┬──────────┘
                        │                                                 │
                        │                                     ┌───────────┴───────────┐
                        │                                     │           │           │
                        │                                 Super Admin   Admin      Bidan
                        │                                     │           │           │
                        │                                     └───────────┼───────────┘
                        │                                                 │
                        │                                                 ▼
                        │                                      ┌─────────────────────┐
                        │                                      │  Redirect ke        │
                        │                                      │  Dashboard          │
                        │                                      └──────────┬──────────┘
                        │                                                 │
                        ▼                                                 ▼
              ┌─────────────────┐                             ┌─────────────────┐
              │  Kembali ke     │                             │     SELESAI     │
              │  Form Login     │                             └─────────────────┘
              └─────────────────┘
```

**B. Flowchart: Proses Input Data Kelahiran**

```
                              ┌─────────────────┐
                              │     MULAI       │
                              └────────┬────────┘
                                       │
                                       ▼
                              ┌─────────────────┐
                              │  Klik Menu      │
                              │  "Tambah Data"  │
                              └────────┬────────┘
                                       │
                                       ▼
                    ┌──────────────────────────────────────┐
                    │     Cek Hak Akses User               │
                    │ (Apakah memiliki permission create?) │
                    └──────────────────┬───────────────────┘
                                       │
                           ┌───────────┴───────────┐
                           │                       │
                     Tidak Punya                 Punya
                           │                       │
                           ▼                       ▼
              ┌─────────────────────┐    ┌─────────────────────┐
              │  Tampilkan Pesan   │    │  Load Data Dokter   │
              │  "Akses Ditolak"   │    │  dari Database      │
              └─────────┬──────────┘    └──────────┬──────────┘
                        │                          │
                        ▼                          ▼
              ┌─────────────────┐        ┌─────────────────────┐
              │  Redirect ke    │        │  Tampilkan Form     │
              │  Dashboard      │        │  Input Kelahiran    │
              └─────────────────┘        └──────────┬──────────┘
                                                   │
                                                   ▼
                         ┌─────────────────────────────────────────────┐
                         │              INPUT DATA                      │
                         ├──────────────────────────────────────────────┤
                         │  📋 DATA BAYI:                               │
                         │  • Nama Bayi                                 │
                         │  • No. Rekam Medis                           │
                         │  • Tanggal & Jam Lahir                       │
                         │  • Jenis Kelamin                             │
                         │  • Anak Ke-                                  │
                         │  • Cara Persalinan                           │
                         │  • Golongan Darah                            │
                         │  • Berat, Panjang, Lingkar Kepala/Dada       │
                         ├──────────────────────────────────────────────┤
                         │  👩 DATA IBU:                                │
                         │  • Nama, NIK, Alamat, Pekerjaan, Gol. Darah  │
                         ├──────────────────────────────────────────────┤
                         │  👨 DATA AYAH:                               │
                         │  • Nama, NIK, Alamat, Pekerjaan, Gol. Darah  │
                         ├──────────────────────────────────────────────┤
                         │  🏥 DOKTER PENANGGUNG JAWAB:                 │
                         │  • Pilih dari dropdown                       │
                         └──────────────────────┬──────────────────────┘
                                                │
                                                ▼
                              ┌─────────────────────────┐
                              │    Klik Tombol Simpan   │
                              └────────────┬────────────┘
                                           │
                                           ▼
                    ┌──────────────────────────────────────┐
                    │       Validasi Input Server-Side     │
                    │  • Required fields terisi?           │
                    │  • Format data valid?                │
                    │  • NIK 16 digit?                     │
                    └──────────────────┬───────────────────┘
                                       │
                           ┌───────────┴───────────┐
                           │                       │
                       Tidak Valid              Valid
                           │                       │
                           ▼                       ▼
              ┌─────────────────────┐    ┌─────────────────────┐
              │  Tampilkan Pesan   │    │  INSERT ke Tabel    │
              │  Error Validasi    │    │  birth_records      │
              │  (per field)       │    └──────────┬──────────┘
              └─────────┬──────────┘               │
                        │                          ▼
                        │              ┌─────────────────────┐
                        │              │  Generate Nomor SKL │
                        │              │  Format: SKL/RM-    │
                        │              │  RSUKM/XXXX/YYYY    │
                        │              └──────────┬──────────┘
                        │                         │
                        │                         ▼
                        │              ┌─────────────────────┐
                        │              │  Generate UUID      │
                        │              │  untuk Verifikasi   │
                        │              └──────────┬──────────┘
                        │                         │
                        │                         ▼
                        │              ┌─────────────────────┐
                        │              │  INSERT ke Tabel    │
                        │              │  skls               │
                        │              └──────────┬──────────┘
                        │                         │
                        │                         ▼
                        │              ┌─────────────────────┐
                        │              │  Log Activity       │
                        │              │  "created birth     │
                        │              │   record"           │
                        │              └──────────┬──────────┘
                        │                         │
                        ▼                         ▼
              ┌─────────────────┐    ┌─────────────────────┐
              │  Kembali ke     │    │  Tampilkan Pesan    │
              │  Form (fix)     │    │  "Data Berhasil     │
              └─────────────────┘    │   Disimpan"         │
                                     └──────────┬──────────┘
                                                │
                                                ▼
                                     ┌─────────────────────┐
                                     │  Redirect ke        │
                                     │  Halaman Detail     │
                                     └──────────┬──────────┘
                                                │
                                                ▼
                                     ┌─────────────────┐
                                     │     SELESAI     │
                                     └─────────────────┘
```

**C. Flowchart: Proses Cetak SKL (PDF)**

```
                              ┌─────────────────┐
                              │     MULAI       │
                              └────────┬────────┘
                                       │
                                       ▼
                              ┌─────────────────┐
                              │  Buka Halaman   │
                              │  Detail SKL     │
                              └────────┬────────┘
                                       │
                                       ▼
                              ┌─────────────────┐
                              │  Klik Tombol    │
                              │  "Cetak PDF"    │
                              └────────┬────────┘
                                       │
                                       ▼
                    ┌──────────────────────────────────────┐
                    │     Cek Hak Akses User               │
                    │ (Apakah memiliki permission print?)  │
                    └──────────────────┬───────────────────┘
                                       │
                           ┌───────────┴───────────┐
                           │                       │
                     Tidak Punya                 Punya
                           │                       │
                           ▼                       ▼
              ┌─────────────────────┐    ┌─────────────────────┐
              │  Tampilkan Pesan   │    │  Query Data SKL     │
              │  "Akses Ditolak"   │    │  + Birth Record     │
              └─────────────────────┘   │  + Doctor           │
                                        └──────────┬──────────┘
                                                   │
                                                   ▼
                                        ┌─────────────────────┐
                                        │  Cek Data Lengkap?  │
                                        └──────────┬──────────┘
                                                   │
                                       ┌───────────┴───────────┐
                                       │                       │
                                  Tidak Lengkap            Lengkap
                                       │                       │
                                       ▼                       ▼
                          ┌─────────────────────┐    ┌─────────────────────┐
                          │  Tampilkan Pesan   │    │  Generate URL       │
                          │  "Data Belum       │    │  Verifikasi QR      │
                          │   Lengkap"         │    │  /verify/{uuid}     │
                          └─────────────────────┘   └──────────┬──────────┘
                                                               │
                                                               ▼
                                                    ┌─────────────────────┐
                                                    │  Generate QR Code   │
                                                    │  Image (Base64)     │
                                                    └──────────┬──────────┘
                                                               │
                                                               ▼
                                                    ┌─────────────────────┐
                                                    │  Load Template      │
                                                    │  PDF SKL            │
                                                    │  (Blade View)       │
                                                    └──────────┬──────────┘
                                                               │
                                                               ▼
                                        ┌─────────────────────────────────────┐
                                        │        RENDER PDF CONTENT           │
                                        ├─────────────────────────────────────┤
                                        │  📄 Header Rumah Sakit              │
                                        │  📄 Nomor Surat & Tanggal           │
                                        │  📄 Data Bayi (Bilingual ID/EN)     │
                                        │  📄 Data Ibu                        │
                                        │  📄 Data Ayah                       │
                                        │  📄 Tanda Tangan Dokter             │
                                        │  📄 QR Code Verifikasi              │
                                        └──────────────────┬──────────────────┘
                                                           │
                                                           ▼
                                                ┌─────────────────────┐
                                                │  DomPDF Render      │
                                                │  HTML to PDF        │
                                                └──────────┬──────────┘
                                                           │
                                                           ▼
                                                ┌─────────────────────┐
                                                │  Log Activity       │
                                                │  "printed SKL       │
                                                │   #document_number" │
                                                └──────────┬──────────┘
                                                           │
                                                           ▼
                                       ┌───────────────────┴───────────────────┐
                                       │                                       │
                                   Preview                               Download
                                       │                                       │
                                       ▼                                       ▼
                          ┌─────────────────────┐              ┌─────────────────────┐
                          │  Tampilkan PDF      │              │  Download File      │
                          │  di Browser         │              │  SKL_[nama]_        │
                          │  (inline)           │              │  [nomor].pdf        │
                          └──────────┬──────────┘              └──────────┬──────────┘
                                     │                                    │
                                     └────────────────┬───────────────────┘
                                                      │
                                                      ▼
                                           ┌─────────────────┐
                                           │     SELESAI     │
                                           └─────────────────┘
```

**D. Flowchart: Proses Verifikasi SKL via QR Code**

```
                              ┌─────────────────┐
                              │     MULAI       │
                              └────────┬────────┘
                                       │
                                       ▼
                              ┌─────────────────┐
                              │  Pihak Ke-3     │
                              │  Menerima SKL   │
                              │  Fisik/Digital  │
                              └────────┬────────┘
                                       │
                                       ▼
                              ┌─────────────────┐
                              │  Scan QR Code   │
                              │  pada SKL       │
                              │  (Kamera HP)    │
                              └────────┬────────┘
                                       │
                                       ▼
                              ┌─────────────────────┐
                              │  QR Code Terbaca?   │
                              └──────────┬──────────┘
                                         │
                             ┌───────────┴───────────┐
                             │                       │
                          Tidak                     Ya
                             │                       │
                             ▼                       ▼
              ┌─────────────────────┐    ┌─────────────────────┐
              │  Tampilkan Pesan   │    │  Browser Membuka    │
              │  "QR Code Rusak    │    │  URL Verifikasi     │
              │   atau Tidak       │    │  /verify/{uuid}     │
              │   Terbaca"         │    └──────────┬──────────┘
              └─────────┬──────────┘               │
                        │                          ▼
                        │              ┌─────────────────────┐
                        │              │  Server Menerima    │
                        │              │  Request            │
                        │              └──────────┬──────────┘
                        │                         │
                        │                         ▼
                        │              ┌─────────────────────┐
                        │              │  Query Database     │
                        │              │  WHERE uuid = ?     │
                        │              └──────────┬──────────┘
                        │                         │
                        │             ┌───────────┴───────────┐
                        │             │                       │
                        │        Tidak Ada                  Ada
                        │             │                       │
                        │             ▼                       ▼
                        │  ┌─────────────────────┐  ┌─────────────────────┐
                        │  │  Tampilkan Halaman  │  │  Load Data SKL      │
                        │  │  ERROR              │  │  + Birth Record     │
                        │  │  ─────────────────  │  │  + Doctor           │
                        │  │  ❌ TIDAK VALID     │  └──────────┬──────────┘
                        │  │                     │             │
                        │  │  "Dokumen tidak    │             ▼
                        │  │   ditemukan dalam  │  ┌─────────────────────┐
                        │  │   database kami"   │  │  Validasi Status    │
                        │  └─────────┬──────────┘  │  SKL                │
                        │            │             └──────────┬──────────┘
                        │            │                        │
                        │            │            ┌───────────┴───────────┐
                        │            │            │                       │
                        │            │        is_signed               is_signed
                        │            │        = false                 = true
                        │            │            │                       │
                        │            │            ▼                       ▼
                        │            │  ┌─────────────────────┐  ┌─────────────────────┐
                        │            │  │  Tampilkan Halaman  │  │  Tampilkan Halaman  │
                        │            │  │  PENDING            │  │  VALID              │
                        │            │  │  ─────────────────  │  │  ─────────────────  │
                        │            │  │  ⏳ MENUNGGU        │  │  ✅ SAH/VALID       │
                        │            │  │                     │  │                     │
                        │            │  │  "Dokumen belum    │  │  "Dokumen ini      │
                        │            │  │   ditandatangani"  │  │   SAH dan terdaftar│
                        │            │  └─────────┬──────────┘  │   dalam sistem"    │
                        │            │            │             └──────────┬──────────┘
                        │            │            │                        │
                        │            │            │                        ▼
                        │            │            │             ┌─────────────────────┐
                        │            │            │             │  TAMPILKAN DATA     │
                        │            │            │             │  KELAHIRAN:         │
                        │            │            │             │  ─────────────────  │
                        │            │            │             │  • Nama Bayi        │
                        │            │            │             │  • Tanggal Lahir    │
                        │            │            │             │  • Nama Orang Tua   │
                        │            │            │             │  • Nomor Dokumen    │
                        │            │            │             │  • Tanggal Terbit   │
                        │            │            │             │  • Nama RS          │
                        │            │            │             └──────────┬──────────┘
                        │            │            │                        │
                        │◀───────────┴────────────┴────────────────────────┘
                        │
                        ▼
              ┌─────────────────┐
              │     SELESAI     │
              └─────────────────┘
```

**E. Flowchart: Proses Manajemen User (Admin)**

```
                              ┌─────────────────┐
                              │     MULAI       │
                              └────────┬────────┘
                                       │
                                       ▼
                              ┌─────────────────┐
                              │  Admin Akses    │
                              │  Menu User      │
                              └────────┬────────┘
                                       │
                                       ▼
                    ┌──────────────────────────────────────┐
                    │           Cek Role User              │
                    │   (Super Admin / Admin only)         │
                    └──────────────────┬───────────────────┘
                                       │
                           ┌───────────┴───────────┐
                           │                       │
                    Bukan Admin                  Admin
                           │                       │
                           ▼                       ▼
              ┌─────────────────────┐    ┌─────────────────────┐
              │  Redirect ke        │    │  Tampilkan Daftar   │
              │  Dashboard          │    │  User (Tabel)       │
              └─────────────────────┘    └──────────┬──────────┘
                                                    │
                                ┌───────────────────┼───────────────────┐
                                │                   │                   │
                                ▼                   ▼                   ▼
                     ┌─────────────────┐  ┌─────────────────┐  ┌─────────────────┐
                     │  Tambah User    │  │   Edit User     │  │  Hapus User     │
                     │  Baru           │  │                 │  │                 │
                     └────────┬────────┘  └────────┬────────┘  └────────┬────────┘
                              │                    │                    │
                              ▼                    ▼                    ▼
                     ┌─────────────────┐  ┌─────────────────┐  ┌─────────────────┐
                     │  Form Input:    │  │  Load Data User │  │  Konfirmasi     │
                     │  • Nama         │  │  ke Form Edit   │  │  Penghapusan    │
                     │  • Email        │  └────────┬────────┘  └────────┬────────┘
                     │  • Password     │           │                    │
                     │  • Role         │           ▼                    │
                     └────────┬────────┘  ┌─────────────────┐           │
                              │           │  Update Data    │           │
                              ▼           │  User           │           │
                     ┌─────────────────┐  └────────┬────────┘           │
                     │  Validasi &     │           │                    │
                     │  Simpan User    │           │                    │
                     └────────┬────────┘           │                    ▼
                              │                    │          ┌─────────────────┐
                              │                    │          │  DELETE User    │
                              │                    │          │  dari Database  │
                              │                    │          └────────┬────────┘
                              │                    │                   │
                              ▼                    ▼                   ▼
                     ┌─────────────────────────────────────────────────────────┐
                     │                  Log Activity                            │
                     │  "created/updated/deleted user [email]"                  │
                     └─────────────────────────┬───────────────────────────────┘
                                               │
                                               ▼
                                    ┌─────────────────────┐
                                    │  Tampilkan Pesan    │
                                    │  Sukses             │
                                    └──────────┬──────────┘
                                               │
                                               ▼
                                    ┌─────────────────────┐
                                    │  Refresh Daftar     │
                                    │  User               │
                                    └──────────┬──────────┘
                                               │
                                               ▼
                                    ┌─────────────────┐
                                    │     SELESAI     │
                                    └─────────────────┘
```

---

### 3.7 Perancangan Antarmuka (UI/UX)

#### 3.7.1 Struktur Navigasi

```
DASHBOARD
├── Data Kelahiran
│   ├── Daftar Semua Data
│   ├── Tambah Data Baru
│   ├── Edit Data
│   ├── Lihat Detail
│   └── Cetak SKL
│
├── Data Dokter
│   ├── Daftar Dokter
│   ├── Tambah Dokter
│   └── Edit Dokter
│
├── Manajemen Pengguna (Admin only)
│   ├── Daftar Pengguna
│   ├── Tambah Pengguna
│   └── Edit Pengguna
│
├── Activity Log (Admin only)
│   └── Riwayat Aktivitas
│
└── Pengaturan
    ├── Profile
    └── Password
```

#### 3.7.2 Komponen UI Utama

1. **Dashboard**
   - Statistik kelahiran (hari ini, bulan ini, total)
   - Grafik distribusi jenis kelamin
   - Grafik metode persalinan
   - Data kelahiran terbaru

2. **Form Input Data Kelahiran**
   - Data Bayi (nama, tanggal lahir, jam, berat, panjang, dll)
   - Data Ibu (nama, NIK, alamat, pekerjaan, golongan darah)
   - Data Ayah (nama, NIK, alamat, pekerjaan, golongan darah)
   - Pilihan Dokter Penanggung Jawab

3. **Dokumen SKL (PDF)**
   - Header Rumah Sakit
   - Nomor Dokumen
   - Data Kelahiran Bilingual (Indonesia/Inggris)
   - Tanda Tangan Digital
   - QR Code Verifikasi

---

### 3.8 Metode Pengujian

#### 3.8.1 Black Box Testing

Pengujian dengan metode Black Box Testing fokus pada fungsionalitas sistem tanpa melihat kode program. Pengujian dilakukan berdasarkan:

**Tabel Skenario Pengujian:**

| No | Fungsi yang Diuji | Skenario Pengujian | Hasil yang Diharapkan |
|----|-------------------|--------------------|-----------------------|
| 1 | Login | Input email dan password valid | Berhasil masuk ke dashboard |
| 2 | Login | Input email atau password salah | Tampil pesan error |
| 3 | Input Data Kelahiran | Input semua field dengan data valid | Data tersimpan, nomor SKL tergenerate |
| 4 | Input Data Kelahiran | Kosongkan field wajib | Tampil pesan validasi error |
| 5 | Cetak SKL | Klik tombol cetak | PDF tergenerate dengan QR Code |
| 6 | Verifikasi QR | Scan QR Code pada SKL | Halaman verifikasi menampilkan data valid |
| 7 | Manajemen User | Tambah user baru | User berhasil ditambahkan |
| 8 | Manajemen Dokter | Edit data dokter | Data dokter terupdate |
| 9 | Activity Log | Akses halaman activity log | Menampilkan riwayat aktivitas |
| 10 | Hak Akses | Bidan akses halaman admin | Tampil pesan unauthorized |

#### 3.8.2 User Acceptance Test (UAT)

Pengujian langsung oleh pengguna akhir (bidan, admin RS) untuk memastikan sistem sesuai dengan kebutuhan operasional. Aspek yang diuji:

1. **Kemudahan Penggunaan** - Sistem mudah dipahami dan dioperasikan
2. **Kesesuaian Fitur** - Fitur sesuai dengan kebutuhan pengguna
3. **Kelengkapan Data** - Semua data yang diperlukan dapat diinput
4. **Kualitas Output** - Dokumen SKL sesuai standar
5. **Performa** - Sistem responsif dan cepat

#### 3.8.3 Performance Testing

Pengujian performa sistem meliputi:
- Waktu respon halaman
- Waktu generate PDF
- Kapasitas concurrent users

---

### 3.9 Keamanan Sistem

#### 3.9.1 Implementasi Keamanan

| Aspek | Implementasi |
|-------|--------------|
| **Autentikasi** | Laravel Fortify dengan hashing bcrypt |
| **Otorisasi** | Role-Based Access Control (Spatie Permission) |
| **CSRF Protection** | Token CSRF terintegrasi otomatis |
| **SQL Injection** | Eloquent ORM dengan prepared statements |
| **XSS Prevention** | Vue.js auto-escaping + Laravel Blade |
| **Enkripsi** | HTTPS + APP_KEY encryption |
| **Audit Trail** | Spatie Activitylog untuk logging aktivitas |

#### 3.9.2 Role dan Permission

| Role | Permissions |
|------|-------------|
| **Super Admin** | Full access (semua fitur) |
| **Admin** | Manajemen user, dokter, lihat activity log |
| **Bidan** | Input data kelahiran, cetak SKL |

---

### 3.10 Lingkungan Implementasi

#### 3.10.1 Kebutuhan Hardware (Server)

| Komponen | Spesifikasi Minimum |
|----------|---------------------|
| Processor | Dual Core 2.0 GHz |
| RAM | 4 GB |
| Storage | 50 GB SSD |
| Network | 100 Mbps |

#### 3.10.2 Kebutuhan Hardware (Client)

| Komponen | Spesifikasi Minimum |
|----------|---------------------|
| Browser | Chrome 90+, Firefox 88+, Edge 90+ |
| Resolution | 1366 x 768 pixel |
| Network | 10 Mbps |

#### 3.10.3 Kebutuhan Software (Server)

| Software | Versi |
|----------|-------|
| Operating System | Ubuntu 22.04 LTS / Windows Server 2019 |
| Web Server | Nginx / Apache |
| PHP | 8.2 atau lebih tinggi |
| Database | MySQL 8.0 / MariaDB 10.6 |
| Node.js | 18.x LTS |

---

### 3.11 Jadwal Penelitian

| No | Kegiatan | Minggu 1-2 | Minggu 3-4 | Minggu 5-6 | Minggu 7-8 | Minggu 9-10 | Minggu 11-12 |
|----|----------|:----------:|:----------:|:----------:|:----------:|:-----------:|:------------:|
| 1 | Pengumpulan Data | ████████ | | | | | |
| 2 | Analisis Kebutuhan | ████████ | ████████ | | | | |
| 3 | Desain Sistem | | ████████ | ████████ | | | |
| 4 | Implementasi | | | ████████ | ████████ | ████████ | |
| 5 | Pengujian | | | | | ████████ | ████████ |
| 6 | Dokumentasi | | | | | | ████████ |

---

## REFERENSI YANG DISARANKAN

### Buku:
1. Pressman, R. S., & Maxim, B. R. (2020). *Software Engineering: A Practitioner's Approach*. McGraw-Hill.
2. Sommerville, I. (2016). *Software Engineering*. Pearson.
3. Fowler, M. (2018). *Refactoring: Improving the Design of Existing Code*. Addison-Wesley.

### Jurnal:
1. Referensi jurnal tentang sistem informasi rumah sakit
2. Referensi jurnal tentang pengembangan aplikasi web berbasis Laravel
3. Referensi jurnal tentang implementasi QR Code untuk verifikasi dokumen

### Dokumentasi Resmi:
1. Laravel Documentation - https://laravel.com/docs
2. Vue.js Documentation - https://vuejs.org/guide
3. Inertia.js Documentation - https://inertiajs.com
4. Tailwind CSS Documentation - https://tailwindcss.com/docs

---

*Dokumen ini dibuat sebagai panduan metodologi penelitian untuk skripsi pengembangan Sistem Informasi Surat Keterangan Lahir (SKL) berbasis web.*
