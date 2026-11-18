<?php
require_once __DIR__ . '/../models/Pembayaran.php';
require_once __DIR__ . '/../models/Pesanan.php';

class PembayaranController {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function index() {
        wajib_login('admin');
        $pembayaranModel = new Pembayaran($this->pdo);
        $pesananModel    = new Pesanan($this->pdo);

        view('admin/pembayaran/index', [
            'judul_halaman'      => 'Pencatatan & Riwayat Pembayaran',
            'daftar_pembayaran'  => $pembayaranModel->getAllAdmin(),
            'pesanan_belum_lunas'=> $pesananModel->getBelumLunas(),
            'id_pesanan_terpilih'=> (int) ($_GET['id_pesanan'] ?? 0),
            'flash'              => get_flash()
        ]);
    }

    public function simpan() {
        wajib_login('admin');
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_pesanan        = (int) ($_POST['id_pesanan'] ?? 0);
            $jenis_pembayaran  = $_POST['jenis_pembayaran'] ?? '';
            $metode_pembayaran = $_POST['metode_pembayaran'] ?? '';
            $jumlah_bayar      = (float) ($_POST['jumlah_bayar'] ?? 0);

            $pesananModel    = new Pesanan($this->pdo);
            $pembayaranModel = new Pembayaran($this->pdo);

            $pesanan = $pesananModel->findById($id_pesanan);
            if (!$pesanan) {
                set_flash('danger', 'Pesanan tidak ditemukan.');
                redirect('admin/pembayaran');
            }

            $total_harga   = (float) $pesanan['total_harga'];
            $total_dibayar = $pembayaranModel->getTotalDibayarByPesanan($id_pesanan);
            $sisa_tagihan  = $total_harga - $total_dibayar;

            if (!in_array($jenis_pembayaran, ['DP', 'Pelunasan'], true) || !in_array($metode_pembayaran, ['Tunai', 'Transfer'], true)) {
                set_flash('danger', 'Jenis atau metode pembayaran tidak valid.');
            } elseif ($jumlah_bayar <= 0) {
                set_flash('danger', 'Nominal pembayaran harus lebih dari Rp 0.');
            } elseif ($jumlah_bayar > $sisa_tagihan) {
                set_flash('danger', 'Nominal pembayaran melebihi sisa tagihan pesanan (' . format_rupiah($sisa_tagihan) . ').');
            } else {
                $pembayaranModel->create($id_pesanan, $_SESSION['id_pengguna'], $jenis_pembayaran, $metode_pembayaran, $jumlah_bayar);
                set_flash('success', 'Pembayaran sebesar ' . format_rupiah($jumlah_bayar) . ' berhasil dicatat.');
            }

            if (!empty($_POST['redirect_detail'])) {
                redirect('admin/pesanan/detail?id=' . $id_pesanan);
            }
        }
        redirect('admin/pembayaran');
    }
}