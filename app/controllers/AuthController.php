<?php
require_once __DIR__ . '/../models/Pengguna.php';

class AuthController {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function login() {
        if (isset($_SESSION['id_pengguna']) && isset($_SESSION['role'])) {
            if ($_SESSION['role'] === 'admin') {
                redirect('admin/dashboard');
            } elseif ($_SESSION['role'] === 'penjahit') {
                redirect('penjahit/dashboard');
            }
        }

        $error = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username'] ?? '');
            $password = $_POST['password'] ?? '';

            if ($username === '' || $password === '') {
                $error = 'Username dan password wajib diisi.';
            } else {
                $penggunaModel = new Pengguna($this->pdo);
                $user = $penggunaModel->findByUsername($username);

                if ($user && password_verify($password, $user['password'])) {
                    $_SESSION['id_pengguna']  = (int) $user['id_pengguna'];
                    $_SESSION['nama_lengkap'] = $user['nama_lengkap'];
                    $_SESSION['username']     = $user['username'];
                    $_SESSION['role']         = $user['role'];

                    if ($user['role'] === 'admin') {
                        redirect('admin/dashboard');
                    } else {
                        redirect('penjahit/dashboard');
                    }
                } else {
                    $error = 'Username atau password salah.';
                }
            }
        }

        view('auth/login', [
            'judul_halaman' => 'Login Staf',
            'error'         => $error
        ]);
    }

    public function logout() {
        $_SESSION = [];
        session_destroy();
        redirect('login');
    }
}