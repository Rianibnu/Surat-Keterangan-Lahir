# DATA FLOW DIAGRAM (DFD)
## Sistem Informasi Surat Keterangan Lahir (SKL) Berbasis Web

---

## 1. Context Diagram (DFD Level 0)

Context Diagram menunjukkan gambaran umum sistem dan interaksinya dengan entitas eksternal.

```
                                    ┌─────────────────────────────────┐
                                    │                                 │
                                    │           SUPER ADMIN           │
                                    │                                 │
                                    └─────────────┬───────────────────┘
                                                  │
                              Data Konfigurasi    │    Laporan Aktivitas Sistem
                              Sistem, Manajemen   │    Audit Trail
                              Role & Permission   │
                                                  │
                                                  ▼
┌─────────────────────────┐               ┌───────────────────────────────────────┐              ┌─────────────────────────┐
│                         │               │                                       │              │                         │
│         BIDAN           │               │                                       │              │         ADMIN           │
│                         │               │                                       │              │                         │
└───────────┬─────────────┘               │                                       │              └───────────┬─────────────┘
            │                             │                                       │                          │
            │ Data Kelahiran              │        SISTEM INFORMASI               │    Data Pengguna,       │
            │ (Bayi, Ibu, Ayah)           │                                       │    Data Dokter          │
            │                             │        SURAT KETERANGAN               │                          │
            ├────────────────────────────▶│                                       │◀─────────────────────────┤
            │                             │              LAHIR (SKL)              │                          │
            │ Dokumen SKL (PDF),          │                                       │    Laporan, Dashboard,   │
            │ Status Simpan Data          │                                       │    Activity Log          │
            │◀────────────────────────────│                                       │─────────────────────────▶│
            │                             │                                       │                          │
            │                             └───────────────────┬───────────────────┘                          │
            │                                                 │                                              │
            │                                                 │                                              │
            │                                                 │                                              │
            │                                                 │                                              │
            │                                                 ▼                                              │
            │                             ┌───────────────────────────────────────┐                          │
            │                             │                                       │                          │
            │                             │           PUBLIK                      │                          │
            │                             │      (PIHAK KETIGA)                   │                          │
            │                             │                                       │                          │
            │                             └───────────────────────────────────────┘                          │
            │                                                 │                                              │
            │                                                 │                                              │
            │                                  UUID/QR Code   │    Hasil Verifikasi                          │
            │                                  Verifikasi     │    (Valid/Invalid + Data SKL)                │
            │                                                 │                                              │
            └─────────────────────────────────────────────────┴──────────────────────────────────────────────┘
```

### Keterangan Entitas Eksternal:

| Entitas | Deskripsi |
|---------|-----------|
| **Bidan** | Petugas medis yang bertugas menginput data kelahiran dan mencetak dokumen SKL |
| **Admin** | Administrator sistem yang mengelola pengguna, dokter, dan memantau aktivitas |
| **Super Admin** | Administrator dengan akses penuh untuk konfigurasi sistem dan manajemen role |
| **Publik (Pihak Ketiga)** | Pihak eksternal yang memverifikasi keaslian dokumen SKL melalui QR Code |

---

## 2. DFD Level 1

DFD Level 1 menunjukkan dekomposisi sistem menjadi proses-proses utama.

