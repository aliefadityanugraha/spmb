<?php
class CetakController extends Controller {
    private $userModel;
    private $biodataModel;
    private $nilaiModel;
    private $berkasModel;

    public function __construct() {
        // Proteksi autentikasi: wajib login terlebih dahulu
        if (!isset($_SESSION['user_id'])) {
            header('Location: /auth/login');
            exit;
        }

        $this->userModel    = $this->model('UserModel');
        $this->biodataModel = $this->model('BiodataModel');
        $this->nilaiModel   = $this->model('NilaiModel');
        $this->berkasModel  = $this->model('BerkasModel');
    }

    /**
     * Default route /cetak -> arahkan ke rekapNilai
     */
    public function index() {
        $this->rekapNilai();
    }

    /**
     * Method rekapNilai():
     * Mengambil semua data nilai dari NilaiModel, menghitung total dan rata-ratanya,
     * lalu menampilkan dalam bentuk tabel rekapitulasi beserta surat pernyataan.
     */
    public function rekapNilai() {
        $user = $this->userModel->findById($_SESSION['user_id']);
        if (!$user) {
            session_destroy();
            header('Location: /auth/login');
            exit;
        }

        if (empty($user['jalur_pendaftaran'])) {
            header('Location: /dashboard');
            exit;
        }

        $biodata     = $this->biodataModel->findByUserId($user['id']);

        // Proteksi Berurutan Tahap 4 (Cetak Rekap): Wajib menyelesaikan Tahap 1 (Biodata), Tahap 2 (Berkas), dan Tahap 3 (Nilai)
        if (!$this->biodataModel->isBiodataComplete($biodata)) {
            $_SESSION['flash_error'] = 'Tahap 4 (Cetak Rekap) masih terkunci. Silakan lengkapi Biodata Diri (Tahap 1) terlebih dahulu.';
            header('Location: /biodata');
            exit;
        }

        if (!$this->berkasModel->isDokumenPokokComplete($user['id'])) {
            $_SESSION['flash_error'] = 'Tahap 4 (Cetak Rekap) masih terkunci. Silakan unggah 4 berkas persyaratan pokok (Tahap 2) terlebih dahulu.';
            header('Location: /berkas');
            exit;
        }

        $mapelList   = NilaiModel::MATA_PELAJARAN;
        $nilaiMatrix = $this->nilaiModel->getMatrixByUserId($user['id']);

        // Hitung total & rata-rata per semester serta per mata pelajaran
        $totalPerSemester = [1 => 0.0, 2 => 0.0, 3 => 0.0, 4 => 0.0];
        $countPerSemester = [1 => 0, 2 => 0, 3 => 0, 4 => 0];
        $rataPerSemester  = [1 => 0.0, 2 => 0.0, 3 => 0.0, 4 => 0.0];
        $rataPerMapel     = [];

        $grandTotal = 0.0;
        $grandCount = 0;

        foreach ($mapelList as $kodeMapel => $namaMapel) {
            $sumMapel   = 0.0;
            $countMapel = 0;

            for ($sem = 1; $sem <= 4; $sem++) {
                if (isset($nilaiMatrix[$sem][$kodeMapel]) && $nilaiMatrix[$sem][$kodeMapel] !== '') {
                    $val = (float) $nilaiMatrix[$sem][$kodeMapel];
                    $totalPerSemester[$sem] += $val;
                    $countPerSemester[$sem]++;

                    $sumMapel += $val;
                    $countMapel++;

                    $grandTotal += $val;
                    $grandCount++;
                }
            }

            $rataPerMapel[$kodeMapel] = $countMapel > 0 ? round($sumMapel / $countMapel, 2) : 0.0;
        }

        if ($grandCount < 40) {
            $_SESSION['flash_error'] = 'Tahap 4 (Cetak Rekap) masih terkunci. Silakan lengkapi dan simpan seluruh Nilai Rapor Semester 1 s.d. Semester 4 (40 nilai) pada Tahap 3 terlebih dahulu.';
            header('Location: /nilai');
            exit;
        }

        // Tandai bahwa Tahap 4 (Cetak Rekap Nilai) sudah diselesaikan sehingga Tahap 5 (Cetak Kartu) terbuka
        $this->biodataModel->markRekapSelesai($user['id']);
        $_SESSION['rekap_selesai'] = true;

        for ($sem = 1; $sem <= 4; $sem++) {
            $rataPerSemester[$sem] = $countPerSemester[$sem] > 0
                ? round($totalPerSemester[$sem] / $countPerSemester[$sem], 2)
                : 0.0;
        }

        $grandAverage = $grandCount > 0 ? round($grandTotal / $grandCount, 2) : 0.0;
        $nomorPeserta = $this->generateNomorPeserta($user);

        $data = [
            'title'              => 'Cetak Rekapitulasi Nilai & Surat Pernyataan - SPMB SMAN 5 Taruna Brawijaya',
            'user'               => $user,
            'biodata'            => $biodata ?: [],
            'nomor_peserta'      => $nomorPeserta,
            'mapel_list'         => $mapelList,
            'nilai_matrix'       => $nilaiMatrix,
            'total_per_semester' => $totalPerSemester,
            'rata_per_semester'  => $rataPerSemester,
            'rata_per_mapel'     => $rataPerMapel,
            'grand_total'        => $grandTotal,
            'grand_average'      => $grandAverage,
            'error'              => $_SESSION['flash_error'] ?? null
        ];
        unset($_SESSION['flash_error']);

        $this->view('dashboard/cetak_rekap', $data);
    }

