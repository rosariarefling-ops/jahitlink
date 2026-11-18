<?php
function base_url($path = '') {
    $script_dir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
    $base = ($script_dir === '/' || $script_dir === '.') ? '' : rtrim($script_dir, '/');
    return $base . '/' . ltrim($path, '/');
}

function redirect($path) {
    header('Location: ' . base_url($path));
    exit;
}

function wajib_login($role_diizinkan) {
    if (!isset($_SESSION['id_pengguna']) || !isset($_SESSION['role'])) {
        redirect('login');
    }

    if ($_SESSION['role'] !== $role_diizinkan) {
        if ($_SESSION['role'] === 'admin') {
            redirect('admin/dashboard');
        } elseif ($_SESSION['role'] === 'penjahit') {
            redirect('penjahit/dashboard');
        } else {
            redirect('login');
        }
    }
}

function view($view_path, $data = []) {
    extract($data);
    $file = __DIR__ . '/../app/views/' . $view_path . '.php';
    if (file_exists($file)) {
        require $file;
    } else {
        die("View tidak ditemukan: " . htmlspecialchars($view_path));
    }
}

function format_rupiah($angka) {
    return 'Rp ' . number_format((float) $angka, 0, ',', '.');
}

function set_flash($tipe, $pesan) {
    $_SESSION['flash'] = ['tipe' => $tipe, 'pesan' => $pesan];
}

function get_flash() {
    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}