```
┌───────────────────────────────────────────────────────────────────────────────────────────────────────────────┐
│                                          SISTEM INFORMASI SKL                                                  │
│                                                                                                               │
│  ┌─────────────┐                                                                                              │
│  │             │                                                                                              │
│  │   BIDAN     │                                                                                              │
│  │             │                                                                                              │
│  └──────┬──────┘                                                                                              │
│         │                                                                                                     │
│         │ Email, Password                                                                                     │
│         ├──────────────────────────────────────────▶┌─────────────────────────────┐                          │
│         │                                           │                             │                          │
│         │                                           │     1.0                     │                          │
│         │                                           │   AUTENTIKASI               │                          │
│         │◀─────────────────────────────────────────│     LOGIN                   │                          │
│         │ Session Token, Pesan Error               │                             │                          │
│         │                                           └──────────────┬──────────────┘                          │
│         │                                                          │                                          │
│         │                                                          │ Query User                               │
│         │                                                          ▼                                          │
│         │                                           ┌──────────────────────────────┐                          │
│         │                                           │      D1  USERS               │                          │
│         │                                           └──────────────────────────────┘                          │
│         │                                                                                                     │
│         │ Data Kelahiran (Bayi, Ibu, Ayah, Dokter)                                                            │
│         ├──────────────────────────────────────────▶┌─────────────────────────────┐                          │
│         │                                           │                             │                          │
│         │                                           │     2.0                     │───────▶ D2 BIRTH_RECORDS │
│         │                                           │   MANAJEMEN                 │                          │
│         │◀─────────────────────────────────────────│   DATA KELAHIRAN            │◀─────── D3 DOCTORS       │
│         │ Status Simpan, Pesan Error               │                             │                          │
│         │                                           └──────────────┬──────────────┘                          │
│         │                                                          │                                          │
│         │                                                          │ Generate SKL                             │
│         │                                                          ▼                                          │
│         │                                           ┌──────────────────────────────┐                          │
│         │                                           │      D4  SKLS                │                          │
│         │                                           └──────────────────────────────┘                          │
│         │                                                                                                     │
│         │ Request Cetak SKL                                                                                   │
│         ├──────────────────────────────────────────▶┌─────────────────────────────┐                          │
│         │                                           │                             │                          │
│         │                                           │     3.0                     │◀─────── D4 SKLS          │
│         │                                           │   GENERATE                  │                          │
│         │◀─────────────────────────────────────────│   DOKUMEN SKL               │◀─────── D2 BIRTH_RECORDS │
│         │ Dokumen PDF + QR Code                    │                             │                          │
│         │                                           └─────────────────────────────┘                          │
│                                                                                                               │
└───────────────────────────────────────────────────────────────────────────────────────────────────────────────┘

┌───────────────────────────────────────────────────────────────────────────────────────────────────────────────┐
│                                                                                                               │
│  ┌─────────────┐                                                                                              │
│  │             │                                                                                              │
│  │   ADMIN     │                                                                                              │
│  │             │                                                                                              │
│  └──────┬──────┘                                                                                              │
│         │                                                                                                     │
│         │ Data Pengguna Baru                                                                                  │
│         ├──────────────────────────────────────────▶┌─────────────────────────────┐                          │
│         │                                           │                             │                          │
│         │                                           │     4.0                     │───────▶ D1 USERS         │
│         │                                           │   MANAJEMEN                 │                          │
│         │◀─────────────────────────────────────────│   PENGGUNA                  │◀─────── D5 ROLES         │
│         │ Status CRUD, Daftar Pengguna             │                             │                          │
│         │                                           └─────────────────────────────┘                          │
│         │                                                                                                     │
│         │ Data Dokter                                                                                         │
│         ├──────────────────────────────────────────▶┌─────────────────────────────┐                          │
│         │                                           │                             │                          │
│         │                                           │     5.0                     │───────▶ D3 DOCTORS       │
│         │                                           │   MANAJEMEN                 │                          │
│         │◀─────────────────────────────────────────│   DOKTER                    │                          │
│         │ Status CRUD, Daftar Dokter               │                             │                          │
│         │                                           └─────────────────────────────┘                          │
│         │                                                                                                     │
│         │ Request Laporan/Dashboard                                                                           │
│         ├──────────────────────────────────────────▶┌─────────────────────────────┐                          │
│         │                                           │                             │                          │
│         │                                           │     6.0                     │◀─────── D2 BIRTH_RECORDS │
│         │                                           │   DASHBOARD &               │                          │
│         │◀─────────────────────────────────────────│   LAPORAN                   │◀─────── D4 SKLS          │
│         │ Data Dashboard, Statistik                │                             │                          │
│         │                                           └─────────────────────────────┘                          │
│         │                                                                                                     │
│         │ Request Activity Log                                                                                │
│         ├──────────────────────────────────────────▶┌─────────────────────────────┐                          │
│         │                                           │                             │                          │
│         │                                           │     7.0                     │◀─────── D6 ACTIVITY_LOG  │
│         │                                           │   AUDIT TRAIL               │                          │
│         │◀─────────────────────────────────────────│   (ACTIVITY LOG)            │                          │
│         │ Daftar Aktivitas Sistem                  │                             │                          │
│         │                                           └─────────────────────────────┘                          │
│                                                                                                               │
└───────────────────────────────────────────────────────────────────────────────────────────────────────────────┘

┌───────────────────────────────────────────────────────────────────────────────────────────────────────────────┐
│                                                                                                               │
│  ┌─────────────┐                                                                                              │
│  │   PUBLIK    │                                                                                              │
│  │ (Pihak Ke-3)│                                                                                              │
│  └──────┬──────┘                                                                                              │
│         │                                                                                                     │
│         │ UUID / QR Code                                                                                      │
│         ├──────────────────────────────────────────▶┌─────────────────────────────┐                          │
│         │                                           │                             │                          │
│         │                                           │     8.0                     │◀─────── D4 SKLS          │
│         │                                           │   VERIFIKASI                │                          │
│         │◀─────────────────────────────────────────│   DOKUMEN SKL               │◀─────── D2 BIRTH_RECORDS │
│         │ Status Valid/Invalid + Data SKL          │                             │                          │
│         │                                           └─────────────────────────────┘                          │
│                                                                                                               │
└───────────────────────────────────────────────────────────────────────────────────────────────────────────────┘
```

