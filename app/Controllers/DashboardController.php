<?php
class DashboardController extends Controller {
    private $userModel;
    private $biodataModel;
    private $berkasModel;
    private $nilaiRaportModel;

    public function __construct() {
        // Proteksi autentikasi: wajib login terlebih dahulu
        if (!isset($_SESSION['user_id'])) {
            header('Location: /auth/login');
            exit;
        }

        $this->userModel        = $this->model('UserModel');
        $this->biodataModel     = $this->model('BiodataModel');
        $this->berkasModel      = $this->model('BerkasModel');
        $this->nilaiRaportModel = $this->model('NilaiRaportModel');
    }

    /**
     * Halaman Utama Dasbor Calon Taruna
     * Jika jalur_pendaftaran masih NULL, paksa pengguna memilih 'Jalur Umum' atau 'Jalur Beasiswa'.
     */
    public function index() {
        $user = $this->userModel->findById($_SESSION['user_id']);
        if (!$user) {
            session_destroy();
            header('Location: /auth/login');
            exit;
        }

        $wajibPilihJalur = empty($user['jalur_pendaftaran']);

        // Ambil ringkasan progres pendaftaran berurutan jika jalur sudah dipilih
        $biodata       = !$wajibPilihJalur ? $this->biodataModel->findByUserId($user['id']) : null;
        $berkasList    = !$wajibPilihJalur ? $this->berkasModel->getByUserId($user['id']) : [];
        $berkasGrouped = !$wajibPilihJalur ? $this->berkasModel->getGroupedByUserId($user['id']) : [];
        $nilaiList     = !$wajibPilihJalur ? $this->nilaiRaportModel->getByUserId($user['id']) : [];

        $step1Done = !$wajibPilihJalur && $this->biodataModel->isBiodataComplete($biodata);
        $step2Done = $step1Done && $this->berkasModel->isDokumenPokokComplete($user['id'], $berkasGrouped);
        $step3Done = $step2Done && (is_array($nilaiList) && count($nilaiList) >= 40);
        $step4Done = $step3Done && (!empty($biodata['status_finalisasi']) || !empty($_SESSION['rekap_selesai']));

        $data = [
            'title'            => 'Dasbor Calon Taruna - SPMB SMAN 5 Taruna Brawijaya',
            'user'             => $user,
            'wajib_pilih_jalur'=> $wajibPilihJalur,
            'active_menu'      => 'dashboard',
            'stats'            => [
                'biodata_lengkap' => $step1Done,
                'berkas_lengkap'  => $step2Done,
                'nilai_lengkap'   => $step3Done,
                'rekap_selesai'   => $step4Done,
                'jumlah_berkas'   => is_array($berkasList) ? count($berkasList) : 0,
                'jumlah_nilai'    => is_array($nilaiList) ? count($nilaiList) : 0,
                'finalisasi'      => $step4Done
            ],
            'success'          => $_SESSION['flash_success'] ?? null,
            'error'            => $_SESSION['flash_error'] ?? null
        ];
        unset($_SESSION['flash_success'], $_SESSION['flash_error']);

        $this->view('dashboard/index', $data);
    }

    /**
     * Menyimpan pilihan 'Jalur Umum' atau 'Jalur Beasiswa' ke tabel users
     */
    public function simpanJalur() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /dashboard');
            exit;
        }

        $jalurPilihan = trim($_POST['jalur_pendaftaran'] ?? '');
        $jalurValid   = ['Jalur Umum', 'Jalur Beasiswa'];

        if (!in_array($jalurPilihan, $jalurValid, true)) {
            $_SESSION['flash_error'] = 'Silakan pilih salah satu jalur pendaftaran yang valid (Jalur Umum atau Jalur Beasiswa).';
            header('Location: /dashboard');
            exit;
        }

        $updated = $this->userModel->updateJalurPendaftaran($_SESSION['user_id'], $jalurPilihan);

        if ($updated > 0) {
            $_SESSION['user_jalur']    = $jalurPilihan;
            $_SESSION['flash_success'] = 'Jalur pendaftaran (' . $jalurPilihan . ') berhasil disimpan. Silakan lengkapi tahapan pendaftaran Anda.';
        } else {
            $_SESSION['flash_error'] = 'Gagal menyimpan pilihan jalur pendaftaran. Silakan coba lagi.';
        }

        header('Location: /dashboard');
        exit;
    }

    /**
     * Alias untuk backward-compatibility form action
     */
    public function updateJalur() {
        $this->simpanJalur();
    }

    /**
     * Redirect /dashboard/biodata ke /biodata
     */
    public function biodata() {
        header('Location: /biodata');
        exit;
    }

    /**
     * Redirect /dashboard/berkas ke /berkas
     */
    public function berkas() {
        header('Location: /berkas');
        exit;
    }

    /**
     * Redirect /dashboard/nilai ke /nilai
     */
    public function nilai() {
        header('Location: /nilai');
        exit;
    }

    /**
     * Redirect /dashboard/cetakRekap ke /cetak/rekapNilai
     */
    public function cetakRekap() {
        header('Location: /cetak/rekapNilai');
        exit;
    }

    /**
     * Redirect /dashboard/cetakKartu ke /cetak/kartuPeserta
     */
    public function cetakKartu() {
        header('Location: /cetak/kartuPeserta');
        exit;
    }
}
