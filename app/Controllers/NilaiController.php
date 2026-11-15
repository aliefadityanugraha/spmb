<?php
class NilaiController extends Controller {
    private $userModel;
    private $biodataModel;
    private $berkasModel;
    private $nilaiModel;

    public function __construct() {
        // Proteksi autentikasi: wajib login terlebih dahulu
        if (!isset($_SESSION['user_id'])) {
            header('Location: /auth/login');
            exit;
        }

        $this->userModel    = $this->model('UserModel');
        $this->biodataModel = $this->model('BiodataModel');
        $this->berkasModel  = $this->model('BerkasModel');
        $this->nilaiModel   = $this->model('NilaiModel');
    }

    /**
     * Memastikan Tahap 1 (Biodata) dan Tahap 2 (Upload Berkas) sudah selesai sebelum mengakses Tahap 3 (Input Nilai)
     */
    private function enforcePrerequisites(string $userId): void {
        $biodata = $this->biodataModel->findByUserId($userId);
        if (!$this->biodataModel->isBiodataComplete($biodata)) {
            $_SESSION['flash_error'] = 'Tahap 3 (Input Nilai) masih terkunci. Silakan lengkapi dan simpan Biodata Diri beserta Pas Foto 3x4 (Tahap 1) terlebih dahulu.';
            header('Location: /biodata');
            exit;
        }

        if (!$this->berkasModel->isDokumenPokokComplete($userId)) {
            $_SESSION['flash_error'] = 'Tahap 3 (Input Nilai) masih terkunci. Silakan unggah ke-4 berkas persyaratan pokok (KK, Surat Sehat, Kelakuan Baik, dan Ijazah/SKL) pada Tahap 2 terlebih dahulu.';
            header('Location: /berkas');
            exit;
        }
    }

    /**
     * Menampilkan halaman form Input Nilai Raport Semester 1 - 4 (10 Mata Pelajaran)
     */
    public function index() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->simpan();
            return;
        }

        $user = $this->userModel->findById($_SESSION['user_id']);
        if (!$user) {
            session_destroy();
            header('Location: /auth/login');
            exit;
        }

        // Jika belum memilih jalur pendaftaran, arahkan ke dasbor
        if (empty($user['jalur_pendaftaran'])) {
            header('Location: /dashboard');
            exit;
        }

        // Proteksi Berurutan: Wajib menyelesaikan Tahap 1 & Tahap 2 terlebih dahulu
        $this->enforcePrerequisites($user['id']);

        $matrixTersimpan = $this->nilaiModel->getMatrixByUserId($user['id']);
        $sudahAdaNilai   = false;
        foreach ($matrixTersimpan as $semData) {
            if (!empty($semData)) {
                $sudahAdaNilai = true;
                break;
            }
        }

        // Jika ada input lama karena gagal validasi, timpa ke matrix tampilan
        if (!empty($_SESSION['nilai_old']) && is_array($_SESSION['nilai_old'])) {
            foreach ($_SESSION['nilai_old'] as $sem => $mapels) {
                if (isset($matrixTersimpan[(int)$sem]) && is_array($mapels)) {
                    $matrixTersimpan[(int)$sem] = array_merge($matrixTersimpan[(int)$sem], $mapels);
                }
            }
        }

        $data = [
            'title'          => 'Input Nilai Raport (Semester 1 - 4) - SPMB SMAN 5 Taruna Brawijaya',
            'user'           => $user,
            'mapel_list'     => NilaiModel::MATA_PELAJARAN,
            'nilai_matrix'   => $matrixTersimpan,
            'is_edit_mode'   => $sudahAdaNilai,
            'errors'         => $_SESSION['nilai_errors'] ?? [],
            'success'        => $_SESSION['flash_success'] ?? null,
            'error'          => $_SESSION['flash_error'] ?? null
        ];

        unset($_SESSION['nilai_errors'], $_SESSION['nilai_old'], $_SESSION['flash_success'], $_SESSION['flash_error']);

        $this->view('dashboard/nilai', $data);
    }

    /**
     * Memproses penyimpanan array nilai 10 mata pelajaran untuk Semester 1 s.d. 4
     */
    public function simpan() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /nilai');
            exit;
        }

        $userId     = $_SESSION['user_id'];
        $this->enforcePrerequisites($userId);
        $inputNilai = $_POST['nilai'] ?? [];
        $mapelValid = array_keys(NilaiModel::MATA_PELAJARAN);

        $errors      = [];
        $cleanMatrix = [1 => [], 2 => [], 3 => [], 4 => []];

        for ($sem = 1; $sem <= 4; $sem++) {
            foreach ($mapelValid as $kodeMapel) {
                $rawVal = isset($inputNilai[$sem][$kodeMapel]) ? trim((string) $inputNilai[$sem][$kodeMapel]) : '';

                if ($rawVal === '') {
                    $errors["{$sem}_{$kodeMapel}"] = "Nilai {$kodeMapel} Semester {$sem} wajib diisi.";
                    continue;
                }

                // Ganti koma dengan titik jika user mengetik desimal dengan koma
                $normalized = str_replace(',', '.', $rawVal);

                if (!is_numeric($normalized)) {
                    $errors["{$sem}_{$kodeMapel}"] = "Nilai {$kodeMapel} Semester {$sem} harus berupa angka.";
                    continue;
                }

                $floatVal = (float) $normalized;
                if ($floatVal < 0 || $floatVal > 100) {
                    $errors["{$sem}_{$kodeMapel}"] = "Nilai {$kodeMapel} Semester {$sem} harus berada di rentang 0 - 100.";
                    continue;
                }

                $cleanMatrix[$sem][$kodeMapel] = round($floatVal, 2);
            }
        }

        if (!empty($errors)) {
            $_SESSION['nilai_errors'] = $errors;
            $_SESSION['nilai_old']    = $inputNilai;
            $_SESSION['flash_error']  = 'Terdapat nilai yang belum lengkap atau di luar rentang 0 - 100. Silakan periksa setiap tab Semester 1 hingga Semester 4.';
            header('Location: /nilai');
            exit;
        }

        try {
            $totalTersimpan = $this->nilaiModel->saveBatchNilai($userId, $cleanMatrix);
            $_SESSION['flash_success'] = "Berhasil menyimpan {$totalTersimpan} entri nilai rapor (10 Mata Pelajaran untuk Semester 1 s.d. 4)!";
        } catch (Exception $e) {
            $_SESSION['nilai_old']   = $inputNilai;
            $_SESSION['flash_error'] = 'Terjadi kesalahan saat menyimpan nilai rapor: ' . $e->getMessage();
        }

        header('Location: /nilai');
        exit;
    }
}