### Keterangan Proses DFD Level 1:

| No. Proses | Nama Proses | Deskripsi |
|------------|-------------|-----------|
| **1.0** | Autentikasi Login | Proses verifikasi identitas pengguna melalui email dan password |
| **2.0** | Manajemen Data Kelahiran | Proses CRUD (Create, Read, Update, Delete) data kelahiran bayi |
| **3.0** | Generate Dokumen SKL | Proses pembuatan dokumen PDF SKL dengan QR Code verifikasi |
| **4.0** | Manajemen Pengguna | Proses pengelolaan data pengguna sistem dan assignment role |
| **5.0** | Manajemen Dokter | Proses pengelolaan data dokter penanggung jawab |
| **6.0** | Dashboard & Laporan | Proses menampilkan statistik dan ringkasan data sistem |
| **7.0** | Audit Trail (Activity Log) | Proses pencatatan dan pemantauan aktivitas pengguna |
| **8.0** | Verifikasi Dokumen SKL | Proses verifikasi keaslian dokumen SKL melalui QR Code |

### Keterangan Data Store:

| Kode | Nama Data Store | Deskripsi |
|------|-----------------|-----------|
| **D1** | USERS | Menyimpan data pengguna sistem (bidan, admin, super admin) |
| **D2** | BIRTH_RECORDS | Menyimpan data kelahiran (bayi, ibu, ayah) |
| **D3** | DOCTORS | Menyimpan data dokter penanggung jawab |
| **D4** | SKLS | Menyimpan data Surat Keterangan Lahir |
| **D5** | ROLES | Menyimpan data role dan permission |
| **D6** | ACTIVITY_LOG | Menyimpan catatan aktivitas pengguna (audit trail) |

---

## 3. DFD Level 2

### 3.1 DFD Level 2 - Proses 1.0 Autentikasi Login

