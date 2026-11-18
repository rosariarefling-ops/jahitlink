<?php
class Pembayaran {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function totalPemasukan() {
        return (float) $this->pdo->query("SELECT COALESCE(SUM(jumlah_bayar), 0) FROM pembayaran")->fetchColumn();
    }

    public function getAllAdmin() {
        $stmt = $this->pdo->query("
            SELECT pb.*, p.kode_pesanan, p.total_harga, pl.nama_pelanggan, u.nama_lengkap AS nama_admin
            FROM pembayaran pb
            JOIN pesanan p ON pb.id_pesanan = p.id_pesanan
            JOIN pelanggan pl ON p.id_pelanggan = pl.id_pelanggan
            JOIN pengguna u ON pb.id_admin = u.id_pengguna
            ORDER BY pb.id_pembayaran DESC
        ");
        return $stmt->fetchAll();
    }

    public function getByPesanan($id_pesanan) {
        $stmt = $this->pdo->prepare("
            SELECT pb.*, u.nama_lengkap AS nama_admin
            FROM pembayaran pb
            JOIN pengguna u ON pb.id_admin = u.id_pengguna
            WHERE pb.id_pesanan = ?
            ORDER BY pb.tanggal_bayar ASC, pb.id_pembayaran ASC
        ");
        $stmt->execute([$id_pesanan]);
        return $stmt->fetchAll();
    }

    public function getTotalDibayarByPesanan($id_pesanan) {
        $stmt = $this->pdo->prepare("SELECT COALESCE(SUM(jumlah_bayar), 0) FROM pembayaran WHERE id_pesanan = ?");
        $stmt->execute([$id_pesanan]);
        return (float) $stmt->fetchColumn();
    }

    public function create($id_pesanan, $id_admin, $jenis_pembayaran, $metode_pembayaran, $jumlah_bayar) {
        $stmt = $this->pdo->prepare("
            INSERT INTO pembayaran (id_pesanan, id_admin, jenis_pembayaran, metode_pembayaran, jumlah_bayar, tanggal_bayar)
            VALUES (?, ?, ?, ?, ?, CURDATE())
        ");
        return $stmt->execute([$id_pesanan, $id_admin, $jenis_pembayaran, $metode_pembayaran, $jumlah_bayar]);
    }
}