# VueDesk - Sistem Informasi Surat Keterangan Lahir (SKL)

VueDesk adalah aplikasi web modern untuk manajemen data kelahiran dan penerbitan Surat Keterangan Lahir (SKL) digital di rumah sakit. Aplikasi ini dilengkapi dengan validasi QR Code, fitur cetak PDF bilingual (Indonesia/Inggris), dan manajemen hak akses pengguna (RBAC).

## 🚀 Fitur Utama

-   **Dashboard Interaktif**: Statistik kelahiran, dokumen terbit, dan performa tim medis secara real-time.
-   **Manajemen Data Kelahiran**: Input data bayi, ibu, ayah, dan detail medis kelahiran.
-   **Cetak SKL Otomatis**: Generate dokumen SKL dalam format PDF (A4) yang rapi, bilingual, dan siap cetak.
-   **Verifikasi Digital (QR Code)**: Setiap SKL memiliki QR Code unik yang dapat dipindai oleh publik untuk memverifikasi keaslian dokumen tanpa perlu login.
-   **Manajemen Pengguna & Peran (RBAC)**:
    -   **Super Admin**: Akses penuh ke seluruh sistem.
    -   **Admin**: Manajemen master data dan user.
    -   **Bidan**: Input data kelahiran dan cetak surat.
-   **Audit Trail / Activity Log**: Mencatat setiap aktivitas pengguna (login, tambah, edit, hapus) untuk keamanan dan akuntabilitas.
-   **Data Master**: Manajemen data dokter/tenaga medis dan pengguna sistem.

## 🛠️ Teknologi yang Digunakan

### Backend
-   **Laravel 11**: Framework PHP modern dan robust.
-   **Spatie Laravel Permission**: Manajemen Role & Permission.
-   **Spatie Activitylog**: Pencatatan riwayat aktivitas sistem.
-   **Bacon QR Code**: Generator QR Code server-side.
-   **DOMPDF**: Generator PDF berkualitas tinggi.

### Frontend
-   **Vue.js 3** (Composition API): Framework JavaScript reaktif.
-   **Inertia.js**: Penghubung monolitik modern antara Laravel & Vue.
-   **Tailwind CSS**: Utility-first CSS framework.
-   **Shadcn-vue**: Komponen UI yang indah dan dapat dikustomisasi.
-   **Lucide Icons**: Ikon vektor yang ringan dan bersih.
-   **Font**: Poppins (Google Fonts).

## ⚙️ Persyaratan Sistem

-   PHP >= 8.2
-   Composer
-   Node.js & NPM
-   Database (MySQL/MariaDB/PostgreSQL)

## 📦 Cara Instalasi

1.  **Clone Repository**
    ```bash
    git clone https://github.com/username/vuedesk-hospital.git
    cd vuedesk
    ```

2.  **Install Dependencies**
    ```bash
    composer install
    npm install
    ```

3.  **Konfigurasi Environment**
    Salin file contoh `.env` dan sesuaikan koneksi database Anda.
    ```bash
    cp .env.example .env
    php artisan key:generate
    ```

4.  **Setup Database**
    Jalankan migrasi dan seeder untuk membuat tabel dan user default.
    ```bash
    php artisan migrate:fresh --seed
    ```
    > **Catatan:** Seeder akan membuat user default dengan role Super Admin, Bidan, dll.

5.  **Jalankan Aplikasi**
    Buka dua terminal terpisah:
    ```bash
    # Terminal 1 (Backend Server)
    php artisan serve

    # Terminal 2 (Frontend Builder/Watcher)
    npm run dev
    ```

6.  **Akses Aplikasi**
    Buka browser dan kunjungi `http://localhost:8000`.

## 🔐 Akun Default (Seeder)

Jika menjalankan seeder standar, Anda dapat menggunakan akun berikut:

-   **Super Admin**: `admin@rsukm.com` / `password`
-   **Bidan**: `bidan@rsukm.com` / `password`

## 📄 Struktur PDF SKL

PDF yang dihasilkan dirancang untuk kertas A4 dengan layout bilingual (Indonesia/Inggris) yang profesional, mencakup:
-   Kop Rumah Sakit
-   Data Kelahiran (Hari, Tanggal, Jam, Berat, Panjang, dll)
-   Data Orang Tua
-   Tanda Tangan Dokter (Digital/Scan)
-   QR Code Verifikasi Keaslian

## 🛡️ Keamanan

-   **Otentikasi**: Menggunakan fitur bawaan Laravel yang aman.
-   **Otorisasi**: Middleware memeriksa hak akses untuk setiap tindakan (Create, Read, Update, Delete).
-   **Perlindungan CSRF**: Terintegrasi otomatis.
-   **Validasi Input**: Validasi ketat di sisi server dan klien.

---

Dibuat untuk efisiensi pelayanan kesehatan.
