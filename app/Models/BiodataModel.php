<?php
class BiodataModel {
    private $table = 'biodata';
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    /**
     * Mengambil data biodata berdasarkan user_id (UUID)
     */
    public function findByUserId(string $userId) {
        $this->db->query("SELECT * FROM {$this->table} WHERE user_id = :user_id LIMIT 1");
        $this->db->bind(':user_id', $userId);
        return $this->db->single();
    }

    /**
     * Mengecek apakah user sudah memiliki record di tabel biodata
     */
    public function existsByUserId(string $userId): bool {
        $this->db->query("SELECT id FROM {$this->table} WHERE user_id = :user_id LIMIT 1");
        $this->db->bind(':user_id', $userId);
        return (bool) $this->db->single();
    }

    /**
     * Mengecek apakah Tahap 1 (Isi Biodata & Pas Foto) sudah lengkap
     */
    public function isBiodataComplete(?array $biodata): bool {
        if (empty($biodata) || !is_array($biodata)) {
            return false;
        }
        return !empty($biodata['tempat_lahir'])
            && !empty($biodata['tanggal_lahir'])
            && !empty($biodata['jenis_kelamin'])
            && !empty($biodata['alamat_lengkap'])
            && !empty($biodata['nama_sekolah_asal'])
            && !empty($biodata['nama_ibu'])
            && !empty($biodata['no_hp_ortu'])
            && !empty($biodata['pas_foto']);
    }

    /**
     * Menandai bahwa pendaftar sudah membuka/menyelesaikan Tahap 4 (Cetak Rekap Nilai)
     */
    public function markRekapSelesai(string $userId): bool {
        $this->db->query("UPDATE {$this->table} SET status_finalisasi = 1 WHERE user_id = :user_id");
        $this->db->bind(':user_id', $userId);
        return $this->db->execute();
    }

    /**
     * Insert data biodata baru ke tabel biodata menggunakan Prepared Statement
     */
    public function insert(string $userId, array $data): bool {
        $query = "INSERT INTO {$this->table} (
                    user_id,
                    nama_lengkap,
                    tempat_lahir,
                    tanggal_lahir,
                    jenis_kelamin,
                    agama,
                    alamat_lengkap,
                    rt,
                    rw,
                    kelurahan_desa,
                    kecamatan,
                    kabupaten_kota,
                    provinsi,
                    kode_pos,
                    latitude,
                    longitude,
                    npsn_sekolah_asal,
                    nama_sekolah_asal,
                    tahun_lulus,
                    nama_ayah,
                    pekerjaan_ayah,
                    nama_ibu,
                    pekerjaan_ibu,
                    nama_wali,
                    no_hp_ortu,
                    penghasilan_ortu,
                    pas_foto
                  ) VALUES (
                    :user_id,
                    :nama_lengkap,
                    :tempat_lahir,
                    :tanggal_lahir,
                    :jenis_kelamin,
                    :agama,
                    :alamat_lengkap,
                    :rt,
                    :rw,
                    :kelurahan_desa,
                    :kecamatan,
                    :kabupaten_kota,
                    :provinsi,
                    :kode_pos,
                    :latitude,
                    :longitude,
                    :npsn_sekolah_asal,
                    :nama_sekolah_asal,
                    :tahun_lulus,
                    :nama_ayah,
                    :pekerjaan_ayah,
                    :nama_ibu,
                    :pekerjaan_ibu,
                    :nama_wali,
                    :no_hp_ortu,
                    :penghasilan_ortu,
                    :pas_foto
                  )";