```
┌───────────────────────────────────────────────────────────────────────────────────────┐
│                           1.0 AUTENTIKASI LOGIN                                        │
│                                                                                       │
│  ┌─────────────┐                                                                      │
│  │             │                                                                      │
│  │   USER      │                                                                      │
│  │             │                                                                      │
│  └──────┬──────┘                                                                      │
│         │                                                                             │
│         │ Email, Password                                                             │
│         ├──────────────────────────────────────────▶┌─────────────────────────────┐  │
│         │                                           │                             │  │
│         │                                           │    1.1                      │  │
│         │◀─────────────────────────────────────────│  VALIDASI                   │  │
│         │ Pesan Error Format                       │  FORMAT INPUT               │  │
│         │                                           │                             │  │
│         │                                           └──────────────┬──────────────┘  │
│         │                                                          │                  │
│         │                                                          │ Email, Password  │
│         │                                                          │ (validated)      │
│         │                                                          ▼                  │
│         │                                           ┌─────────────────────────────┐  │
│         │                                           │                             │  │
│         │                                           │    1.2                      │◀───────▶ D1 USERS
│         │◀─────────────────────────────────────────│  VERIFIKASI                 │  │
│         │ Pesan User Tidak Ditemukan               │  KREDENSIAL                 │  │
│         │                                           │                             │  │
│         │                                           └──────────────┬──────────────┘  │
│         │                                                          │                  │
│         │                                                          │ User Data        │
│         │                                                          ▼                  │
│         │                                           ┌─────────────────────────────┐  │
│         │                                           │                             │  │
│         │                                           │    1.3                      │  │
│         │◀─────────────────────────────────────────│  VERIFIKASI                 │  │
│         │ Pesan Password Salah                     │  PASSWORD                   │  │
│         │                                           │  (bcrypt)                   │  │
│         │                                           │                             │  │
│         │                                           └──────────────┬──────────────┘  │
│         │                                                          │                  │
│         │                                                          │ Valid Credentials│
│         │                                                          ▼                  │
│         │                                           ┌─────────────────────────────┐  │
│         │                                           │                             │  │
│         │                                           │    1.4                      │─────────▶ D6 ACTIVITY_LOG
│         │◀─────────────────────────────────────────│  BUAT SESSION               │  │
│         │ Session Token + Redirect                 │  & LOG AKTIVITAS            │  │
│         │                                           │                             │  │
│         │                                           └─────────────────────────────┘  │
│                                                                                       │
└───────────────────────────────────────────────────────────────────────────────────────┘
```

### 3.2 DFD Level 2 - Proses 2.0 Manajemen Data Kelahiran

```
┌───────────────────────────────────────────────────────────────────────────────────────┐
│                           2.0 MANAJEMEN DATA KELAHIRAN                                 │
│                                                                                       │
│  ┌─────────────┐                                                                      │
│  │             │                                                                      │
│  │   BIDAN     │                                                                      │
│  │             │                                                                      │
│  └──────┬──────┘                                                                      │
│         │                                                                             │
│         │ Request Tambah Data                                                         │
│         ├──────────────────────────────────────────▶┌─────────────────────────────┐  │
│         │                                           │                             │  │
│         │                                           │    2.1                      │◀───────▶ D5 ROLES
│         │◀─────────────────────────────────────────│  CEK HAK AKSES              │  │
│         │ Status Akses (Diizinkan/Ditolak)         │  (AUTHORIZATION)            │  │
│         │                                           │                             │  │
│         │                                           └──────────────┬──────────────┘  │
│         │                                                          │                  │
│         │                                                          │ Access Granted   │
│         │                                                          ▼                  │
│         │                                           ┌─────────────────────────────┐  │
│         │ Daftar Dokter                             │                             │  │
│         │◀──────────────────────────────────────────│    2.2                      │◀───────▶ D3 DOCTORS
│         │                                           │  LOAD DATA                  │  │
│         │                                           │  PENDUKUNG                  │  │
│         │                                           │                             │  │
│         │                                           └──────────────┬──────────────┘  │
│         │                                                          │                  │
│         │                                                          │ Form Ready       │
│         │ Data Kelahiran Lengkap                                   ▼                  │
│         │ (Bayi, Ibu, Ayah, Dokter)               ┌─────────────────────────────┐  │
│         ├──────────────────────────────────────────▶│                             │  │
│         │                                           │    2.3                      │  │
│         │◀─────────────────────────────────────────│  VALIDASI                   │  │
│         │ Pesan Error Validasi                     │  INPUT DATA                 │  │
│         │                                           │                             │  │
│         │                                           └──────────────┬──────────────┘  │
│         │                                                          │                  │
│         │                                                          │ Data Valid       │
│         │                                                          ▼                  │
│         │                                           ┌─────────────────────────────┐  │
│         │                                           │                             │  │
│         │                                           │    2.4                      │─────────▶ D2 BIRTH_RECORDS
│         │                                           │  SIMPAN DATA                │  │
│         │                                           │  KELAHIRAN                  │  │
│         │                                           │                             │  │
│         │                                           └──────────────┬──────────────┘  │
│         │                                                          │                  │
│         │                                                          │ Birth Record ID  │
│         │                                                          ▼                  │
│         │                                           ┌─────────────────────────────┐  │
│         │                                           │                             │  │
│         │                                           │    2.5                      │─────────▶ D4 SKLS
│         │                                           │  GENERATE                   │  │
│         │                                           │  NOMOR SKL & UUID           │  │
│         │                                           │                             │  │
│         │                                           └──────────────┬──────────────┘  │
│         │                                                          │                  │
│         │                                                          │ SKL Created      │
│         │                                                          ▼                  │
│         │                                           ┌─────────────────────────────┐  │
│         │                                           │                             │  │
│         │◀──────────────────────────────────────────│    2.6                      │─────────▶ D6 ACTIVITY_LOG
│         │ Status Sukses + Redirect                 │  LOG AKTIVITAS              │  │
│         │                                           │  & RESPONSE                 │  │
│         │                                           │                             │  │
│         │                                           └─────────────────────────────┘  │
│                                                                                       │
└───────────────────────────────────────────────────────────────────────────────────────┘
```

