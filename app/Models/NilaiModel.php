<?php
class NilaiModel {
    private $table = 'nilai_raport';
    private $db;

    /**
     * Daftar 10 Mata Pelajaran Wajib SPMB (Semester 1 s.d. 4)
     */
    public const MATA_PELAJARAN = [
        'Agama'       => 'Pendidikan Agama & Budi Pekerti (Agama)',
        'PKn'         => 'Pendidikan Pancasila & Kewarganegaraan (PKn)',
        'B.Indo'      => 'Bahasa Indonesia (B.Indo)',
        'MTK'         => 'Matematika (MTK)',
        'IPA'         => 'Ilmu Pengetahuan Alam (IPA)',
        'IPS'         => 'Ilmu Pengetahuan Sosial (IPS)',
        'B.Inggris'   => 'Bahasa Inggris (B.Inggris)',
        'Seni Budaya' => 'Seni Budaya',
        'PJOK'        => 'Pendidikan Jasmani, Olahraga, & Kesehatan (PJOK)',
        'Informatika' => 'Informatika'
    ];

    public function __construct() {
        $this->db = new Database();
    }

    /**
     * Insert atau Update satu baris nilai secara vertikal (user_id, semester, mata_pelajaran, nilai)
     * menggunakan PDO Prepared Statement (MySQL ON DUPLICATE KEY UPDATE).
     */
    public function upsertNilaiVertikal(string $userId, int $semester, string $mataPelajaran, float $nilai): bool {
        $query = "INSERT INTO {$this->table} (
                    user_id,
                    semester,
                    mata_pelajaran,
                    nilai_pengetahuan
                  ) VALUES (
                    :user_id,
                    :semester,
                    :mata_pelajaran,
                    :nilai
                  )
                  ON DUPLICATE KEY UPDATE
                    nilai_pengetahuan = VALUES(nilai_pengetahuan),
                    status_verifikasi = 'menunggu'";

        $this->db->query($query);
        $this->db->bind(':user_id', $userId);
        $this->db->bind(':semester', $semester, PDO::PARAM_INT);
        $this->db->bind(':mata_pelajaran', $mataPelajaran);
        $this->db->bind(':nilai', $nilai);

        return $this->db->execute();
    }

    /**
     * Menyimpan array nilai 10 mata pelajaran untuk Semester 1 s.d. 4 sekaligus
     * menggunakan 1 Bulk Query Multi-Row INSERT ... ON DUPLICATE KEY UPDATE
     * Format $nilaiMatrix: [semester => [mata_pelajaran => nilai]]
     */
    public function saveBatchNilai(string $userId, array $nilaiMatrix): int {
        $placeholders = [];
        $bindings     = [];
        $idx          = 0;

        foreach ($nilaiMatrix as $semester => $mapelList) {
            $semInt = (int) $semester;
            if ($semInt < 1 || $semInt > 4 || !is_array($mapelList)) {
                continue;
            }

            foreach ($mapelList as $kodeMapel => $nilai) {
                if ($nilai === '' || $nilai === null) {
                    continue;
                }

                $uKey = ":u{$idx}";
                $sKey = ":s{$idx}";
                $mKey = ":m{$idx}";
                $nKey = ":n{$idx}";

                $placeholders[] = "({$uKey}, {$sKey}, {$mKey}, {$nKey})";
                $bindings[$uKey] = [$userId, PDO::PARAM_STR];
                $bindings[$sKey] = [$semInt, PDO::PARAM_INT];
                $bindings[$mKey] = [(string) $kodeMapel, PDO::PARAM_STR];
                $bindings[$nKey] = [(float) $nilai, PDO::PARAM_STR];
                $idx++;
            }
        }

        if ($idx === 0) {
            return 0;
        }

        $valuesSql = implode(', ', $placeholders);
        $query = "INSERT INTO {$this->table} (
                    user_id,
                    semester,
                    mata_pelajaran,
                    nilai_pengetahuan
                  ) VALUES {$valuesSql}
                  ON DUPLICATE KEY UPDATE
                    nilai_pengetahuan = VALUES(nilai_pengetahuan),
                    status_verifikasi = 'menunggu'";

        $this->db->beginTransaction();
        try {
            $this->db->query($query);
            foreach ($bindings as $param => [$val, $type]) {
                $this->db->bind($param, $val, $type);
            }
            $this->db->execute();
            $this->db->commit();
            return $idx;
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    /**
     * Mengambil semua baris nilai milik user dari tabel nilai_raport
     */
    public function getByUserId(string $userId): array {
        $this->db->query("SELECT semester, mata_pelajaran, nilai_pengetahuan, status_verifikasi
                          FROM {$this->table}
                          WHERE user_id = :user_id
                            AND semester BETWEEN 1 AND 4
                          ORDER BY semester ASC, id ASC");
        $this->db->bind(':user_id', $userId);
        return $this->db->resultSet();
    }

    /**
     * Mengambil nilai milik user yang sudah dipetakan ke matriks 2 dimensi:
     * [semester (1..4)][mata_pelajaran] = nilai_pengetahuan
     */
    public function getMatrixByUserId(string $userId): array {
        $rows = $this->getByUserId($userId);
        $matrix = [
            1 => [],
            2 => [],
            3 => [],
            4 => []
        ];

        foreach ($rows as $row) {
            $sem   = (int) $row['semester'];
            $mapel = $row['mata_pelajaran'];
            if (isset($matrix[$sem])) {
                $matrix[$sem][$mapel] = (float) $row['nilai_pengetahuan'];
            }
        }

        return $matrix;
    }

    /**
     * Menghitung jumlah entri nilai rapor milik user (Semester 1 s.d. 4)
     */
    public function countByUserId(string $userId): int {
        $this->db->query("SELECT COUNT(*) AS total FROM {$this->table} WHERE user_id = :user_id AND semester BETWEEN 1 AND 4");
        $this->db->bind(':user_id', $userId);
        $row = $this->db->single();
        return (int) ($row['total'] ?? 0);
    }

    /**
     * Mengecek apakah Tahap 3 (Input 40 Nilai Rapor: 10 Mapel x 4 Semester) sudah lengkap
     */
    public function isNilaiComplete(string $userId): bool {
        return $this->countByUserId($userId) >= 40;
    }
}
