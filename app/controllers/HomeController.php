<?php
require_once __DIR__ . '/../models/JasaJahit.php';
require_once __DIR__ . '/../models/Pesanan.php';
require_once __DIR__ . '/../models/Pengguna.php';
require_once __DIR__ . '/../models/Pembayaran.php';

class HomeController {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function index() {
        $jasaModel = new JasaJahit($this->pdo);
        $daftar_jasa = $jasaModel->getAll();

        view('home/index', [
            'judul_halaman' => 'Beranda',
            'daftar_jasa'   => $daftar_jasa
        ]);
    }

    public function dashboardAdmin() {
        wajib_login('admin');

        $pesananModel    = new Pesanan($this->pdo);
        $jasaModel       = new JasaJahit($this->pdo);
        $penggunaModel   = new Pengguna($this->pdo);
        $pembayaranModel = new Pembayaran($this->pdo);

        view('admin/dashboard', [
            'judul_halaman'   => 'Dashboard Admin',
            'total_pesanan'   => $pesananModel->countAll(),
            'pesanan_aktif'   => $pesananModel->countByStatus(['Menunggu Konfirmasi', 'Dikonfirmasi', 'Sedang Dikerjakan', 'Siap Diambil']),
            'total_jasa'      => $jasaModel->countAll(),
            'total_penjahit'  => $penggunaModel->countByRole('penjahit'),
            'total_pemasukan' => $pembayaranModel->totalPemasukan(),
            'pesanan_terbaru' => $pesananModel->getRecent(5)
        ]);
    }
}