### 3.3 DFD Level 2 - Proses 3.0 Generate Dokumen SKL

```
┌───────────────────────────────────────────────────────────────────────────────────────┐
│                           3.0 GENERATE DOKUMEN SKL                                     │
│                                                                                       │
│  ┌─────────────┐                                                                      │
│  │             │                                                                      │
│  │   BIDAN     │                                                                      │
│  │             │                                                                      │
│  └──────┬──────┘                                                                      │
│         │                                                                             │
│         │ Request Cetak (SKL ID)                                                      │
│         ├──────────────────────────────────────────▶┌─────────────────────────────┐  │
│         │                                           │                             │  │
│         │                                           │    3.1                      │◀───────▶ D4 SKLS
│         │                                           │  AMBIL DATA SKL             │  │
│         │                                           │  & BIRTH RECORD             │◀───────▶ D2 BIRTH_RECORDS
│         │                                           │                             │  │
│         │                                           └──────────────┬──────────────┘  │
│         │                                                          │                  │
│         │                                                          │ Data SKL +       │
│         │                                                          │ Birth Record     │
│         │                                                          ▼                  │
│         │                                           ┌─────────────────────────────┐  │
│         │                                           │                             │  │
│         │                                           │    3.2                      │◀───────▶ D3 DOCTORS
│         │                                           │  AMBIL DATA DOKTER          │  │
│         │                                           │  & TANDA TANGAN             │  │
│         │                                           │                             │  │
│         │                                           └──────────────┬──────────────┘  │
│         │                                                          │                  │
│         │                                                          │ Complete Data    │
│         │                                                          ▼                  │
│         │                                           ┌─────────────────────────────┐  │
│         │                                           │                             │  │
│         │                                           │    3.3                      │  │
│         │                                           │  GENERATE                   │  │
│         │                                           │  QR CODE                    │  │
│         │                                           │  (Verification URL)         │  │
│         │                                           │                             │  │
│         │                                           └──────────────┬──────────────┘  │
│         │                                                          │                  │
│         │                                                          │ QR Code Image    │
│         │                                                          ▼                  │
│         │                                           ┌─────────────────────────────┐  │
│         │                                           │                             │  │
│         │                                           │    3.4                      │  │
│         │                                           │  RENDER PDF                 │  │
│         │                                           │  TEMPLATE                   │  │
│         │                                           │  (DomPDF)                   │  │
│         │                                           │                             │  │
│         │                                           └──────────────┬──────────────┘  │
│         │                                                          │                  │
│         │                                                          │ PDF Binary       │
│         │                                                          ▼                  │
│         │                                           ┌─────────────────────────────┐  │
│         │                                           │                             │  │
│         │◀──────────────────────────────────────────│    3.5                      │─────────▶ D6 ACTIVITY_LOG
│         │ Dokumen PDF SKL                          │  LOG AKTIVITAS              │  │
│         │                                           │  & KIRIM PDF                │  │
│         │                                           │                             │  │
│         │                                           └─────────────────────────────┘  │
│                                                                                       │
└───────────────────────────────────────────────────────────────────────────────────────┘
```