        $this->db->query($query);
        $this->bindBiodataParams($userId, $data);
        return $this->db->execute();
    }

    /**
     * Update data biodata yang sudah ada berdasarkan user_id menggunakan Prepared Statement
     */
    public function update(string $userId, array $data): bool {
        $query = "UPDATE {$this->table} SET
                    nama_lengkap      = :nama_lengkap,
                    tempat_lahir      = :tempat_lahir,
                    tanggal_lahir     = :tanggal_lahir,
                    jenis_kelamin     = :jenis_kelamin,
                    agama             = :agama,
                    alamat_lengkap    = :alamat_lengkap,
                    rt                = :rt,
                    rw                = :rw,
                    kelurahan_desa    = :kelurahan_desa,
                    kecamatan         = :kecamatan,
                    kabupaten_kota    = :kabupaten_kota,
                    provinsi          = :provinsi,
                    kode_pos          = :kode_pos,
                    latitude          = :latitude,
                    longitude         = :longitude,
                    npsn_sekolah_asal = :npsn_sekolah_asal,
                    nama_sekolah_asal = :nama_sekolah_asal,
                    tahun_lulus       = :tahun_lulus,
                    nama_ayah         = :nama_ayah,
                    pekerjaan_ayah    = :pekerjaan_ayah,
                    nama_ibu          = :nama_ibu,
                    pekerjaan_ibu     = :pekerjaan_ibu,
                    nama_wali         = :nama_wali,
                    no_hp_ortu        = :no_hp_ortu,
                    penghasilan_ortu  = :penghasilan_ortu,
                    pas_foto          = :pas_foto
                  WHERE user_id = :user_id";

        $this->db->query($query);
        $this->bindBiodataParams($userId, $data);
        return $this->db->execute();
    }

    /**
     * Simpan otomatis (Insert jika belum ada, Update jika sudah pernah mengisi)
     */
    public function saveOrUpdate(string $userId, array $data): bool {
        $this->db->beginTransaction();
        try {
            if ($this->existsByUserId($userId)) {
                $this->update($userId, $data);
            } else {
                $this->insert($userId, $data);
            }

            // Sinkronkan nama_lengkap dan tahun_lulus ke tabel users
            $this->db->query("UPDATE users SET nama_lengkap = :nama_lengkap, tahun_lulus = :tahun_lulus WHERE id = :id");
            $this->db->bind(':nama_lengkap', $data['nama_lengkap']);
            $this->db->bind(':tahun_lulus', (int) $data['tahun_lulus'], PDO::PARAM_INT);
            $this->db->bind(':id', $userId);
            $this->db->execute();

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    /**
     * Alias save() -> saveOrUpdate() untuk kompatibilitas BiodataController
     */
    public function save(string $userId, array $data): bool {
        return $this->saveOrUpdate($userId, $data);
    }

    /**
     * Helper pengikatan parameter Prepared Statement untuk semua kolom tabel biodata
     */
    private function bindBiodataParams(string $userId, array $data): void {
        $this->db->bind(':user_id', $userId);
        $this->db->bind(':nama_lengkap', $data['nama_lengkap']);
        $this->db->bind(':tempat_lahir', $data['tempat_lahir']);
        $this->db->bind(':tanggal_lahir', $data['tanggal_lahir']);
        $this->db->bind(':jenis_kelamin', $data['jenis_kelamin']);
        $this->db->bind(':agama', $data['agama']);
        $this->db->bind(':alamat_lengkap', $data['alamat_lengkap']);
        $this->db->bind(':rt', $data['rt'] !== '' ? $data['rt'] : null);
        $this->db->bind(':rw', $data['rw'] !== '' ? $data['rw'] : null);
        $this->db->bind(':kelurahan_desa', $data['kelurahan_desa']);
        $this->db->bind(':kecamatan', $data['kecamatan']);
        $this->db->bind(':kabupaten_kota', $data['kabupaten_kota']);
        $this->db->bind(':provinsi', $data['provinsi']);
        $this->db->bind(':kode_pos', $data['kode_pos'] !== '' ? $data['kode_pos'] : null);
        $this->db->bind(':latitude', $data['latitude'] !== '' && $data['latitude'] !== null ? $data['latitude'] : null);
        $this->db->bind(':longitude', $data['longitude'] !== '' && $data['longitude'] !== null ? $data['longitude'] : null);
        $this->db->bind(':npsn_sekolah_asal', $data['npsn_sekolah_asal'] !== '' ? $data['npsn_sekolah_asal'] : null);
        $this->db->bind(':nama_sekolah_asal', $data['nama_sekolah_asal']);
        $this->db->bind(':tahun_lulus', (int) $data['tahun_lulus'], PDO::PARAM_INT);
        $this->db->bind(':nama_ayah', $data['nama_ayah'] !== '' ? $data['nama_ayah'] : null);
        $this->db->bind(':pekerjaan_ayah', $data['pekerjaan_ayah'] !== '' ? $data['pekerjaan_ayah'] : null);
        $this->db->bind(':nama_ibu', $data['nama_ibu']);
        $this->db->bind(':pekerjaan_ibu', $data['pekerjaan_ibu'] !== '' ? $data['pekerjaan_ibu'] : null);
        $this->db->bind(':nama_wali', $data['nama_wali'] !== '' ? $data['nama_wali'] : null);
        $this->db->bind(':no_hp_ortu', $data['no_hp_ortu']);
        $this->db->bind(':penghasilan_ortu', $data['penghasilan_ortu'] !== '' ? $data['penghasilan_ortu'] : null);
        $this->db->bind(':pas_foto', $data['pas_foto'] ?? null);
    }
}
