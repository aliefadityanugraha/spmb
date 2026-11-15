# Sistem Penerimaan Murid Baru (SPMB) — SMAN 5 Taruna Brawijaya Jawa Timur

Aplikasi web **Sistem Penerimaan Murid Baru (SPMB) SMAN 5 Taruna Brawijaya Jawa Timur** berbasis arsitektur **PHP Native MVC (Modular)**, **MySQL 8.0**, **Tailwind CSS**, dan **Docker**. Dirancang dengan antarmuka **Light Theme beraksen Hijau Emerald** yang **100% Responsive Mobile** baik untuk Calon Taruna maupun Panitia Seleksi (Administrator).

---

## 🔐 Akun Login Default (Administrator)

Sistem dilengkapi mekanisme *auto-seeding* yang otomatis membuat akun **Administrator Default** saat sistem pertama kali diakses:

| Parameter | Kredensial Default |
| :--- | :--- |
| **URL Login** | `http://localhost:8080/auth/login` |
| **URL Panel Admin** | `http://localhost:8080/admin` |
| **Username / Nomor HP** | `081111111111` |
| **Password** | `AdminSPMB2027!` |
| **Role** | `admin` |

> **Catatan:** Saat login menggunakan Nomor HP & Password Admin di atas pada halaman `/auth/login`, sistem akan otomatis mengarahkan pengguna langsung ke **Panel Admin (`/admin`)**.

---

## ✨ Fitur Utama Sistem

### 1. Halaman Beranda Publik (`/`)
- Informasi resmi penerimaan Calon Taruna Baru SMAN 5 Taruna Brawijaya.
- Statistik *real-time* jumlah pendaftar terdaftar (Total Pendaftar, Jalur Umum, dan Jalur Beasiswa).
- Penjelasan 2 Jalur Seleksi (**Jalur Umum** & **Jalur Beasiswa**), 5 Langkah Alur Pendaftaran, Daftar Persyaratan Berkas, Jadwal Seleksi, serta FAQ interaktif.

### 2. Portal Calon Taruna (`/dashboard`)
- **Autentikasi & Registrasi (`/auth/register` & `/auth/login`)**:
  - Registrasi menggunakan NIK (16 digit), NISN (10 digit), Nama Lengkap, Nomor WhatsApp aktif, Tahun Lulus, Email, dan Password (`BCrypt`).
- **Pemilihan Jalur Pendaftaran**:
  - Pilihan **Jalur Umum** atau **Jalur Beasiswa** langsung dari dasbor peserta.
- **Pengisian Biodata & Pas Foto (`/biodata`)**:
  - Mode *Insert / Update (Edit Mode)* otomatis untuk data kelahiran, asal sekolah (NPSN), data orang tua/wali, penghasilan, alamat lengkap domisili, serta unggah **Pas Foto 3x4** (JPG/JPEG/PNG maks. 5 MB).
- **Unggah Berkas Persyaratan (`/berkas`)**:
  - Unggah 4 dokumen wajib: **Kartu Keluarga (KK)**, **Surat Keterangan Sehat**, **Surat Keterangan Kelakuan Baik (SKKB)**, dan **Ijazah/SKL**.
  - Form dinamis unggah hingga **5 Sertifikat Prestasi** beserta tingkat kejuaraan (`Kota/Kabupaten`, `Provinsi`, `Nasional`) — wajib/utama bagi *Jalur Beasiswa* dan *opsional* sebagai nilai tambah bagi *Jalur Umum*.
  - Validasi ketat MIME & ekstensi (`JPG`, `JPEG`, `PNG`) dengan ukuran maksimal **5 MB (5.120 KB)** per file melalui `UploadService.php`.
- **Input Nilai Rapor Semester 1 s.d. 4 (`/nilai`)**:
  - Input nilai 10 mata pelajaran (*Pendidikan Agama, PKn, Bahasa Indonesia, Matematika, IPA, IPS, Bahasa Inggris, PJOK, Informatika, Seni Budaya*) yang terbagi ke dalam 4 Tab Semester (Semester 1–4) menggunakan transaksi database (`BEGIN` / `COMMIT`).
- **Cetak Dokumen Resmi (`@media print`)**:
  - **Cetak Kartu Tanda Peserta (`/cetak/kartuPeserta`)**: Dilengkapi Nomor Peserta otomatis, Pas Foto 3x4, identitas diri, dan daftar *checklist* pemeriksaan berkas fisik.
  - **Cetak Rekapitulasi Nilai Rapor (`/cetak/rekapNilai`)**: Tabel rekapitulasi nilai Semester 1–4 beserta rata-rata akhir dan Surat Pernyataan Keaslian Dokumen bermeterai.

### 3. Panel Khusus Administrator (`/admin`)
Dilengkapi 5 menu utama yang responsif (mendukung *Mobile Card View* di layar HP dan *Tabel Proporsional* di layar Desktop):
1. **Ringkasan Dasbor (`/admin`)**: Kartu KPI statistik pendaftar, komposisi jalur, status verifikasi berkas, antrean berkas terbaru, dan pendaftar terbaru.
2. **Data Calon Taruna (`/admin/pendaftar` & `/admin/detail/{id}`)**: Pencarian & filter pendaftar, inspeksi lengkap biodata & pas foto, ubah jalur pendaftaran siswa, hapus pendaftar, verifikasi berkas, dan tabel nilai rapor per siswa.
3. **Verifikasi Berkas (`/admin/berkas`)**: Antrean pemeriksaan berkas dengan filter status (`Menunggu`, `Valid`, `Perlu Perbaikan`, `Ditolak`), pratinjau file unggahan, serta pemberian catatan verifikator.
4. **Peringkat Nilai Rapor (`/admin/nilai`)**: Pemeringkatan otomatis calon taruna berdasarkan rata-rata nilai rapor Semester 1–4 tertinggi beserta filter per jalur pendaftaran.
5. **Laporan & Ekspor Data (`/admin/laporan` & `/admin/exportCsv`)**: Cetak laporan rekapitulasi resmi format kertas A4 Landscape (`@media print`) dan unduh seluruh data pendaftar dalam format **CSV / Microsoft Excel**.