### 3.4 DFD Level 2 - Proses 8.0 Verifikasi Dokumen SKL

```
┌───────────────────────────────────────────────────────────────────────────────────────┐
│                           8.0 VERIFIKASI DOKUMEN SKL                                   │
│                                                                                       │
│  ┌─────────────┐                                                                      │
│  │   PUBLIK    │                                                                      │
│  │(Pihak Ke-3) │                                                                      │
│  └──────┬──────┘                                                                      │
│         │                                                                             │
│         │ UUID (dari QR Code / URL)                                                   │
│         ├──────────────────────────────────────────▶┌─────────────────────────────┐  │
│         │                                           │                             │  │
│         │                                           │    8.1                      │  │
│         │◀─────────────────────────────────────────│  VALIDASI                   │  │
│         │ Pesan Error Format UUID                  │  FORMAT UUID                │  │
│         │                                           │                             │  │
│         │                                           └──────────────┬──────────────┘  │
│         │                                                          │                  │
│         │                                                          │ UUID Valid Format│
│         │                                                          ▼                  │
│         │                                           ┌─────────────────────────────┐  │
│         │                                           │                             │  │
│         │                                           │    8.2                      │◀───────▶ D4 SKLS
│         │◀─────────────────────────────────────────│  CARI DATA SKL              │  │
│         │ Pesan SKL Tidak Ditemukan                │  BY UUID                    │  │
│         │                                           │                             │  │
│         │                                           └──────────────┬──────────────┘  │
│         │                                                          │                  │
│         │                                                          │ SKL Data Found   │
│         │                                                          ▼                  │
│         │                                           ┌─────────────────────────────┐  │
│         │                                           │                             │  │
│         │                                           │    8.3                      │◀───────▶ D2 BIRTH_RECORDS
│         │                                           │  AMBIL DATA                 │  │
│         │                                           │  KELAHIRAN TERKAIT          │◀───────▶ D3 DOCTORS
│         │                                           │                             │  │
│         │                                           └──────────────┬──────────────┘  │
│         │                                                          │                  │
│         │                                                          │ Complete Data    │
│         │                                                          ▼                  │
│         │                                           ┌─────────────────────────────┐  │
│         │                                           │                             │  │
│         │                                           │    8.4                      │  │
│         │                                           │  VALIDASI                   │  │
│         │◀─────────────────────────────────────────│  KEASLIAN                   │  │
│         │ Status Invalid (Dokumen Rusak)           │  DOKUMEN                    │  │
│         │                                           │                             │  │
│         │                                           └──────────────┬──────────────┘  │
│         │                                                          │                  │
│         │                                                          │ Valid Document   │
│         │                                                          ▼                  │
│         │                                           ┌─────────────────────────────┐  │
│         │                                           │                             │  │
│         │◀──────────────────────────────────────────│    8.5                      │  │
│         │ Status Valid + Data SKL                  │  TAMPILKAN                  │  │
│         │ (Nama Bayi, Tanggal Lahir,               │  HASIL                      │  │
│         │  Nomor SKL, Tanggal Terbit)              │  VERIFIKASI                 │  │
│         │                                           │                             │  │
│         │                                           └─────────────────────────────┘  │
│                                                                                       │
└───────────────────────────────────────────────────────────────────────────────────────┘
```

---

## 4. Ringkasan Aliran Data (Data Flow Summary)

### 4.1 Aliran Data Masuk (Input)

