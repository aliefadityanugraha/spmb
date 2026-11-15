<?php
require_once '../app/Services/UploadService.php';

class BiodataController extends Controller {
    private $userModel;
    private $biodataModel;
    private $uploadService;

    public function __construct() {
        // Wajib login terlebih dahulu
        if (!isset($_SESSION['user_id'])) {
            header('Location: /auth/login');
            exit;
        }

        $this->userModel     = $this->model('UserModel');
        $this->biodataModel  = $this->model('BiodataModel');
        $this->uploadService = new UploadService();
    }

    /**
     * Menampilkan halaman form Biodata (Mode Edit jika sudah pernah diisi)
     * dan juga menangani submit jika dikirim langsung ke /biodata
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

        // Jika belum memilih jalur pendaftaran, arahkan ke dasbor untuk memilih jalur
        if (empty($user['jalur_pendaftaran'])) {
            header('Location: /dashboard');
            exit;
        }

        $existingBiodata = $this->biodataModel->findByUserId($user['id']);
        $isEditMode      = !empty($existingBiodata) && !empty($existingBiodata['tempat_lahir']);

        // Gabungkan data default dari tabel users jika biodata belum pernah dilengkapi
        $formValues = [
            'nama_lengkap'      => $existingBiodata['nama_lengkap'] ?? $user['nama_lengkap'] ?? '',
            'tempat_lahir'      => $existingBiodata['tempat_lahir'] ?? '',
            'tanggal_lahir'     => $existingBiodata['tanggal_lahir'] ?? '',
            'jenis_kelamin'     => $existingBiodata['jenis_kelamin'] ?? '',
            'agama'             => $existingBiodata['agama'] ?? '',
            'alamat_lengkap'    => $existingBiodata['alamat_lengkap'] ?? '',
            'rt'                => $existingBiodata['rt'] ?? '',
            'rw'                => $existingBiodata['rw'] ?? '',
            'kelurahan_desa'    => $existingBiodata['kelurahan_desa'] ?? '',
            'kecamatan'         => $existingBiodata['kecamatan'] ?? '',
            'kabupaten_kota'    => $existingBiodata['kabupaten_kota'] ?? '',
            'provinsi'          => $existingBiodata['provinsi'] ?? 'Jawa Timur',
            'kode_pos'          => $existingBiodata['kode_pos'] ?? '',
            'latitude'          => $existingBiodata['latitude'] ?? '',
            'longitude'         => $existingBiodata['longitude'] ?? '',
            'npsn_sekolah_asal' => $existingBiodata['npsn_sekolah_asal'] ?? '',
            'nama_sekolah_asal' => $existingBiodata['nama_sekolah_asal'] ?? '',
            'tahun_lulus'       => $existingBiodata['tahun_lulus'] ?? $user['tahun_lulus'] ?? date('Y'),
            'nama_ayah'         => $existingBiodata['nama_ayah'] ?? '',
            'pekerjaan_ayah'    => $existingBiodata['pekerjaan_ayah'] ?? '',
            'nama_ibu'          => $existingBiodata['nama_ibu'] ?? '',
            'pekerjaan_ibu'     => $existingBiodata['pekerjaan_ibu'] ?? '',
            'nama_wali'         => $existingBiodata['nama_wali'] ?? '',
            'no_hp_ortu'        => $existingBiodata['no_hp_ortu'] ?? $user['no_hp'] ?? '',
            'penghasilan_ortu'  => $existingBiodata['penghasilan_ortu'] ?? '',
            'pas_foto'          => $existingBiodata['pas_foto'] ?? '',
            'updated_at'        => $existingBiodata['updated_at'] ?? null
        ];

        $data = [
            'title'        => 'Biodata Calon Taruna - SPMB SMAN 5 Taruna Brawijaya',
            'user'         => $user,
            'biodata'      => $formValues,
            'is_edit_mode' => $isEditMode,
            'errors'       => $_SESSION['biodata_errors'] ?? [],
            'success'      => $_SESSION['flash_success'] ?? null,
            'error'        => $_SESSION['flash_error'] ?? null
        ];

        // Jika ada input lama karena error validasi sebelumnya, timpa ke formValues
        if (!empty($_SESSION['biodata_old'])) {
            $data['biodata'] = array_merge($formValues, $_SESSION['biodata_old']);
        }

        unset($_SESSION['biodata_errors'], $_SESSION['biodata_old'], $_SESSION['flash_success'], $_SESSION['flash_error']);

        $this->view('dashboard/biodata', $data);
    }

    /**
     * Memproses penyimpanan (Insert baru atau Update data lama) beserta Upload Pas Foto ke tabel biodata
     */
    public function simpan() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /biodata');
            exit;
        }

        $userId          = $_SESSION['user_id'];
        $existingBiodata = $this->biodataModel->findByUserId($userId);
        $oldPasFoto      = $existingBiodata['pas_foto'] ?? null;

        $input = [
            'nama_lengkap'      => trim($_POST['nama_lengkap'] ?? ''),
            'tempat_lahir'      => trim($_POST['tempat_lahir'] ?? ''),
            'tanggal_lahir'     => trim($_POST['tanggal_lahir'] ?? ''),
            'jenis_kelamin'     => trim($_POST['jenis_kelamin'] ?? ''),
            'agama'             => trim($_POST['agama'] ?? ''),
            'alamat_lengkap'    => trim($_POST['alamat_lengkap'] ?? ''),
            'rt'                => trim($_POST['rt'] ?? ''),
            'rw'                => trim($_POST['rw'] ?? ''),
            'kelurahan_desa'    => trim($_POST['kelurahan_desa'] ?? ''),
            'kecamatan'         => trim($_POST['kecamatan'] ?? ''),
            'kabupaten_kota'    => trim($_POST['kabupaten_kota'] ?? ''),
            'provinsi'          => trim($_POST['provinsi'] ?? ''),
            'kode_pos'          => trim($_POST['kode_pos'] ?? ''),
            'latitude'          => trim($_POST['latitude'] ?? ''),
            'longitude'         => trim($_POST['longitude'] ?? ''),
            'npsn_sekolah_asal' => trim($_POST['npsn_sekolah_asal'] ?? ''),
            'nama_sekolah_asal' => trim($_POST['nama_sekolah_asal'] ?? ''),
            'tahun_lulus'       => trim($_POST['tahun_lulus'] ?? ''),
            'nama_ayah'         => trim($_POST['nama_ayah'] ?? ''),
            'pekerjaan_ayah'    => trim($_POST['pekerjaan_ayah'] ?? ''),
            'nama_ibu'          => trim($_POST['nama_ibu'] ?? ''),
            'pekerjaan_ibu'     => trim($_POST['pekerjaan_ibu'] ?? ''),
            'nama_wali'         => trim($_POST['nama_wali'] ?? ''),
            'no_hp_ortu'        => trim($_POST['no_hp_ortu'] ?? ''),
            'penghasilan_ortu'  => trim($_POST['penghasilan_ortu'] ?? ''),
            'pas_foto'          => $oldPasFoto
        ];

        $errors = [];

        // Validasi Data Diri
        if (empty($input['nama_lengkap'])) {
            $errors['nama_lengkap'] = 'Nama lengkap wajib diisi.';
        }
        if (empty($input['tempat_lahir'])) {
            $errors['tempat_lahir'] = 'Tempat lahir wajib diisi.';
        }
        if (empty($input['tanggal_lahir'])) {
            $errors['tanggal_lahir'] = 'Tanggal lahir wajib diisi.';
        }
        if (!in_array($input['jenis_kelamin'], ['L', 'P'], true)) {
            $errors['jenis_kelamin'] = 'Pilih jenis kelamin (Laki-laki / Perempuan).';
        }
        if (empty($input['agama'])) {
            $errors['agama'] = 'Agama wajib dipilih.';
        }

        // Validasi Sekolah Asal
        if (!empty($input['npsn_sekolah_asal']) && !preg_match('/^[0-9]{8}$/', $input['npsn_sekolah_asal'])) {
            $errors['npsn_sekolah_asal'] = 'NPSN harus terdiri dari 8 digit angka.';
        }
        if (empty($input['nama_sekolah_asal'])) {
            $errors['nama_sekolah_asal'] = 'Nama sekolah asal (SMP/MTs) wajib diisi.';
        }
        if (empty($input['tahun_lulus']) || !preg_match('/^[0-9]{4}$/', $input['tahun_lulus'])) {
            $errors['tahun_lulus'] = 'Tahun lulus tidak valid.';
        }

        // Validasi Orang Tua
        if (empty($input['nama_ibu'])) {
            $errors['nama_ibu'] = 'Nama ibu kandung wajib diisi.';
        }
        if (empty($input['no_hp_ortu']) || !preg_match('/^[0-9]{10,15}$/', $input['no_hp_ortu'])) {
            $errors['no_hp_ortu'] = 'Nomor HP orang tua/wali wajib berupa 10-15 digit angka.';
        }

        // Validasi Alamat
        if (empty($input['alamat_lengkap'])) {
            $errors['alamat_lengkap'] = 'Alamat lengkap wajib diisi.';
        }
        if (empty($input['kelurahan_desa'])) {
            $errors['kelurahan_desa'] = 'Kelurahan / Desa wajib diisi.';
        }
        if (empty($input['kecamatan'])) {
            $errors['kecamatan'] = 'Kecamatan wajib diisi.';
        }
        if (empty($input['kabupaten_kota'])) {
            $errors['kabupaten_kota'] = 'Kabupaten / Kota wajib diisi.';
        }
        if (empty($input['provinsi'])) {
            $errors['provinsi'] = 'Provinsi wajib diisi.';
        }
        if (!empty($input['kode_pos']) && !preg_match('/^[0-9]{5}$/', $input['kode_pos'])) {
            $errors['kode_pos'] = 'Kode pos harus 5 digit angka.';
        }

        // Validasi & Proses Upload Pas Foto (JPG/JPEG/PNG)
        $newPhotoUploaded = false;
        if (isset($_FILES['pas_foto']) && $_FILES['pas_foto']['error'] !== UPLOAD_ERR_NO_FILE) {
            $ext = strtolower(pathinfo($_FILES['pas_foto']['name'] ?? '', PATHINFO_EXTENSION));
            if (!in_array($ext, ['jpg', 'jpeg', 'png'], true)) {
                $errors['pas_foto'] = 'Pas foto wajib berformat gambar JPG, JPEG, atau PNG.';
            } else {
                $uploadRes = $this->uploadService->upload($_FILES['pas_foto'], 'pas_foto', $userId);
                if (!$uploadRes['success']) {
                    $errors['pas_foto'] = $uploadRes['error'];
                } elseif (strpos($uploadRes['data']['mime_type'], 'image/') !== 0) {
                    $this->uploadService->deleteFile($uploadRes['data']['file_path']);
                    $errors['pas_foto'] = 'Pas foto harus berupa file gambar (JPG atau PNG).';
                } else {
                    $input['pas_foto'] = $uploadRes['data']['file_path'];
                    $newPhotoUploaded  = true;
                }
            }
        } elseif (empty($oldPasFoto)) {
            $errors['pas_foto'] = 'Pas foto 3x4 wajib diunggah.';
        }

        if (!empty($errors)) {
            // Jika ada error validasi field lain padahal foto baru terlanjur terunggah, bersihkan file sementara tersebut
            if ($newPhotoUploaded && !empty($input['pas_foto']) && $input['pas_foto'] !== $oldPasFoto) {
                $this->uploadService->deleteFile($input['pas_foto']);
                $input['pas_foto'] = $oldPasFoto;
            }
            $_SESSION['biodata_errors'] = $errors;
            $_SESSION['biodata_old']    = $input;
            $_SESSION['flash_error']    = 'Terdapat beberapa isian yang belum sesuai. Mohon periksa kembali form di bawah.';
            header('Location: /biodata');
            exit;
        }

        try {
            $sudahAda = !empty($existingBiodata['tempat_lahir']);
            $this->biodataModel->saveOrUpdate($userId, $input);

            // Hapus file pas foto lama jika diganti dengan pas foto baru
            if ($newPhotoUploaded && !empty($oldPasFoto) && $oldPasFoto !== $input['pas_foto']) {
                $this->uploadService->deleteFile($oldPasFoto);
            }

            $_SESSION['user_nama']     = $input['nama_lengkap'];
            $_SESSION['flash_success'] = $sudahAda
                ? 'Perubahan biodata dan pas foto berhasil diperbarui!'
                : 'Data biodata dan pas foto berhasil disimpan!';
        } catch (Exception $e) {
            $_SESSION['biodata_old'] = $input;
            $_SESSION['flash_error'] = 'Gagal menyimpan biodata: ' . $e->getMessage();
        }

        header('Location: /biodata');
        exit;
    }
}
