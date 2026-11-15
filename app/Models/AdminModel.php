<?php
class AdminModel {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    /**
     * Mengambil ringkasan statistik untuk halaman utama Dasbor Admin (Kompatibel MySQL 8.0 / MariaDB cPanel)
     */
    public function getDashboardStats(): array {
        $userStats   = [];
        $bioStats    = [];
        $berkasStats = [];

        try {
            // 1. Statistik pendaftar berdasarkan jalur
            $this->db->query("
                SELECT
                    COALESCE(SUM(CASE WHEN role = 'calon_siswa' THEN 1 ELSE 0 END), 0) AS total_pendaftar,
                    COALESCE(SUM(CASE WHEN role = 'calon_siswa' AND jalur_pendaftaran = 'Jalur Umum' THEN 1 ELSE 0 END), 0) AS total_umum,
                    COALESCE(SUM(CASE WHEN role = 'calon_siswa' AND jalur_pendaftaran = 'Jalur Beasiswa' THEN 1 ELSE 0 END), 0) AS total_beasiswa,
                    COALESCE(SUM(CASE WHEN role = 'calon_siswa' AND (jalur_pendaftaran IS NULL OR jalur_pendaftaran = '') THEN 1 ELSE 0 END), 0) AS belum_pilih_jalur
                FROM users
            ");
            $userStats = $this->db->single() ?: [];
        } catch (Exception $e) {
            $userStats = [];
        }

        try {
            // 2. Statistik kelengkapan biodata
            $this->db->query("
                SELECT COUNT(*) AS biodata_lengkap
                FROM biodata b
                JOIN users u ON u.id = b.user_id
                WHERE u.role = 'calon_siswa'
                  AND b.tempat_lahir IS NOT NULL AND b.tempat_lahir <> ''
                  AND b.alamat_lengkap IS NOT NULL AND b.alamat_lengkap <> ''
            ");
            $bioStats = $this->db->single() ?: [];
        } catch (Exception $e) {
            $bioStats = [];
        }

        try {
            // 3. Statistik verifikasi berkas
            $this->db->query("
                SELECT
                    COUNT(*) AS total_berkas,
                    COALESCE(SUM(CASE WHEN status_verifikasi = 'menunggu' THEN 1 ELSE 0 END), 0) AS berkas_menunggu,
                    COALESCE(SUM(CASE WHEN status_verifikasi = 'valid' THEN 1 ELSE 0 END), 0) AS berkas_valid,
                    COALESCE(SUM(CASE WHEN status_verifikasi IN ('perbaikan', 'ditolak') THEN 1 ELSE 0 END), 0) AS berkas_ditolak
                FROM berkas
            ");
            $berkasStats = $this->db->single() ?: [];
        } catch (Exception $e) {
            $berkasStats = [];
        }

        return [
            'total_pendaftar'   => (int) ($userStats['total_pendaftar'] ?? 0),
            'total_umum'        => (int) ($userStats['total_umum'] ?? 0),
            'total_beasiswa'    => (int) ($userStats['total_beasiswa'] ?? 0),
            'belum_pilih_jalur' => (int) ($userStats['belum_pilih_jalur'] ?? 0),
            'biodata_lengkap'   => (int) ($bioStats['biodata_lengkap'] ?? 0),
            'total_berkas'      => (int) ($berkasStats['total_berkas'] ?? 0),
            'berkas_menunggu'   => (int) ($berkasStats['berkas_menunggu'] ?? 0),
            'berkas_valid'      => (int) ($berkasStats['berkas_valid'] ?? 0),
            'berkas_ditolak'    => (int) ($berkasStats['berkas_ditolak'] ?? 0)
        ];
    }

    /**
     * Mengambil daftar seluruh pendaftar beserta ringkasan biodata, jumlah berkas, dan rata-rata nilai
     */
    public function getAllPendaftar(string $search = '', string $jalur = ''): array {
        try {
            $where = ["u.role = 'calon_siswa'"];
            $params = [];

            if ($search !== '') {
                $where[] = "(u.nama_lengkap LIKE :s1 OR u.nisn LIKE :s2 OR u.nik LIKE :s3 OR u.no_hp LIKE :s4 OR COALESCE(b.nama_sekolah_asal, '') LIKE :s5)";
                $likeVal = '%' . $search . '%';
                $params[':s1'] = $likeVal;
                $params[':s2'] = $likeVal;
                $params[':s3'] = $likeVal;
                $params[':s4'] = $likeVal;
                $params[':s5'] = $likeVal;
            }

            if ($jalur !== '') {
                $where[] = "u.jalur_pendaftaran = :jalur";
                $params[':jalur'] = $jalur;
            }

            $whereSql = implode(' AND ', $where);

            $query = "
                SELECT
                    u.id,
                    u.nik,
                    u.nisn,
                    u.nama_lengkap,
                    u.no_hp,
                    u.email_ortu,
                    u.tahun_lulus,
                    u.jalur_pendaftaran,
                    u.is_active,
                    u.created_at,
                    b.tempat_lahir,
                    b.tanggal_lahir,
                    b.jenis_kelamin,
                    b.nama_sekolah_asal,
                    b.kabupaten_kota,
                    b.pas_foto,
                    COALESCE(bk_stat.jumlah_berkas, 0) AS jumlah_berkas,
                    COALESCE(bk_stat.berkas_valid, 0) AS berkas_valid,
                    nr_stat.rata_nilai AS rata_nilai,
                    COALESCE(nr_stat.jumlah_entri_nilai, 0) AS jumlah_entri_nilai
                FROM users u
                LEFT JOIN biodata b ON b.user_id = u.id
                LEFT JOIN (
                    SELECT
                        user_id,
                        COUNT(*) AS jumlah_berkas,
                        SUM(CASE WHEN status_verifikasi = 'valid' THEN 1 ELSE 0 END) AS berkas_valid
                    FROM berkas
                    GROUP BY user_id
                ) bk_stat ON bk_stat.user_id = u.id
                LEFT JOIN (
                    SELECT
                        user_id,
                        ROUND(AVG(nilai_pengetahuan), 2) AS rata_nilai,
                        COUNT(*) AS jumlah_entri_nilai
                    FROM nilai_raport
                    WHERE semester BETWEEN 1 AND 4
                    GROUP BY user_id
                ) nr_stat ON nr_stat.user_id = u.id
                WHERE {$whereSql}
                ORDER BY u.created_at DESC
            ";

            $this->db->query($query);
            foreach ($params as $key => $val) {
                $this->db->bind($key, $val);
            }

            return $this->db->resultSet();
        } catch (Exception $e) {
            return [];
        }
    }

    /**
     * Mengambil daftar berkas untuk halaman Verifikasi Berkas Admin
     */
    public function getAllBerkasQueue(string $status = '', string $search = ''): array {
        try {
            $where = ["u.role = 'calon_siswa'"];
            $params = [];

            if ($status !== '') {
                $where[] = "bk.status_verifikasi = :status";
                $params[':status'] = $status;
            }

            if ($search !== '') {
                $where[] = "(u.nama_lengkap LIKE :s1 OR u.nisn LIKE :s2 OR bk.nama_berkas LIKE :s3)";
                $likeVal = '%' . $search . '%';
                $params[':s1'] = $likeVal;
                $params[':s2'] = $likeVal;
                $params[':s3'] = $likeVal;
            }

            $whereSql = implode(' AND ', $where);

            $query = "
                SELECT
                    bk.id,
                    bk.user_id,
                    bk.jenis_berkas,
                    bk.nama_berkas,
                    bk.file_path,
                    bk.mime_type,
                    bk.ukuran_kb,
                    bk.tingkat,
                    bk.status_verifikasi,
                    bk.catatan_verifikator,
                    bk.verified_at,
                    bk.created_at,
                    u.nama_lengkap,
                    u.nisn,
                    u.jalur_pendaftaran
                FROM berkas bk
                JOIN users u ON u.id = bk.user_id
                WHERE {$whereSql}
                ORDER BY
                    CASE WHEN bk.status_verifikasi = 'menunggu' THEN 0 ELSE 1 END ASC,
                    bk.created_at DESC
            ";

            $this->db->query($query);
            foreach ($params as $key => $val) {
                $this->db->bind($key, $val);
            }

            return $this->db->resultSet();
        } catch (Exception $e) {
            return [];
        }
    }

    /**
     * Memperbarui status verifikasi berkas oleh Admin
     */
    public function updateVerifikasiBerkas(int $berkasId, string $status, ?string $catatan = null): bool {
        $query = "
            UPDATE berkas
            SET status_verifikasi = :status,
                catatan_verifikator = :catatan,
                verified_at = CURRENT_TIMESTAMP
            WHERE id = :id
        ";
        $this->db->query($query);
        $this->db->bind(':status', $status);
        $this->db->bind(':catatan', $catatan !== '' ? $catatan : null);
        $this->db->bind(':id', $berkasId, PDO::PARAM_INT);
        return $this->db->execute();
    }

    /**
     * Mengambil peringkat nilai rapor seluruh calon taruna (Semester 1 - 4)
     */
    public function getRankingNilai(string $jalur = ''): array {
        try {
            $where = ["u.role = 'calon_siswa'"];
            $params = [];

            if ($jalur !== '') {
                $where[] = "u.jalur_pendaftaran = :jalur";
                $params[':jalur'] = $jalur;
            }

            $whereSql = implode(' AND ', $where);

            $query = "
                SELECT
                    u.id,
                    u.nisn,
                    u.nik,
                    u.nama_lengkap,
                    u.jalur_pendaftaran,
                    b.nama_sekolah_asal,
                    COUNT(nr.id) AS total_mapel_diisi,
                    COALESCE(ROUND(AVG(CASE WHEN nr.semester = 1 THEN nr.nilai_pengetahuan END), 2), 0) AS rata_sem1,
                    COALESCE(ROUND(AVG(CASE WHEN nr.semester = 2 THEN nr.nilai_pengetahuan END), 2), 0) AS rata_sem2,
                    COALESCE(ROUND(AVG(CASE WHEN nr.semester = 3 THEN nr.nilai_pengetahuan END), 2), 0) AS rata_sem3,
                    COALESCE(ROUND(AVG(CASE WHEN nr.semester = 4 THEN nr.nilai_pengetahuan END), 2), 0) AS rata_sem4,
                    COALESCE(ROUND(SUM(nr.nilai_pengetahuan), 2), 0) AS total_nilai,
                    COALESCE(ROUND(AVG(nr.nilai_pengetahuan), 2), 0) AS rata_total
                FROM users u
                LEFT JOIN biodata b ON b.user_id = u.id
                LEFT JOIN nilai_raport nr ON nr.user_id = u.id AND nr.semester BETWEEN 1 AND 4
                WHERE {$whereSql}
                GROUP BY u.id, u.nisn, u.nik, u.nama_lengkap, u.jalur_pendaftaran, b.nama_sekolah_asal
                ORDER BY rata_total DESC, total_nilai DESC, u.nama_lengkap ASC
            ";

            $this->db->query($query);
            foreach ($params as $key => $val) {
                $this->db->bind($key, $val);
            }

            return $this->db->resultSet();
        } catch (Exception $e) {
            return [];
        }
    }

    /**
     * Menghapus akun pendaftar (otomatis menghapus biodata, berkas, nilai_raport karena ON DELETE CASCADE)
     */
    public function deletePendaftar(string $userId): bool {
        $this->db->query("DELETE FROM users WHERE id = :id AND role = 'calon_siswa'");
        $this->db->bind(':id', $userId);
        return $this->db->execute();
    }
}
