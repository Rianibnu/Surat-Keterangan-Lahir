Tentu, ini adalah proyek yang menarik. Membuat aplikasi untuk pembuatan Surat Keterangan Lahir (SKL) menggunakan **Laravel 12 (dengan Breeze/Jetstream untuk *starter kit* Vue)** dan **MySQL** memerlukan perencanaan alur kerja dan fitur yang matang.

Berikut adalah penjelasan lengkap mengenai alur dan fitur yang harus Anda buat.

---

## 🏗️ Struktur Project dan Alur Kerja

Aplikasi ini akan berfungsi sebagai sistem manajemen data kelahiran dan pembuatan dokumen SKL.

### 1. Struktur Database (MySQL)

Anda akan membutuhkan minimal 3 tabel utama:

| Tabel | Deskripsi | Kolom Penting | Hubungan |
| --- | --- | --- | --- |
| `users` | Pengguna sistem (staf rumah sakit/admin) | `id`, `name`, `email`, `password`, `role` (misalnya: admin, dokter, perawat) |  |
| `birth_records` | Data detail kelahiran (Bayi, Ibu, Ayah) | `id`, `baby_name`, `birth_date`, `birth_time`, `hospital_name`, `medical_record_no`, `gender`, `child_order`, `delivery_method`, `baby_blood_type`, `weight`, `length`, `head_circ`, `chest_circ`, `mother_id`, `father_id`, `doctor_id`, `created_by_user_id` | **One-to-One** dengan SKL |
| `doctors` | Data Dokter Penanggung Jawab | `id`, `name`, `license_no`, `hospital` |  |
| `SKLs` | Data Surat Keterangan Lahir | `id`, `record_id`, `document_number`, `issue_date`, `signer_name`, `signer_role`, `is_signed`, `created_at` | **One-to-One** dengan `birth_records` |

### 2. Alur Proses (Workflow)

1. **Login:** Staf RS/Admin masuk ke sistem.
2. **Input Data Kelahiran:** Staf memasukkan data lengkap Bayi, Ibu, Ayah, dan detail persalinan ke dalam formulir.
3. **Verifikasi & Simpan:** Data disimpan ke tabel `birth_records`.
4. **Generasi SKL:** Sistem secara otomatis atau manual menggenerasi Nomor Surat (`document_number`) dan data terkait, menyimpannya ke tabel `SKLs` dengan merujuk pada `birth_records`.
5. **Cetak:** Pengguna mencetak SKL yang sudah terisi.

---

## 🛠️ Fitur Utama yang Harus Dibangun

### A. Fitur Dasar (Otentikasi & Keamanan)

1. **Login & Logout:** Menggunakan Laravel Breeze/Jetstream Vue.
2. **Manajemen Pengguna (Admin):** CRUD (Create, Read, Update, Delete) untuk akun staf/pengguna, termasuk penetapan **Role** (`admin`, `staf_input`, `dokter_penanggung_jawab`).
3. **Manajemen Dokter:** CRUD untuk data Dokter Penanggung Jawab Persalinan.

### B. Fitur Input Data Kelahiran

**1. Formulir Input Data Bayi dan Persalinan:**

* **Data Surat:** Nomor SKL (terisi otomatis/manual), Tanggal/Hari/Jam, Tempat Lahir (Rumah Sakit X).
* **Data Bayi:** Nama, No. Rekam Medis, Jenis Kelamin, Anak Ke-, Cara Persalinan, Golongan Darah, Berat (gram), Panjang (cm), Lingkar Kepala (cm), Lingkar Dada (cm).
* *Catatan:* Gunakan validasi data (misal: berat/panjang harus angka, jenis kelamin *dropdown*).



**2. Formulir Input Data Ibu:**

* Nama Ibu, No. Rekam Medis Ibu, No. KTP/Paspor, Alamat, Pekerjaan, Golongan Darah.

**3. Formulir Input Data Ayah:**

* Nama Ayah, No. KTP/Paspor, Alamat, Pekerjaan, Golongan Darah.

**4. Integrasi Input:**

* Sistem harus mampu mencari data Ibu/Ayah yang sudah ada untuk menghindari duplikasi data (misal: mencari berdasarkan No. KTP/Paspor).

### C. Fitur Manajemen Data (CRUD & Pencarian)

1. **Daftar Data Kelahiran (Tabel):** Menampilkan semua data kelahiran yang sudah diinput.
* Kolom: No. SKL, Nama Bayi, Nama Ibu, Tanggal Lahir, Status (SKL sudah dicetak/belum).


2. **Pencarian & Filter:**
* Cari berdasarkan: Nama Bayi/Ibu/Ayah, Nomor SKL, Tanggal Lahir.
* Filter berdasarkan: Jenis Kelamin, Cara Persalinan, Rentang Tanggal.


3. **Edit & Hapus Data:** Fitur untuk mengubah atau menghapus data kelahiran (dengan batasan hak akses, hanya Admin/Staf tertentu).

### D. Fitur Generasi dan Cetak SKL

1. **Pratinjau (Preview) SKL:** Menampilkan data yang telah diinput dalam format *layout* SKL persis seperti gambar sebelum dicetak.
2. **Generasi Nomor Surat Otomatis:** Sistem dapat menghasilkan nomor surat sesuai format yang diinginkan (misal: `/SKL/RM-RSUKM/ /20...`).
3. **Pilihan Penandatangan:** Memilih Dokter Penanggung Jawab Persalinan dari data `doctors`.
4. **Fitur Cetak (Print):**
* Tombol untuk mencetak SKL. **Gunakan CSS Print Media Query** di sisi Vue/Front-end agar hasil cetak bersih (menghilangkan tombol, *header*, *footer* aplikasi).
* Hasil cetak harus menyerupai format pada gambar.



---

## 💻 Implementasi Teknologi (Laravel & Vue)

### Laravel (Backend)

* **Routing:** Gunakan *routing* Laravel untuk API (misal: `/api/birth-records`, `/api/doctors`).
* **Eloquent Models:** Buat Model untuk `User`, `BirthRecord`, `Doctor`, dan `SKL` dengan mendefinisikan relasi yang benar (`$hasOne`, `$belongsTo`).
* **Migrations:** Buat skema tabel MySQL yang sesuai.
* **Controllers:** Logika bisnis, validasi input data, dan koneksi ke database.
* **Authorization:** Gunakan *Gates* atau *Policies* Laravel untuk mengelola hak akses berdasarkan `role` (misal: hanya Admin yang bisa menghapus data).

### Vue (Frontend)

* **Component-Based UI:**
* **Layout Component:** Tata letak utama (Sidebar, Header).
* **Input Form Components:** Komponen terpisah untuk Input Data Bayi, Ibu, dan Ayah.
* **Table Component:** Untuk daftar data kelahiran.
* **SKL Preview Component:** Komponen khusus untuk menampilkan dan mencetak SKL.


* **State Management (Optional):** Gunakan Pinia (disarankan untuk Vue 3) untuk mengelola data formulir kompleks atau status aplikasi secara global.
* **API Calls:** Gunakan **Axios** untuk berkomunikasi dengan *backend* Laravel.
* **Routing (Vue Router):** Untuk navigasi di sisi klien (misal: `/dashboard`, `/birth-records/create`, `/birth-records/:id/view`).

---

Apakah Anda ingin saya memberikan contoh spesifik untuk **skema database** atau **struktur API *endpoint*** yang akan digunakan?