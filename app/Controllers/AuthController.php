<?php
class AuthController extends Controller {
    private $userModel;

    public function __construct() {
        $this->userModel = $this->model('UserModel');
    }

    /**
     * Default route /auth -> arahkan ke halaman login
     */
    public function index() {
        $this->login();
    }

    /**
     * Menampilkan & memproses form Login (No HP & Password)
     */
    public function login() {
        // Jika sudah login, langsung arahkan ke dasbor sesuai role
        if (isset($_SESSION['user_id'])) {
            $target = (($_SESSION['user_role'] ?? '') === 'admin') ? '/admin' : '/dashboard';
            header('Location: ' . $target);
            exit;
        }

        $data = [
            'title'   => 'Login Calon Taruna - SPMB SMAN 5 Taruna Brawijaya',
            'errors'  => [],
            'old'     => ['no_hp' => ''],
            'success' => $_SESSION['flash_success'] ?? null
        ];
        unset($_SESSION['flash_success']);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $noHp     = trim($_POST['no_hp'] ?? '');
            $password = trim($_POST['password'] ?? '');

            $data['old']['no_hp'] = $noHp;

            // Validasi input (Mendukung Nomor HP 10-15 digit atau username 'admin')
            $isAdminUsername = (strtolower($noHp) === 'admin');
            if (empty($noHp)) {
                $data['errors']['no_hp'] = 'Nomor HP atau Username wajib diisi.';
            } elseif (!$isAdminUsername && !preg_match('/^[0-9]{10,15}$/', $noHp)) {
                $data['errors']['no_hp'] = 'Format Nomor HP tidak valid (gunakan 10-15 digit angka).';
            }

            if (empty($password)) {
                $data['errors']['password'] = 'Password wajib diisi.';
            }

            // Jika lolos validasi dasar, cek kredensial ke database
            if (empty($data['errors'])) {
                $user = $this->userModel->loginByNoHp($noHp, $password);

                if ($user) {
                    session_regenerate_id(true);
                    $_SESSION['user_id']    = $user['id'];
                    $_SESSION['user_nama']  = $user['nama_lengkap'];
                    $_SESSION['user_nisn']  = $user['nisn'];
                    $_SESSION['user_no_hp'] = $user['no_hp'];
                    $_SESSION['user_role']  = $user['role'];

                    if (($user['role'] ?? '') === 'admin') {
                        header('Location: /admin');
                    } else {
                        header('Location: /dashboard');
                    }
                    exit;
                } else {
                    $data['errors']['general'] = 'Nomor HP atau Password yang Anda masukkan salah.';
                }
            }
        }

