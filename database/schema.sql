-- ============================================================================
-- SCRIPT DDL DATABASE SISTEM PENERIMAAN MURID BARU (SPMB) - MYSQL 8.0
-- SMAN 5 TARUNA BRAWIJAYA JAWA TIMUR
-- ============================================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ============================================================================
-- 1. TABEL USERS (Akun & Identitas Utama Pendaftar)
-- ============================================================================
CREATE TABLE IF NOT EXISTS users (
    id CHAR(36) NOT NULL PRIMARY KEY COMMENT 'UUID akun pengguna',
    nik CHAR(16) NOT NULL UNIQUE COMMENT 'Nomor Induk Kependudukan (16 digit)',
    nisn CHAR(10) NOT NULL UNIQUE COMMENT 'Nomor Induk Siswa Nasional (10 digit)',
    nama_lengkap VARCHAR(150) NOT NULL COMMENT 'Nama lengkap sesuai ijazah/akta',
    no_hp VARCHAR(20) NOT NULL UNIQUE COMMENT 'Nomor HP/WhatsApp aktif untuk login',
    password VARCHAR(255) NOT NULL COMMENT 'Hash password akun (Bcrypt/Argon2id)',
    tahun_lulus SMALLINT NOT NULL COMMENT 'Tahun lulus SMP/MTs sederajat',
    email_ortu VARCHAR(150) NOT NULL COMMENT 'Alamat email aktif',
    jalur_pendaftaran VARCHAR(50) NULL DEFAULT NULL COMMENT 'Jalur Umum / Jalur Beasiswa',
    role VARCHAR(20) NOT NULL DEFAULT 'calon_siswa' COMMENT 'calon_siswa / admin',
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_users_jalur (jalur_pendaftaran),
    INDEX idx_users_role (role)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- ============================================================================
-- 2. TABEL BIODATA (Data Diri, Alamat, Asal Sekolah, & Orang Tua)
-- ============================================================================
CREATE TABLE IF NOT EXISTS biodata (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    user_id CHAR(36) NOT NULL UNIQUE,
    nama_lengkap VARCHAR(150) NOT NULL,
    tempat_lahir VARCHAR(100) NULL,
    tanggal_lahir DATE NULL,
    jenis_kelamin ENUM('L', 'P') NULL,
    agama VARCHAR(30) NULL,

    -- Data Alamat & Koordinat
    alamat_lengkap TEXT NULL,
    rt VARCHAR(5) NULL,
    rw VARCHAR(5) NULL,
    kelurahan_desa VARCHAR(100) NULL,
    kecamatan VARCHAR(100) NULL,
    kabupaten_kota VARCHAR(100) NULL,
    provinsi VARCHAR(100) NULL,
    kode_pos CHAR(5) NULL,
    latitude DECIMAL(10, 7) NULL,
    longitude DECIMAL(10, 7) NULL,

    -- Data Sekolah Asal
    npsn_sekolah_asal CHAR(8) NULL,
    nama_sekolah_asal VARCHAR(150) NULL,
    tahun_lulus SMALLINT NOT NULL,

    -- Data Orang Tua / Wali & Pas Foto
    nama_ayah VARCHAR(150) NULL,
    pekerjaan_ayah VARCHAR(100) NULL,
    nama_ibu VARCHAR(150) NULL,
    pekerjaan_ibu VARCHAR(100) NULL,
    nama_wali VARCHAR(150) NULL,
    no_hp_ortu VARCHAR(20) NULL,
    penghasilan_ortu VARCHAR(50) NULL,
    pas_foto TEXT NULL,

    status_finalisasi TINYINT(1) NOT NULL DEFAULT 0,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_biodata_user
        FOREIGN KEY (user_id)
        REFERENCES users(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- ============================================================================
-- 3. TABEL BERKAS (Dokumen Persyaratan & Sertifikat Prestasi)
-- ============================================================================
CREATE TABLE IF NOT EXISTS berkas (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    user_id CHAR(36) NOT NULL,
    jenis_berkas VARCHAR(50) NOT NULL,
    nama_berkas VARCHAR(200) NOT NULL,
    file_path TEXT NOT NULL,
    mime_type VARCHAR(50) NOT NULL,
    ukuran_kb INT NOT NULL,

    -- Kolom khusus sertifikat / piagam penghargaan (Opsional / Nullable)
    tingkat VARCHAR(50) NULL,
    juara VARCHAR(50) NULL,
    tahun_sertifikat SMALLINT NULL,

    -- Status Verifikasi Panitia
    status_verifikasi ENUM('menunggu', 'valid', 'ditolak', 'perbaikan') NOT NULL DEFAULT 'menunggu',
    catatan_verifikator TEXT NULL,
    verified_at TIMESTAMP NULL DEFAULT NULL,

    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    INDEX idx_berkas_user_id (user_id),
    INDEX idx_berkas_user_status (user_id, status_verifikasi),
    INDEX idx_berkas_jenis_status (jenis_berkas, status_verifikasi),

    CONSTRAINT fk_berkas_user
        FOREIGN KEY (user_id)
        REFERENCES users(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- ============================================================================
-- 4. TABEL NILAI_RAPORT (Nilai Akademik per Semester & Mata Pelajaran)
-- ============================================================================
CREATE TABLE IF NOT EXISTS nilai_raport (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    user_id CHAR(36) NOT NULL,
    semester SMALLINT NOT NULL,
    mata_pelajaran VARCHAR(100) NOT NULL,
    nilai_pengetahuan DECIMAL(5, 2) NOT NULL,
    nilai_keterampilan DECIMAL(5, 2) NULL,
    status_verifikasi ENUM('menunggu', 'valid', 'ditolak', 'perbaikan') NOT NULL DEFAULT 'menunggu',
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    UNIQUE KEY uq_user_semester_mapel (user_id, semester, mata_pelajaran),
    INDEX idx_nilai_raport_user_id (user_id),
    INDEX idx_nilai_user_sem (user_id, semester),

    CONSTRAINT fk_nilai_raport_user
        FOREIGN KEY (user_id)
        REFERENCES users(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- 5. SEED AKUN DEFAULT ADMINISTRATOR PANITIA SPMB
--    Login: 081111111111 (atau 'admin') | Password: AdminSPMB2027!
-- ============================================================================
INSERT IGNORE INTO users (
    id, nik, nisn, nama_lengkap, no_hp, password, tahun_lulus, email_ortu, jalur_pendaftaran, role, is_active
) VALUES (
    '00000000-0000-4000-8000-000000000001',
    '3571010000000001',
    '0000000001',
    'Administrator Panitia SPMB',
    '081111111111',
    '$2y$10$wK1xV8mP2jR5nL9qT4vY7uO6eH3cB8zF1aG5dJ2kM9pN4rS6tU8vW',
    2026,
    'admin@sman5tarunabrawijaya.sch.id',
    'Jalur Umum',
    'admin',
    1
);

SET FOREIGN_KEY_CHECKS = 1;