    /**
     * Alias /cetak/rekap -> rekapNilai()
     */
    public function rekap() {
        $this->rekapNilai();
    }

    /**
     * Method kartuPeserta():
     * Mengambil data dari UserModel dan BiodataModel, menampilkan dalam desain
     * kartu peserta yang rapi, berisikan nomor peserta, nama, asal sekolah,
     * dan daftar checklist berkas fisik.
     */
    public function kartuPeserta() {
        $user = $this->userModel->findById($_SESSION['user_id']);
        if (!$user) {
            session_destroy();
            header('Location: /auth/login');
            exit;
        }

        if (empty($user['jalur_pendaftaran'])) {
            header('Location: /dashboard');
            exit;
        }

        $biodata       = $this->biodataModel->findByUserId($user['id']);
        $berkasGrouped = $this->berkasModel->getGroupedByUserId($user['id']);

        // Proteksi Berurutan Tahap 5 (Cetak Kartu): Wajib menyelesaikan Tahap 1, 2, 3, dan 4 terlebih dahulu
        if (!$this->biodataModel->isBiodataComplete($biodata)) {
            $_SESSION['flash_error'] = 'Tahap 5 (Cetak Kartu) masih terkunci. Silakan lengkapi Biodata Diri (Tahap 1) terlebih dahulu.';
            header('Location: /biodata');
            exit;
        }

        if (!$this->berkasModel->isDokumenPokokComplete($user['id'], $berkasGrouped)) {
            $_SESSION['flash_error'] = 'Tahap 5 (Cetak Kartu) masih terkunci. Silakan unggah 4 berkas persyaratan pokok (Tahap 2) terlebih dahulu.';
            header('Location: /berkas');
            exit;
        }

        if (!$this->nilaiModel->isNilaiComplete($user['id'])) {
            $_SESSION['flash_error'] = 'Tahap 5 (Cetak Kartu) masih terkunci. Silakan lengkapi seluruh Nilai Rapor Semester 1–4 (Tahap 3) terlebih dahulu.';
            header('Location: /nilai');
            exit;
        }

        if (empty($biodata['status_finalisasi']) && empty($_SESSION['rekap_selesai'])) {
            $_SESSION['flash_error'] = 'Tahap 5 (Cetak Kartu) masih terkunci. Silakan buka dan cetak Lembar Rekapitulasi Nilai Rapor (Tahap 4) terlebih dahulu sebelum mencetak Kartu Peserta.';
            header('Location: /cetak/rekap');
            exit;
        }

        $nomorPeserta  = $this->generateNomorPeserta($user);

        $data = [
            'title'         => 'Cetak Kartu Peserta SPMB - SMAN 5 Taruna Brawijaya',
            'user'          => $user,
            'biodata'       => $biodata ?: [],
            'berkas'        => $berkasGrouped,
            'nomor_peserta' => $nomorPeserta
        ];

        $this->view('dashboard/cetak_kartu', $data);
    }

    /**
     * Alias /cetak/kartu -> kartuPeserta()
     */
    public function kartu() {
        $this->kartuPeserta();
    }

    /**
     * Membuat Nomor Peserta resmi berdasarkan Tahun, Kode Jalur, dan NISN
     */
    private function generateNomorPeserta(array $user): string {
        $kodeJalur = (stripos($user['jalur_pendaftaran'] ?? '', 'Beasiswa') !== false) ? 'BSW' : 'UMM';
        $nisn      = $user['nisn'] ?? '0000000000';
        $suffixId  = strtoupper(substr(str_replace('-', '', $user['id'] ?? ''), 0, 4));
        return sprintf('TB-%s-%s-%s-%s', date('Y'), $kodeJalur, substr($nisn, -6), $suffixId);
    }
}
