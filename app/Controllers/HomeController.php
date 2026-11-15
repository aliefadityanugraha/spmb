<?php
class HomeController extends Controller {
    public function index() {
        $isLoggedIn   = isset($_SESSION['user_id']);
        $userRole     = $_SESSION['user_role'] ?? 'calon_taruna';
        $dashboardUrl = ($userRole === 'admin') ? '/admin' : '/dashboard';

        $data = [
            'title'         => 'Beranda Resmi SPMB - SMAN 5 Taruna Brawijaya Jawa Timur',
            'is_logged_in'  => $isLoggedIn,
            'user_nama'     => $_SESSION['user_nama'] ?? '',
            'user_role'     => $userRole,
            'dashboard_url' => $dashboardUrl
        ];

        $this->view('home/index', $data);
    }
}