---

## 🛠️ Spesifikasi Teknologi & Keamanan

- **Backend**: PHP 8.2 (Apache dengan `mod_rewrite` aktif) — Custom MVC Architecture
- **Database**: MySQL 8.0 (`InnoDB`, `utf8mb4_unicode_ci`, dengan UUID primary keys untuk tabel `users`)
- **Frontend**: HTML5, Tailwind CSS, Vanilla JavaScript, Google Fonts (*Plus Jakarta Sans*)
- **Containerization**: Docker & Docker Compose (`spmb_web` & `spmb_db`)
- **Standar Keamanan**:
  - Seluruh kueri SQL menggunakan **PDO Prepared Statements** (`PDO::ATTR_EMULATE_PREPARES => false`).
  - Password di-hash menggunakan `password_hash(..., PASSWORD_BCRYPT)`.
  - Proteksi sesi (`session_regenerate_id(true)`) dan pembatasan akses rute berdasarkan `role` (`calon_taruna` vs `admin`).
  - Validasi tipe MIME asli file (`finfo_file`) dan batas ukuran unggahan (`<= 5 MB`) di `UploadService.php`.

---

## 📂 Struktur Direktori Proyek

```text
smstk/
├── app/
│   ├── Config/
│   │   └── Database.php           # Konfigurasi koneksi PDO PostgreSQL
│   ├── Controllers/
│   │   ├── HomeController.php     # Halaman Beranda Publik (/)
│   │   ├── AuthController.php     # Login, Registrasi, dan Logout
│   │   ├── DashboardController.php# Dasbor utama Calon Taruna
│   │   ├── BiodataController.php  # Pengisian Biodata & Upload Pas Foto
│   │   ├── BerkasController.php   # Upload Berkas Wajib & Sertifikat Prestasi
│   │   ├── NilaiController.php    # Input Nilai Rapor Semester 1-4
│   │   ├── CetakController.php    # Cetak Kartu Peserta & Rekap Nilai
│   │   └── AdminController.php    # Seluruh fitur Panel Administrator
│   ├── Core/
│   │   ├── App.php                # Router URL MVC
│   │   └── Controller.php         # Base Controller (loader view & model)
│   ├── Models/
│   │   ├── UserModel.php          # Model akun pengguna & auto-seed Admin
│   │   ├── BiodataModel.php       # Model biodata calon taruna
│   │   ├── BerkasModel.php        # Model berkas persyaratan & sertifikat
│   │   ├── NilaiModel.php         # Model nilai rapor vertikal (Sem 1-4)
│   │   └── AdminModel.php         # Model statistik, verifikasi & ranking admin
│   ├── Services/
│   │   └── UploadService.php      # Service validasi & penyimpanan file (JPG/PNG <= 5MB)
│   └── Views/
│       ├── home/                  # Tampilan Beranda Publik (index.php)
│       ├── auth/                  # Tampilan Login & Registrasi
│       ├── dashboard/             # Tampilan Portal Calon Taruna & Print View
│       └── admin/                 # Tampilan 5 Menu Panel Administrator
├── database/
│   └── schema.sql                 # Skema DDL MySQL 8.0 (users, biodata, berkas, nilai_raport)
├── public/
│   ├── .htaccess                  # Konfigurasi routing Apache & limit upload PHP
│   ├── index.php                  # Entry point aplikasi
│   └── uploads/                   # Direktori penyimpanan pas foto & berkas pendaftar
├── Dockerfile                     # Konfigurasi image PHP 8.2 Apache + pdo_mysql
├── docker-compose.yaml            # Orkestrasi container spmb_web & spmb_db (MySQL 8.0)
└── README.md                      # Dokumentasi sistem
```

---

## 🚀 Cara Menjalankan Sistem (Docker)

1. **Pastikan Docker Desktop / Docker Engine sudah berjalan.**
2. **Jalankan kontainer melalui terminal pada direktori proyek:**
   ```bash
   docker-compose down -v
   docker-compose up -d --build
   ```
3. **Akses aplikasi melalui browser:**
   - **Beranda Utama:** [http://localhost:8080/](http://localhost:8080/)
   - **Halaman Login:** [http://localhost:8080/auth/login](http://localhost:8080/auth/login)
   - **Halaman Registrasi Siswa:** [http://localhost:8080/auth/register](http://localhost:8080/auth/register)
   - **Panel Admin:** [http://localhost:8080/admin](http://localhost:8080/admin)

---

## 🗄️ Konfigurasi Database (MySQL 8.0)

Konfigurasi default pada `docker-compose.yaml`:
- **Container Name:** `spmb_db`
- **Host (Internal Docker):** `db` (Port Eksternal: `3306`)
- **Database Name:** `spmb_db`
- **Database User:** `spmb_admin`
- **Database Password:** `SpMb2027Taruna!`
- **Root Password:** `SpMb2027Root!`