| No | Data | Sumber | Tujuan Proses | Deskripsi |
|----|------|--------|---------------|-----------|
| 1 | Email & Password | User | 1.0 Autentikasi | Kredensial untuk login sistem |
| 2 | Data Kelahiran | Bidan | 2.0 Manajemen Data | Data bayi, ibu, ayah lengkap |
| 3 | Request Cetak | Bidan | 3.0 Generate SKL | Permintaan cetak dokumen PDF |
| 4 | Data Pengguna | Admin | 4.0 Manajemen User | Data user baru/update |
| 5 | Data Dokter | Admin | 5.0 Manajemen Dokter | Data dokter baru/update |
| 6 | UUID/QR Code | Publik | 8.0 Verifikasi | Kode unik untuk verifikasi |

### 4.2 Aliran Data Keluar (Output)

| No | Data | Sumber Proses | Tujuan | Deskripsi |
|----|------|---------------|--------|-----------|
| 1 | Session Token | 1.0 Autentikasi | User | Token akses setelah login |
| 2 | Status Simpan | 2.0 Manajemen Data | Bidan | Konfirmasi data tersimpan |
| 3 | Dokumen PDF | 3.0 Generate SKL | Bidan | File PDF Surat Keterangan Lahir |
| 4 | Daftar User | 4.0 Manajemen User | Admin | List pengguna sistem |
| 5 | Daftar Dokter | 5.0 Manajemen Dokter | Admin | List dokter terdaftar |
| 6 | Data Dashboard | 6.0 Dashboard | Admin | Statistik dan ringkasan |
| 7 | Activity Log | 7.0 Audit Trail | Admin | Catatan aktivitas |
| 8 | Hasil Verifikasi | 8.0 Verifikasi | Publik | Status valid/invalid + data |

### 4.3 Aliran Data Internal (Antar Data Store)

| No | Data Store Asal | Data Store Tujuan | Data | Deskripsi |
|----|-----------------|-------------------|------|-----------|
| 1 | D2 BIRTH_RECORDS | D4 SKLS | birth_record_id | Relasi data kelahiran ke SKL |
| 2 | D3 DOCTORS | D2 BIRTH_RECORDS | doctor_id | Relasi dokter ke data kelahiran |
| 3 | D1 USERS | D2 BIRTH_RECORDS | created_by_user_id | Relasi user pembuat data |
| 4 | D5 ROLES | D1 USERS | role_id | Assignment role ke user |

---

## 5. Kamus Data (Data Dictionary)

### 5.1 Data Store: D1 USERS

| Field | Tipe | Deskripsi |
|-------|------|-----------|
| id | Integer (PK) | Primary key |
| name | String(255) | Nama lengkap pengguna |
| email | String(255) | Email unik untuk login |
| password | String(255) | Password terenkripsi (bcrypt) |
| email_verified_at | Timestamp | Waktu verifikasi email |
| remember_token | String(100) | Token untuk remember me |
| created_at | Timestamp | Waktu pembuatan |
| updated_at | Timestamp | Waktu update terakhir |

### 5.2 Data Store: D2 BIRTH_RECORDS

| Field | Tipe | Deskripsi |
|-------|------|-----------|
| id | Integer (PK) | Primary key |
| baby_name | String(255) | Nama bayi |
| medical_record_no | String(50) | Nomor rekam medis bayi |
| birth_date | Date | Tanggal lahir |
| birth_time | Time | Jam lahir |
| gender | Enum | Jenis kelamin (L/P) |
| child_order | Integer | Anak ke- |
| delivery_method | Enum | Cara persalinan |
| blood_type | Enum | Golongan darah bayi |
| weight | Decimal | Berat badan (gram) |
| length | Decimal | Panjang badan (cm) |
| head_circumference | Decimal | Lingkar kepala (cm) |
| chest_circumference | Decimal | Lingkar dada (cm) |
| hospital_name | String(255) | Nama rumah sakit |
| mother_name | String(255) | Nama ibu |
| mother_medical_record_no | String(50) | No. RM ibu |
| mother_id_number | String(16) | NIK ibu |
| mother_address | Text | Alamat ibu |
| mother_occupation | String(100) | Pekerjaan ibu |
| mother_blood_type | Enum | Golongan darah ibu |
| father_name | String(255) | Nama ayah |
| father_id_number | String(16) | NIK ayah |
| father_address | Text | Alamat ayah |
| father_occupation | String(100) | Pekerjaan ayah |
| father_blood_type | Enum | Golongan darah ayah |
| doctor_id | Integer (FK) | Foreign key ke doctors |
| created_by_user_id | Integer (FK) | Foreign key ke users |
| created_at | Timestamp | Waktu pembuatan |
| updated_at | Timestamp | Waktu update terakhir |

