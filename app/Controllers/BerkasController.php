<?php
require_once '../app/Services/UploadService.php';

class BerkasController extends Controller {
    private $userModel;
    private $biodataModel;
    private $berkasModel;
    private $uploadService;

    public function __construct() {
        // Wajib login terlebih dahulu
        if (!isset($_SESSION['user_id'])) {
            header('Location: /auth/login');
            exit;
        }

        $this->userModel     = $this->model('UserModel');
        $this->biodataModel  = $this->model('BiodataModel');
        $this->berkasModel   = $this->model('BerkasModel');
        $this->uploadService = new UploadService();
    }

    /**
     * Menampilkan halaman form Upload Berkas (Wajib menyelesaikan Tahap 1: Isi Biodata terlebih dahulu)
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

        // Proteksi Berurutan: Wajib menyelesaikan Tahap 1 (Isi Biodata & Pas Foto) sebelum Tahap 2 (Upload Berkas)
        $biodata = $this->biodataModel->findByUserId($user['id']);
        if (!$this->biodataModel->isBiodataComplete($biodata)) {
            $_SESSION['flash_error'] = 'Tahap 2 (Upload Berkas) masih terkunci. Silakan lengkapi dan simpan Biodata Diri beserta Pas Foto 3x4 (Tahap 1) terlebih dahulu.';
            header('Location: /biodata');
            exit;
        }

        // Sinkronkan session jalur_pendaftaran
        $_SESSION['user_jalur'] = $user['jalur_pendaftaran'];
        $isBeasiswa = (stripos($user['jalur_pendaftaran'], 'Beasiswa') !== false);

        $berkasGrouped = $this->berkasModel->getGroupedByUserId($user['id']);

        $data = [
            'title'       => 'Upload Berkas Persyaratan - SPMB SMAN 5 Taruna Brawijaya',
            'user'        => $user,
            'is_beasiswa' => $isBeasiswa,
            'berkas'      => $berkasGrouped,
            'errors'      => $_SESSION['berkas_errors'] ?? [],
            'success'     => $_SESSION['flash_success'] ?? null,
            'error'       => $_SESSION['flash_error'] ?? null
        ];
        unset($_SESSION['berkas_errors'], $_SESSION['flash_success'], $_SESSION['flash_error']);

        $this->view('dashboard/upload_berkas', $data);
    }

    /**
     * Memproses penyimpanan berkas wajib & sertifikat (khusus jalur Beasiswa)
     */
    public function simpan() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /berkas');
            exit;
        }

        $user = $this->userModel->findById($_SESSION['user_id']);
        if (!$user || empty($user['jalur_pendaftaran'])) {
            header('Location: /dashboard');
            exit;
        }

        // Proteksi Berurutan: Pastikan Tahap 1 (Biodata) sudah lengkap
        $biodata = $this->biodataModel->findByUserId($user['id']);
        if (!$this->biodataModel->isBiodataComplete($biodata)) {
            $_SESSION['flash_error'] = 'Tahap 2 (Upload Berkas) masih terkunci. Silakan lengkapi dan simpan Biodata Diri (Tahap 1) terlebih dahulu.';
            header('Location: /biodata');
            exit;
        }

        $userId     = $user['id'];
        $isBeasiswa = (stripos($user['jalur_pendaftaran'], 'Beasiswa') !== false);
        $errors     = [];
        $uploadedCount = 0;

        // 1. Daftar 4 Dokumen Utama
        $dokumenUtama = [
            'kk'               => 'Kartu Keluarga (KK)',
            'keterangan_sehat' => 'Surat Keterangan Sehat',
            'kelakuan_baik'    => 'Surat Keterangan Kelakuan Baik',
            'ijazah'           => 'Ijazah / SKL'
        ];

        foreach ($dokumenUtama as $field => $label) {
            if (isset($_FILES[$field]) && $_FILES[$field]['error'] !== UPLOAD_ERR_NO_FILE) {
                $result = $this->uploadService->upload($_FILES[$field], $field, $userId);

                if (!$result['success']) {
                    $errors[$field] = $label . ': ' . $result['error'];
                } else {
                    $oldPath = $this->berkasModel->saveOrUpdateDokumenUtama([
                        'user_id'      => $userId,
                        'jenis_berkas' => $field,
                        'nama_berkas'  => $label . ' - ' . $result['data']['nama_berkas'],
                        'file_path'    => $result['data']['file_path'],
                        'mime_type'    => $result['data']['mime_type'],
                        'ukuran_kb'    => $result['data']['ukuran_kb']
                    ]);

                    // Hapus file fisik lama jika user mengganti dokumen
                    if ($oldPath) {
                        $this->uploadService->deleteFile($oldPath);
                    }
                    $uploadedCount++;
                }
            }
        }

        // 2. Proses Sertifikat Prestasi (Wajib/Utama pada Jalur Beasiswa, Opsional pada Jalur Umum, maks 5 file)
        if (isset($_FILES['sertifikat_file']) && is_array($_FILES['sertifikat_file']['name'])) {
            $existingSertifikatCount = $this->berkasModel->countSertifikatByUserId($userId);
            $sisaKuota               = max(0, 5 - $existingSertifikatCount);

            $files    = $_FILES['sertifikat_file'];
            $tingkats = $_POST['sertifikat_tingkat'] ?? [];
            $namas    = $_POST['sertifikat_nama'] ?? [];
            $allowedTingkat = ['Kota', 'Provinsi', 'Nasional'];

            $jumlahFileBaru = 0;
            foreach ($files['error'] as $err) {
                if ($err !== UPLOAD_ERR_NO_FILE) {
                    $jumlahFileBaru++;
                }
            }

            if ($jumlahFileBaru > $sisaKuota) {
                $errors['sertifikat'] = "Maksimal total sertifikat adalah 5 file. Anda sudah mengunggah {$existingSertifikatCount} sertifikat (tersisa kuota {$sisaKuota} sertifikat).";
            } elseif ($jumlahFileBaru > 0) {
                $totalRows = count($files['name']);
                for ($i = 0; $i < $totalRows; $i++) {
                    if ($files['error'][$i] === UPLOAD_ERR_NO_FILE) {
                        continue;
                    }

                    $tingkat = trim($tingkats[$i] ?? '');
                    $ketNama = trim($namas[$i] ?? '');

                    if (!in_array($tingkat, $allowedTingkat, true)) {
                        $errors['sertifikat'] = 'Tingkat sertifikat ke-' . ($i + 1) . ' wajib dipilih (Kota / Provinsi / Nasional).';
                        continue;
                    }

                    $singleFile = [
                        'name'     => $files['name'][$i],
                        'type'     => $files['type'][$i],
                        'tmp_name' => $files['tmp_name'][$i],
                        'error'    => $files['error'][$i],
                        'size'     => $files['size'][$i]
                    ];

                    $result = $this->uploadService->upload($singleFile, 'sertifikat_' . strtolower($tingkat), $userId);
                    if (!$result['success']) {
                        $errors['sertifikat'] = 'Sertifikat ke-' . ($i + 1) . ': ' . $result['error'];
                    } else {
                        $judulSertifikat = !empty($ketNama)
                            ? $ketNama
                            : ('Sertifikat Tingkat ' . $tingkat . ' - ' . $result['data']['nama_berkas']);

                        $this->berkasModel->insert([
                            'user_id'          => $userId,
                            'jenis_berkas'     => 'sertifikat',
                            'nama_berkas'      => $judulSertifikat,
                            'file_path'        => $result['data']['file_path'],
                            'mime_type'        => $result['data']['mime_type'],
                            'ukuran_kb'        => $result['data']['ukuran_kb'],
                            'tingkat'          => $tingkat,
                            'juara'            => null,
                            'tahun_sertifikat' => null
                        ]);
                        $uploadedCount++;
                    }
                }
            }
        }

        if (!empty($errors)) {
            $_SESSION['berkas_errors'] = $errors;
            $_SESSION['flash_error']   = 'Beberapa berkas gagal diunggah. Silakan periksa pesan kesalahan di bawah.';
        } elseif ($uploadedCount > 0) {
            $_SESSION['flash_success'] = "Berhasil mengunggah dan menyimpan {$uploadedCount} berkas dokumen!";
        } else {
            $_SESSION['flash_error'] = 'Belum ada file baru yang Anda pilih untuk diunggah.';
        }

        header('Location: /berkas');
        exit;
    }

    /**
     * Menghapus sertifikat prestasi tertentu milik user
     */
    public function hapus($id = null) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($id)) {
            $userId = $_SESSION['user_id'];
            $berkas = $this->berkasModel->findByIdAndUser((int) $id, $userId);

            if ($berkas && $berkas['jenis_berkas'] === 'sertifikat') {
                $this->uploadService->deleteFile($berkas['file_path']);
                $this->berkasModel->deleteByIdAndUser((int) $id, $userId);
                $_SESSION['flash_success'] = 'Sertifikat berhasil dihapus.';
            }
        }

        header('Location: /berkas');
        exit;
    }
}
