<?php
require_once __DIR__ . '/../models/Pesanan.php';
require_once __DIR__ . '/../models/Pengguna.php';
require_once __DIR__ . '/../models/Pembayaran.php';

class PesananController {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function index() {
        wajib_login('admin');
        $filter_status = $_GET['status'] ?? '';
        $pesananModel = new Pesanan($this->pdo);

        view('admin/pesanan/index', [
            'judul_halaman'  => 'Kelola Pesanan',
            'daftar_pesanan' => $pesananModel->getAllAdmin($filter_status),
            'filter_status'  => $filter_status,
            'flash'          => get_flash()
        ]);
    }

    public function detail() {
        wajib_login('admin');
        $id = (int) ($_GET['id'] ?? 0);

        $pesananModel    = new Pesanan($this->pdo);
        $penggunaModel   = new Pengguna($this->pdo);
        $pembayaranModel = new Pembayaran($this->pdo);

        $pesanan = $pesananModel->findById($id);
        if (!$pesanan) {
            set_flash('danger', 'Data pesanan tidak ditemukan.');
            redirect('admin/pesanan');
        }

        view('admin/pesanan/detail', [
            'judul_halaman'   => 'Detail & Konfirmasi Pesanan',
            'pesanan'         => $pesanan,
            'daftar_penjahit' => $penggunaModel->getAllPenjahit(),
            'riwayat_bayar'   => $pembayaranModel->getByPesanan($id),
            'flash'           => get_flash()
        ]);
    }

    public function update() {
        wajib_login('admin');
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_pesanan        = (int) ($_POST['id_pesanan'] ?? 0);
            $id_penjahit       = (int) ($_POST['id_penjahit'] ?? 0);
            $harga_satuan      = (float) ($_POST['harga_satuan'] ?? 0);
            $perkiraan_selesai = trim($_POST['perkiraan_selesai'] ?? '');
            $status_pesanan    = $_POST['status_pesanan'] ?? 'Menunggu Konfirmasi';

            $status_valid = ['Menunggu Konfirmasi', 'Dikonfirmasi', 'Sedang Dikerjakan', 'Siap Diambil', 'Selesai'];

            $pesananModel = new Pesanan($this->pdo);
            $pesanan = $pesananModel->findById($id_pesanan);

            if (!$pesanan) {
                set_flash('danger', 'Pesanan tidak ditemukan.');
                redirect('admin/pesanan');
            }

            if ($harga_satuan < 0 || !in_array($status_pesanan, $status_valid, true)) {
                set_flash('danger', 'Input harga atau status pesanan tidak valid.');
                redirect('admin/pesanan/detail?id=' . $id_pesanan);
            }

            if ($status_pesanan !== 'Menunggu Konfirmasi' && $id_penjahit <= 0) {
                set_flash('danger', 'Pilih penjahit yang bertugas sebelum mengubah status dari Menunggu Konfirmasi.');
                redirect('admin/pesanan/detail?id=' . $id_pesanan);
            }

            $total_harga = $harga_satuan * (int) $pesanan['jumlah'];
            if ($total_harga < (float) $pesanan['total_dibayar']) {
                set_flash('danger', 'Total harga baru tidak boleh lebih kecil dari total yang sudah dibayar pelanggan (' . format_rupiah($pesanan['total_dibayar']) . ').');
                redirect('admin/pesanan/detail?id=' . $id_pesanan);
            }

            $pesananModel->updateAdmin($id_pesanan, $id_penjahit, $harga_satuan, $total_harga, $perkiraan_selesai, $status_pesanan);
            set_flash('success', 'Data pesanan, penugasan penjahit, dan status berhasil diperbarui.');
            redirect('admin/pesanan/detail?id=' . $id_pesanan);
        }
        redirect('admin/pesanan');
    }

    public function hapus() {
        wajib_login('admin');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('admin/pesanan');
        }

        $id_pesanan   = (int) ($_POST['id_pesanan'] ?? 0);
        $pesananModel = new Pesanan($this->pdo);
        $pesanan      = $pesananModel->findById($id_pesanan);

        if (!$pesanan) {
            set_flash('danger', 'Data pesanan tidak ditemukan.');
            redirect('admin/pesanan');
        }

        if ($pesanan['status_pesanan'] !== 'Menunggu Konfirmasi') {
            set_flash('danger', 'Hanya pesanan berstatus Menunggu Konfirmasi yang dapat dihapus.');
            redirect('admin/pesanan');
        }

        try {
            $this->pdo->beginTransaction();

            $stmtBayar = $this->pdo->prepare("DELETE FROM pembayaran WHERE id_pesanan = :id");
            $stmtBayar->execute([':id' => $id_pesanan]);

            $pesananModel->deleteById($id_pesanan);

            $this->pdo->commit();
            set_flash('success', 'Pesanan ' . $pesanan['kode_pesanan'] . ' berhasil dihapus.');
        } catch (Exception $e) {
            $this->pdo->rollBack();
            set_flash('danger', 'Gagal menghapus pesanan: ' . $e->getMessage());
        }

        redirect('admin/pesanan');
    }
}