        $this->view('auth/login', $data);
    }

    /**
     * Menampilkan & memproses form Registrasi SPMB SMAN 5 Taruna Brawijaya
     */
    public function register() {
        // Jika sudah login, langsung arahkan ke dasbor sesuai role
        if (isset($_SESSION['user_id'])) {
            $target = (($_SESSION['user_role'] ?? '') === 'admin') ? '/admin' : '/dashboard';
            header('Location: ' . $target);
            exit;
        }

        $data = [
            'title'  => 'Registrasi Akun - SPMB SMAN 5 Taruna Brawijaya',
            'errors' => [],
            'old'    => [
                'nik'          => '',
                'nisn'         => '',
                'nama_lengkap' => '',
                'no_hp'        => '',
                'tahun_lulus'  => 2027,
                'email'        => ''
            ]
        ];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nik            = trim($_POST['nik'] ?? '');
            $nisn           = trim($_POST['nisn'] ?? '');
            $namaLengkap    = trim($_POST['nama_lengkap'] ?? '');
            $noHp           = trim($_POST['no_hp'] ?? '');
            $password       = $_POST['password'] ?? '';
            $passwordRepeat = $_POST['password_confirmation'] ?? '';
            $tahunLulus     = trim($_POST['tahun_lulus'] ?? '');
            $email          = trim($_POST['email'] ?? '');

            $data['old'] = [
                'nik'          => $nik,
                'nisn'         => $nisn,
                'nama_lengkap' => $namaLengkap,
                'no_hp'        => $noHp,
                'tahun_lulus'  => $tahunLulus,
                'email'        => $email
            ];

            // 1. Validasi NIK (16 digit angka & unik)
            if (empty($nik)) {
                $data['errors']['nik'] = 'NIK wajib diisi.';
            } elseif (!preg_match('/^[0-9]{16}$/', $nik)) {
                $data['errors']['nik'] = 'NIK harus terdiri dari 16 digit angka.';
            } elseif ($this->userModel->findByNik($nik)) {
                $data['errors']['nik'] = 'NIK sudah terdaftar dalam sistem.';
            }

            // 2. Validasi NISN (10 digit angka & unik)
            if (empty($nisn)) {
                $data['errors']['nisn'] = 'NISN wajib diisi.';
            } elseif (!preg_match('/^[0-9]{10}$/', $nisn)) {
                $data['errors']['nisn'] = 'NISN harus terdiri dari 10 digit angka.';
            } elseif ($this->userModel->findByNisn($nisn)) {
                $data['errors']['nisn'] = 'NISN sudah terdaftar dalam sistem.';
            }

            // 3. Validasi Nama Lengkap
            if (empty($namaLengkap)) {
                $data['errors']['nama_lengkap'] = 'Nama lengkap wajib diisi.';
            } elseif (mb_strlen($namaLengkap) < 3) {
                $data['errors']['nama_lengkap'] = 'Nama lengkap minimal 3 karakter.';
            }

            // 4. Validasi Nomor HP (10-15 digit angka & unik)
            if (empty($noHp)) {
                $data['errors']['no_hp'] = 'Nomor HP/WhatsApp wajib diisi.';
            } elseif (!preg_match('/^[0-9]{10,15}$/', $noHp)) {
                $data['errors']['no_hp'] = 'Nomor HP harus berupa 10-15 digit angka (contoh: 081234567890).';
            } elseif ($this->userModel->findByNoHp($noHp)) {
                $data['errors']['no_hp'] = 'Nomor HP sudah digunakan oleh pendaftar lain.';
            }

            // 5. Validasi Tahun Lulus (Kelas IX TA 2026/2027 = 2027 atau Lulusan Tahun Sebelumnya)
            $currentYear = max((int) date('Y'), 2026);
            if (empty($tahunLulus)) {
                $data['errors']['tahun_lulus'] = 'Tahun lulus wajib dipilih/diisi.';
            } elseif (!preg_match('/^[0-9]{4}$/', $tahunLulus) || (int)$tahunLulus < 2023 || (int)$tahunLulus > ($currentYear + 2)) {
                $data['errors']['tahun_lulus'] = 'Tahun lulus tidak valid.';
            }

            // 6. Validasi Email
            if (empty($email)) {
                $data['errors']['email'] = 'Alamat email wajib diisi.';
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $data['errors']['email'] = 'Format alamat email tidak valid.';
            }

            // 7. Validasi Password & Ulangi Password
            if (empty($password)) {
                $data['errors']['password'] = 'Password wajib diisi.';
            } elseif (strlen($password) < 8) {
                $data['errors']['password'] = 'Password minimal 8 karakter.';
            }

            if (empty($passwordRepeat)) {
                $data['errors']['password_confirmation'] = 'Konfirmasi ulangi password wajib diisi.';
            } elseif ($password !== $passwordRepeat) {
                $data['errors']['password_confirmation'] = 'Konfirmasi password tidak cocok.';
            }

            // Jika seluruh validasi terpenuhi, simpan user baru & login otomatis
            if (empty($data['errors'])) {
                try {
                    $newUser = $this->userModel->register([
                        'nik'          => $nik,
                        'nisn'         => $nisn,
                        'nama_lengkap' => $namaLengkap,
                        'no_hp'        => $noHp,
                        'password'     => $password,
                        'tahun_lulus'  => (int) $tahunLulus,
                        'email_ortu'   => $email
                    ]);

                    if ($newUser && isset($newUser['id'])) {
                        session_regenerate_id(true);
                        $_SESSION['user_id']       = $newUser['id'];
                        $_SESSION['user_nama']     = $newUser['nama_lengkap'];
                        $_SESSION['user_nisn']     = $newUser['nisn'];
                        $_SESSION['user_no_hp']    = $newUser['no_hp'];
                        $_SESSION['user_role']     = $newUser['role'];
                        $_SESSION['flash_success'] = 'Registrasi berhasil! Selamat datang di Portal SPMB SMAN 5 Taruna Brawijaya.';

                        header('Location: /dashboard');
                        exit;
                    }
                } catch (Exception $e) {
                    $data['errors']['general'] = 'Terjadi kesalahan saat menyimpan pendaftaran. Silakan periksa kembali data Anda.';
                }
            }
        }

        $this->view('auth/register', $data);
    }

    /**
     * Logout & hapus session pengguna
     */
    public function logout() {
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params['path'],
                $params['domain'],
                $params['secure'],
                $params['httponly']
            );
        }
        session_destroy();

        header('Location: /auth/login');
        exit;
    }
}
