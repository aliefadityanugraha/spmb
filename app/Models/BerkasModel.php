<?php
class BerkasModel {
    private $table = 'berkas';
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    /**
     * Insert data berkas baru ke tabel berkas menggunakan Prepared Statement (MySQL)
     */
    public function insert(array $data) {
        $query = "INSERT INTO {$this->table} (
                    user_id,
                    jenis_berkas,
                    nama_berkas,
                    file_path,
                    mime_type,
                    ukuran_kb,
                    tingkat,
                    juara,
                    tahun_sertifikat
                  ) VALUES (
                    :user_id,
                    :jenis_berkas,
                    :nama_berkas,
                    :file_path,
                    :mime_type,
                    :ukuran_kb,
                    :tingkat,
                    :juara,
                    :tahun_sertifikat
                  )";

        $this->db->query($query);
        $this->db->bind(':user_id', $data['user_id']);
        $this->db->bind(':jenis_berkas', $data['jenis_berkas']);
        $this->db->bind(':nama_berkas', $data['nama_berkas']);
        $this->db->bind(':file_path', $data['file_path']);
        $this->db->bind(':mime_type', $data['mime_type']);
        $this->db->bind(':ukuran_kb', (int) $data['ukuran_kb'], PDO::PARAM_INT);
        $this->db->bind(':tingkat', $data['tingkat'] ?? null);
        $this->db->bind(':juara', $data['juara'] ?? null);
        $this->db->bind(':tahun_sertifikat', isset($data['tahun_sertifikat']) && $data['tahun_sertifikat'] !== '' ? (int) $data['tahun_sertifikat'] : null);

        $this->db->execute();
        $insertedId = (int) $this->db->lastInsertId();
        return $this->findByIdAndUser($insertedId, $data['user_id']);
    }

    /**
     * Alias create() untuk kompatibilitas
     */
    public function create(array $data) {
        return $this->insert($data);
    }

    /**
     * Mengambil satu berkas berdasarkan user_id dan jenis_berkas (untuk dokumen wajib tunggal)
     */
    public function findByUserAndJenis(string $userId, string $jenisBerkas) {
        $this->db->query("SELECT * FROM {$this->table} WHERE user_id = :user_id AND jenis_berkas = :jenis_berkas LIMIT 1");
        $this->db->bind(':user_id', $userId);
        $this->db->bind(':jenis_berkas', $jenisBerkas);
        return $this->db->single();
    }

    /**
     * Menyimpan atau memperbarui dokumen utama (kk, keterangan_sehat, kelakuan_baik, ijazah)
     * Mengembalikan path file lama jika diganti agar file fisik lama dapat dihapus.
     */
    public function saveOrUpdateDokumenUtama(array $data): ?string {
        $existing = $this->findByUserAndJenis($data['user_id'], $data['jenis_berkas']);

        if ($existing) {
            $oldFilePath = $existing['file_path'];
            $query = "UPDATE {$this->table} SET
                        nama_berkas       = :nama_berkas,
                        file_path         = :file_path,
                        mime_type         = :mime_type,
                        ukuran_kb         = :ukuran_kb,
                        status_verifikasi = 'menunggu'
                      WHERE id = :id AND user_id = :user_id";

            $this->db->query($query);
            $this->db->bind(':nama_berkas', $data['nama_berkas']);
            $this->db->bind(':file_path', $data['file_path']);
            $this->db->bind(':mime_type', $data['mime_type']);
            $this->db->bind(':ukuran_kb', (int) $data['ukuran_kb'], PDO::PARAM_INT);
            $this->db->bind(':id', (int) $existing['id'], PDO::PARAM_INT);
            $this->db->bind(':user_id', $data['user_id']);
            $this->db->execute();

            return $oldFilePath;
        }

        $this->insert($data);
        return null;
    }

    /**
     * Mengambil seluruh berkas milik user
     */
    public function getByUserId(string $userId): array {
        $this->db->query("SELECT * FROM {$this->table} WHERE user_id = :user_id ORDER BY id ASC");
        $this->db->bind(':user_id', $userId);
        return $this->db->resultSet();
    }

    /**
     * Mengelompokkan berkas user menjadi dokumen utama dan daftar sertifikat
     */
    public function getGroupedByUserId(string $userId): array {
        $all = $this->getByUserId($userId);
        $grouped = [
            'kk'               => null,
            'keterangan_sehat' => null,
            'kelakuan_baik'    => null,
            'ijazah'           => null,
            'sertifikat'       => []
        ];

        foreach ($all as $row) {
            $jenis = $row['jenis_berkas'];
            if (array_key_exists($jenis, $grouped) && $jenis !== 'sertifikat') {
                $grouped[$jenis] = $row;
            } elseif ($jenis === 'sertifikat' || $jenis === 'sertifikat_prestasi') {
                $grouped['sertifikat'][] = $row;
            }
        }

        return $grouped;
    }

    /**
     * Mengecek apakah Tahap 2 (4 Berkas Persyaratan Pokok: KK, Surat Sehat, Kelakuan Baik, Ijazah/SKL) sudah lengkap
     */
    public function isDokumenPokokComplete(string $userId, ?array $grouped = null): bool {
        if ($grouped === null) {
            $grouped = $this->getGroupedByUserId($userId);
        }
        return !empty($grouped['kk'])
            && !empty($grouped['keterangan_sehat'])
            && !empty($grouped['kelakuan_baik'])
            && !empty($grouped['ijazah']);
    }

    /**
     * Menghitung jumlah sertifikat yang sudah diunggah oleh user
     */
    public function countSertifikatByUserId(string $userId): int {
        $this->db->query("SELECT COUNT(*) AS total FROM {$this->table} WHERE user_id = :user_id AND jenis_berkas = 'sertifikat'");
        $this->db->bind(':user_id', $userId);
        $row = $this->db->single();
        return (int) ($row['total'] ?? 0);
    }

    /**
     * Mengambil satu berkas berdasarkan ID dan user_id
     */
    public function findByIdAndUser(int $id, string $userId) {
        $this->db->query("SELECT * FROM {$this->table} WHERE id = :id AND user_id = :user_id LIMIT 1");
        $this->db->bind(':id', $id, PDO::PARAM_INT);
        $this->db->bind(':user_id', $userId);
        return $this->db->single();
    }

    /**
     * Menghapus berkas berdasarkan ID dan user_id
     */
    public function deleteByIdAndUser(int $id, string $userId): bool {
        $this->db->query("DELETE FROM {$this->table} WHERE id = :id AND user_id = :user_id");
        $this->db->bind(':id', $id, PDO::PARAM_INT);
        $this->db->bind(':user_id', $userId);
        return $this->db->execute();
    }
}