### 5.3 Data Store: D3 DOCTORS

| Field | Tipe | Deskripsi |
|-------|------|-----------|
| id | Integer (PK) | Primary key |
| name | String(255) | Nama dokter |
| license_no | String(50) | Nomor STR/SIP |
| hospital | String(255) | Rumah sakit tempat praktek |
| signature_path | String(255) | Path file tanda tangan |
| created_at | Timestamp | Waktu pembuatan |
| updated_at | Timestamp | Waktu update terakhir |

### 5.4 Data Store: D4 SKLS

| Field | Tipe | Deskripsi |
|-------|------|-----------|
| id | Integer (PK) | Primary key |
| birth_record_id | Integer (FK) | Foreign key ke birth_records |
| uuid | String (Unique) | UUID untuk verifikasi |
| document_number | String(50) | Nomor dokumen SKL |
| issue_date | Date | Tanggal terbit |
| signer_name | String(255) | Nama penandatangan |
| signer_role | String(100) | Jabatan penandatangan |
| is_signed | Boolean | Status ditandatangani |
| created_at | Timestamp | Waktu pembuatan |
| updated_at | Timestamp | Waktu update terakhir |

### 5.5 Data Store: D5 ROLES

| Field | Tipe | Deskripsi |
|-------|------|-----------|
| id | Integer (PK) | Primary key |
| name | String(100) | Nama role |
| guard_name | String(100) | Guard authentication |
| created_at | Timestamp | Waktu pembuatan |
| updated_at | Timestamp | Waktu update terakhir |

### 5.6 Data Store: D6 ACTIVITY_LOG

| Field | Tipe | Deskripsi |
|-------|------|-----------|
| id | Integer (PK) | Primary key |
| log_name | String(100) | Nama log |
| description | Text | Deskripsi aktivitas |
| subject_type | String(255) | Tipe model subject |
| subject_id | Integer | ID subject |
| causer_type | String(255) | Tipe model causer (user) |
| causer_id | Integer | ID causer (user) |
| properties | JSON | Data perubahan |
| created_at | Timestamp | Waktu aktivitas |
| updated_at | Timestamp | Waktu update |

---

## 6. Notasi DFD yang Digunakan

```
┌─────────────────────────────────────────────────────────────────────────────────────┐
│                              NOTASI DFD (GANE-SARSON)                                │
└─────────────────────────────────────────────────────────────────────────────────────┘

1. PROSES (Process)
   ┌─────────────────────┐
   │      1.0            │     Kotak dengan sudut bulat
   │    NAMA PROSES      │     berisi nomor dan nama proses
   └─────────────────────┘

2. ENTITAS EKSTERNAL (External Entity)
   ┌─────────────────────┐
   │                     │     Kotak persegi panjang
   │      ENTITAS        │     berisi nama entitas
   │                     │
   └─────────────────────┘

3. DATA STORE
   ┌──────────────────────────────┐
   │      D1  NAMA_TABEL          │     Kotak terbuka di sisi kiri
   └──────────────────────────────┘     dengan kode dan nama tabel

4. ALIRAN DATA (Data Flow)
   ─────────────────────────────▶     Panah dengan label
   Nama Data                          menunjukkan arah aliran

5. ALIRAN DATAR (Bidirectional)
   ◀────────────────────────────▶     Panah dua arah untuk
   Query/Response                     baca dan tulis data
```

---

*Dokumen ini dibuat sebagai bagian dari metodologi penelitian untuk Sistem Informasi Surat Keterangan Lahir (SKL) Berbasis Web pada Rumah Sakit.*
