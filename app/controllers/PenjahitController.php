<?php
require_once __DIR__ . '/../models/Pesanan.php';
require_once __DIR__ . '/../models/Pengguna.php';

class PenjahitController {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // --- FITUR AKTOR PENJAHIT ---
    public function dashboard() {
        wajib_login('penjahit');

        $pesananModel = new Pesanan($this->pdo);
        $daftar_tugas = $pesananModel->getByPenjahit($_SESSION['id_pengguna']);

        view('penjahit/dashboard', [
            'judul_halaman' => 'Dashboard Penjahit',
            'daftar_tugas'  => $daftar_tugas,
            'flash'         => get_flash()
        ]);
    }

    public function detailTugas() {
        wajib_login('penjahit');
        $id_pesanan = (int) ($_GET['id'] ?? 0);

        $pesananModel = new Pesanan($this->pdo);
        $tugas = $pesananModel->findByIdAndPenjahit($id_pesanan, $_SESSION['id_pengguna']);

        if (!$tugas) {
            set_flash('danger', 'Tugas pesanan tidak ditemukan atau bukan milik Anda.');
            redirect('penjahit/dashboard');
        }

        view('penjahit/detail', [
            'judul_halaman' => 'Detail Tugas Jahitan',
            'tugas'         => $tugas,
            'flash'         => get_flash()
        ]);
    }

    public function updateStatus() {
        wajib_login('penjahit');
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_pesanan     = (int) ($_POST['id_pesanan'] ?? 0);
            $status_pesanan = $_POST['status_pesanan'] ?? '';
            $status_valid   = ['Dikonfirmasi', 'Sedang Dikerjakan', 'Siap Diambil'];

            $pesananModel = new Pesanan($this->pdo);
            $tugas = $pesananModel->findByIdAndPenjahit($id_pesanan, $_SESSION['id_pengguna']);

            if (!$tugas) {
                set_flash('danger', 'Akses ditolak: pesanan tidak ditugaskan kepada Anda.');
            } elseif ($tugas['status_pesanan'] === 'Selesai') {
                set_flash('danger', 'Pesanan sudah berstatus Selesai dan tidak dapat diubah.');
            } elseif (!in_array($status_pesanan, $status_valid, true)) {
                set_flash('danger', 'Status pengerjaan tidak valid.');
            } else {
                $pesananModel->updateStatusByPenjahit($id_pesanan, $_SESSION['id_pengguna'], $status_pesanan);
                set_flash('success', 'Status pengerjaan berhasil diperbarui menjadi "' . $status_pesanan . '".');
            }
            redirect('penjahit/pesanan/detail?id=' . $id_pesanan);
        }
        redirect('penjahit/dashboard');
    }

    // --- FITUR KELOLA PENJAHIT OLEH ADMIN ---
    public function indexAdmin() {
        wajib_login('admin');
        $penggunaModel = new Pengguna($this->pdo);

        view('admin/penjahit/index', [
            'judul_halaman'   => 'Kelola Akun Penjahit',
            'daftar_penjahit' => $penggunaModel->getAllPenjahit(),
            'flash'           => get_flash()
        ]);
    }

    public function tambah() {
        wajib_login('admin');
        $error = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nama_lengkap = trim($_POST['nama_lengkap'] ?? '');
            $username     = trim($_POST['username'] ?? '');
            $password     = $_POST['password'] ?? '';

            $penggunaModel = new Pengguna($this->pdo);

            if ($nama_lengkap === '' || $username === '' || $password === '') {
                $error = 'Nama lengkap, username, dan password wajib diisi.';
            } elseif (strlen($password) < 6) {
                $error = 'Password minimal terdiri dari 6 karakter.';
            } elseif ($penggunaModel->usernameExists($username)) {
                $error = 'Username sudah digunakan akun lain.';
            } else {
                $hash = password_hash($password, PASSWORD_DEFAULT);
                $penggunaModel->createPenjahit($nama_lengkap, $username, $hash);
                set_flash('success', 'Akun penjahit baru berhasil ditambahkan.');
                redirect('admin/penjahit');
            }
        }

        view('admin/penjahit/tambah', [
            'judul_halaman' => 'Tambah Akun Penjahit',
            'error'         => $error
        ]);
    }

    public function edit() {
        wajib_login('admin');
        $id = (int) ($_GET['id'] ?? 0);
        $penggunaModel = new Pengguna($this->pdo);
        $penjahit = $penggunaModel->findById($id);

        if (!$penjahit || $penjahit['role'] !== 'penjahit') {
            set_flash('danger', 'Data penjahit tidak ditemukan.');
            redirect('admin/penjahit');
        }

        $error = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nama_lengkap  = trim($_POST['nama_lengkap'] ?? '');
            $username      = trim($_POST['username'] ?? '');
            $password_baru = $_POST['password'] ?? '';

            if ($nama_lengkap === '' || $username === '') {
                $error = 'Nama lengkap dan username wajib diisi.';
            } elseif ($penggunaModel->usernameExists($username, $id)) {
                $error = 'Username sudah digunakan oleh pengguna lain.';
            } elseif ($password_baru !== '' && strlen($password_baru) < 6) {
                $error = 'Jika ingin mengganti password, minimal 6 karakter.';
            } else {
                $hash = ($password_baru !== '') ? password_hash($password_baru, PASSWORD_DEFAULT) : null;
                $penggunaModel->updatePenjahit($id, $nama_lengkap, $username, $hash);
                set_flash('success', 'Data akun penjahit berhasil diperbarui.');
                redirect('admin/penjahit');
            }
        }

        view('admin/penjahit/edit', [
            'judul_halaman' => 'Edit Akun Penjahit',
            'penjahit'      => $penjahit,
            'error'         => $error
        ]);
    }

    public function hapus() {
        wajib_login('admin');
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int) ($_POST['id_pengguna'] ?? 0);
            $penggunaModel = new Pengguna($this->pdo);

            if ($penggunaModel->isAssignedInPesanan($id)) {
                set_flash('danger', 'Akun penjahit tidak dapat dihapus karena masih terhubung dengan data pesanan.');
            } else {
                $penggunaModel->deletePenjahit($id);
                set_flash('success', 'Akun penjahit berhasil dihapus.');
            }
        }
        redirect('admin/penjahit');
    }
}