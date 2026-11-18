<?php
require_once __DIR__ . '/../app/controllers/HomeController.php';
require_once __DIR__ . '/../app/controllers/AuthController.php';
require_once __DIR__ . '/../app/controllers/JasaController.php';
require_once __DIR__ . '/../app/controllers/PenjahitController.php';
require_once __DIR__ . '/../app/controllers/PelangganController.php';
require_once __DIR__ . '/../app/controllers/PesananController.php';
require_once __DIR__ . '/../app/controllers/PembayaranController.php';

switch ($url) {
    // Halaman Publik Pelanggan
    case '':
        (new HomeController($pdo))->index();
        break;
    case 'pesan':
        (new PelangganController($pdo))->formPesan();
        break;
    case 'pesan/sukses':
        (new PelangganController($pdo))->sukses();
        break;
    case 'cek-status':
        (new PelangganController($pdo))->cekStatus();
        break;

    // Autentikasi Staf
    case 'login':
        (new AuthController($pdo))->login();
        break;
    case 'logout':
        (new AuthController($pdo))->logout();
        break;

    // Panel Admin
    case 'admin/dashboard':
        (new HomeController($pdo))->dashboardAdmin();
        break;
    case 'admin/jasa':
        (new JasaController($pdo))->index();
        break;
    case 'admin/jasa/tambah':
        (new JasaController($pdo))->tambah();
        break;
    case 'admin/jasa/edit':
        (new JasaController($pdo))->edit();
        break;
    case 'admin/jasa/hapus':
        (new JasaController($pdo))->hapus();
        break;
    case 'admin/penjahit':
        (new PenjahitController($pdo))->indexAdmin();
        break;
    case 'admin/penjahit/tambah':
        (new PenjahitController($pdo))->tambah();
        break;
    case 'admin/penjahit/edit':
        (new PenjahitController($pdo))->edit();
        break;
    case 'admin/penjahit/hapus':
        (new PenjahitController($pdo))->hapus();
        break;
    case 'admin/pelanggan':
        (new PelangganController($pdo))->indexAdmin();
        break;
    case 'admin/pesanan':
        (new PesananController($pdo))->index();
        break;
    case 'admin/pesanan/detail':
        (new PesananController($pdo))->detail();
        break;
    case 'admin/pesanan/update':
        (new PesananController($pdo))->update();
        break;
    case 'admin/pembayaran':
        (new PembayaranController($pdo))->index();
        break;
    case 'admin/pembayaran/simpan':
        (new PembayaranController($pdo))->simpan();
        break;

    // Panel Penjahit
    case 'penjahit/dashboard':
        (new PenjahitController($pdo))->dashboard();
        break;
    case 'penjahit/pesanan/detail':
        (new PenjahitController($pdo))->detailTugas();
        break;
    case 'penjahit/pesanan/update-status':
        (new PenjahitController($pdo))->updateStatus();
        break;

    default:
        http_response_code(404);
        echo "<h3>404 - Halaman tidak ditemukan</h3><a href='" . base_url('') . "'>Kembali ke Beranda</a>";
        break;
}