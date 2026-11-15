<?php
class AdminController extends Controller {
    private $userModel;
    private $biodataModel;
    private $berkasModel;
    private $nilaiModel;
    private $adminModel;
    private $currentAdmin;

    public function __construct() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /auth/login');
            exit;
        }

        $this->userModel    = $this->model('UserModel');
        $this->biodataModel = $this->model('BiodataModel');
        $this->berkasModel  = $this->model('BerkasModel');
        $this->nilaiModel   = $this->model('NilaiModel');
        $this->adminModel   = $this->model('AdminModel');

        try {
            $this->currentAdmin = $this->userModel->findById($_SESSION['user_id']);
        } catch (Exception $e) {
            $this->currentAdmin = null;
        }

        // Jika login menggunakan sesi admin default, gunakan fallback sesi agar tidak error 500
        if (!$this->currentAdmin && (($_SESSION['user_role'] ?? '') === 'admin')) {
            $this->currentAdmin = [
                'id'           => $_SESSION['user_id'],
                'nama_lengkap' => $_SESSION['user_nama'] ?? 'Administrator Panitia SPMB',
                'nisn'         => $_SESSION['user_nisn'] ?? '0000000001',
                'no_hp'        => $_SESSION['user_no_hp'] ?? '081111111111',
                'role'         => 'admin'
            ];
        }

        if (!$this->currentAdmin) {
            session_destroy();
            header('Location: /auth/login');
            exit;
        }

        if (($this->currentAdmin['role'] ?? '') !== 'admin') {
            header('Location: /dashboard');
            exit;
        }
    }

    /**
     * 1. Halaman Utama Dasbor Admin (/admin)
     */
    public function index() {
        $stats            = $this->adminModel->getDashboardStats();
        $pendaftarTerbaru = array_slice($this->adminModel->getAllPendaftar(), 0, 8);
        $berkasMenunggu   = array_slice($this->adminModel->getAllBerkasQueue('menunggu'), 0, 6);

        $data = [
            'title'             => 'Dasbor Admin - SPMB SMAN 5 Taruna Brawijaya',
            'admin'             => $this->currentAdmin,
            'active_menu'       => 'ringkasan',
            'stats'             => $stats,
            'pendaftar_terbaru' => $pendaftarTerbaru,
            'berkas_menunggu'   => $berkasMenunggu,
            'success'           => $_SESSION['flash_success'] ?? null,
            'error'             => $_SESSION['flash_error'] ?? null
        ];
        unset($_SESSION['flash_success'], $_SESSION['flash_error']);

        $this->view('admin/index', $data);
    }

    /**
     * 2. Menu Data Pendaftar / Calon Taruna (/admin/pendaftar)
     */
    public function pendaftar() {
        $search = trim($_GET['q'] ?? '');
        $jalur  = trim($_GET['jalur'] ?? '');

        $pendaftarList = $this->adminModel->getAllPendaftar($search, $jalur);

        $data = [
            'title'       => 'Data Calon Taruna - Admin SPMB SMAN 5 Taruna Brawijaya',
            'admin'       => $this->currentAdmin,
            'active_menu' => 'pendaftar',
            'pendaftar'   => $pendaftarList,
            'filter_q'    => $search,
            'filter_jalur'=> $jalur,
            'success'     => $_SESSION['flash_success'] ?? null,
            'error'       => $_SESSION['flash_error'] ?? null
        ];
        unset($_SESSION['flash_success'], $_SESSION['flash_error']);

        $this->view('admin/pendaftar', $data);
    }

    /**
     * Halaman Detail Lengkap Calon Taruna & Verifikasi (/admin/detail/{userId})
     */
    public function detail($userId = null) {
        if (empty($userId)) {
            header('Location: /admin/pendaftar');
            exit;
        }

        $siswa = $this->userModel->findById($userId);
        if (!$siswa || ($siswa['role'] ?? '') !== 'calon_siswa') {
            $_SESSION['flash_error'] = 'Data calon taruna tidak ditemukan.';
            header('Location: /admin/pendaftar');
            exit;
        }

        $biodata     = $this->biodataModel->findByUserId($userId);
        $berkasList  = $this->berkasModel->getByUserId($userId);
        $nilaiMatrix = $this->nilaiModel->getMatrixByUserId($userId);

        $data = [
            'title'        => 'Detail Pendaftar: ' . $siswa['nama_lengkap'] . ' - Admin SPMB',
            'admin'        => $this->currentAdmin,
            'active_menu'  => 'pendaftar',
            'siswa'        => $siswa,
            'biodata'      => $biodata ?: [],
            'berkas_list'  => $berkasList,
            'mapel_list'   => NilaiModel::MATA_PELAJARAN,
            'nilai_matrix' => $nilaiMatrix,
            'success'      => $_SESSION['flash_success'] ?? null,
            'error'        => $_SESSION['flash_error'] ?? null
        ];
        unset($_SESSION['flash_success'], $_SESSION['flash_error']);

        $this->view('admin/detail', $data);
    }

    /**
     * 3. Menu Verifikasi Berkas (/admin/berkas)
     */
    public function berkas() {
        $status = trim($_GET['status'] ?? '');
        $search = trim($_GET['q'] ?? '');

        $berkasList = $this->adminModel->getAllBerkasQueue($status, $search);
        $stats      = $this->adminModel->getDashboardStats();

        $data = [
            'title'         => 'Verifikasi Berkas Pendaftar - Admin SPMB SMAN 5 Taruna Brawijaya',
            'admin'         => $this->currentAdmin,
            'active_menu'   => 'berkas',
            'berkas_list'   => $berkasList,
            'stats'         => $stats,
            'filter_status' => $status,
            'filter_q'      => $search,
            'success'       => $_SESSION['flash_success'] ?? null,
            'error'         => $_SESSION['flash_error'] ?? null
        ];
        unset($_SESSION['flash_success'], $_SESSION['flash_error']);

        $this->view('admin/berkas', $data);
    }

    /**
     * Aksi POST untuk memverifikasi berkas (valid / perbaikan / ditolak)
     */
    public function verifikasiBerkas() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $berkasId = (int) ($_POST['berkas_id'] ?? 0);
            $status   = trim($_POST['status_verifikasi'] ?? '');
            $catatan  = trim($_POST['catatan_verifikator'] ?? '');
            $redirect = trim($_POST['redirect_to'] ?? '/admin/berkas');

            $allowed = ['menunggu', 'valid', 'perbaikan', 'ditolak'];
            if ($berkasId > 0 && in_array($status, $allowed, true)) {
                $this->adminModel->updateVerifikasiBerkas($berkasId, $status, $catatan);
                $_SESSION['flash_success'] = 'Status verifikasi berkas berhasil diperbarui menjadi "' . strtoupper($status) . '".';
            } else {
                $_SESSION['flash_error'] = 'Data verifikasi berkas tidak valid.';
            }

            header('Location: ' . $redirect);
            exit;
        }

        header('Location: /admin/berkas');
        exit;
    }

    /**
     * Aksi POST untuk mengubah jalur pendaftaran siswa oleh Admin
     */
    public function ubahJalur() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId = trim($_POST['user_id'] ?? '');
            $jalur  = trim($_POST['jalur_pendaftaran'] ?? '');

            if (!empty($userId) && in_array($jalur, ['Jalur Umum', 'Jalur Beasiswa'], true)) {
                $this->userModel->updateJalurPendaftaran($userId, $jalur);
                $_SESSION['flash_success'] = 'Jalur pendaftaran calon taruna berhasil diubah ke ' . $jalur . '.';
                header('Location: /admin/detail/' . $userId);
                exit;
            }
        }
        header('Location: /admin/pendaftar');
        exit;
    }

    /**
     * Aksi POST untuk menghapus data pendaftar oleh Admin
     */
    public function hapusPendaftar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId = trim($_POST['user_id'] ?? '');
            if (!empty($userId)) {
                $this->adminModel->deletePendaftar($userId);
                $_SESSION['flash_success'] = 'Data calon taruna berhasil dihapus dari sistem.';
            }
        }
        header('Location: /admin/pendaftar');
        exit;
    }

    /**
     * 4. Menu Rekap & Peringkat Nilai Rapor (/admin/nilai)
     */
    public function nilai() {
        $jalur   = trim($_GET['jalur'] ?? '');
        $ranking = $this->adminModel->getRankingNilai($jalur);

        $data = [
            'title'        => 'Peringkat & Rekap Nilai Rapor - Admin SPMB SMAN 5 Taruna Brawijaya',
            'admin'        => $this->currentAdmin,
            'active_menu'  => 'nilai',
            'ranking'      => $ranking,
            'filter_jalur' => $jalur
        ];

        $this->view('admin/nilai', $data);
    }

    /**
     * 5. Menu Laporan & Cetak Data (/admin/laporan)
     */
    public function laporan() {
        $jalur     = trim($_GET['jalur'] ?? '');
        $pendaftar = $this->adminModel->getAllPendaftar('', $jalur);
        $stats     = $this->adminModel->getDashboardStats();

        $data = [
            'title'        => 'Laporan Rekapitulasi Pendaftar SPMB - SMAN 5 Taruna Brawijaya',
            'admin'        => $this->currentAdmin,
            'active_menu'  => 'laporan',
            'pendaftar'    => $pendaftar,
            'stats'        => $stats,
            'filter_jalur' => $jalur
        ];

        $this->view('admin/laporan', $data);
    }

    /**
     * Ekspor Data Pendaftar ke format CSV / Excel (/admin/exportCsv)
     */
    public function exportCsv() {
        $jalur     = trim($_GET['jalur'] ?? '');
        $pendaftar = $this->adminModel->getAllPendaftar('', $jalur);

        $filename = 'Data_Pendaftar_SPMB_SMAN5_Taruna_Brawijaya_' . date('Ymd_His') . '.csv';
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        $out = fopen('php://output', 'w');
        // Tambahkan UTF-8 BOM agar terbaca rapi di Microsoft Excel
        fprintf($out, chr(0xEF) . chr(0xBB) . chr(0xBF));

        fputcsv($out, [
            'No',
            'NIK',
            'NISN',
            'Nama Lengkap',
            'Jenis Kelamin',
            'Tempat Lahir',
            'Tanggal Lahir',
            'Asal Sekolah',
            'Tahun Lulus',
            'Jalur Pendaftaran',
            'No HP',
            'Email',
            'Kabupaten/Kota',
            'Jumlah Berkas',
            'Berkas Valid',
            'Rata-Rata Nilai Rapor (Sem 1-4)',
            'Tanggal Daftar'
        ]);

        foreach ($pendaftar as $idx => $row) {
            fputcsv($out, [
                $idx + 1,
                "'" . ($row['nik'] ?? ''),
                "'" . ($row['nisn'] ?? ''),
                $row['nama_lengkap'] ?? '',
                $row['jenis_kelamin'] ?? '-',
                $row['tempat_lahir'] ?? '-',
                $row['tanggal_lahir'] ?? '-',
                $row['nama_sekolah_asal'] ?? '-',
                $row['tahun_lulus'] ?? '-',
                $row['jalur_pendaftaran'] ?? 'Belum Pilih',
                "'" . ($row['no_hp'] ?? ''),
                $row['email_ortu'] ?? '',
                $row['kabupaten_kota'] ?? '-',
                (int) ($row['jumlah_berkas'] ?? 0),
                (int) ($row['berkas_valid'] ?? 0),
                $row['rata_nilai'] ?? '0.00',
                !empty($row['created_at']) ? date('d-m-Y H:i', strtotime($row['created_at'])) : '-'
            ]);
        }

        fclose($out);
        exit;
    }
}
