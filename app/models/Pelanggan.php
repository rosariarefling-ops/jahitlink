<?php
class Pelanggan {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function findByNoHp($no_hp) {
        $stmt = $this->pdo->prepare("SELECT * FROM pelanggan WHERE no_hp = ? LIMIT 1");
        $stmt->execute([$no_hp]);
        return $stmt->fetch();
    }

    public function findOrCreate($nama_pelanggan, $no_hp, $alamat) {
        $existing = $this->findByNoHp($no_hp);
        if ($existing) {
            $stmt = $this->pdo->prepare("UPDATE pelanggan SET nama_pelanggan = ?, alamat = ? WHERE id_pelanggan = ?");
            $stmt->execute([$nama_pelanggan, $alamat, $existing['id_pelanggan']]);
            return (int) $existing['id_pelanggan'];
        }

        $stmt = $this->pdo->prepare("INSERT INTO pelanggan (nama_pelanggan, no_hp, alamat) VALUES (?, ?, ?)");
        $stmt->execute([$nama_pelanggan, $no_hp, $alamat]);
        return (int) $this->pdo->lastInsertId();
    }

    public function getAllWithTotalPesanan() {
        $stmt = $this->pdo->query("
            SELECT pl.*, COUNT(p.id_pesanan) AS total_pesanan
            FROM pelanggan pl
            LEFT JOIN pesanan p ON pl.id_pelanggan = p.id_pelanggan
            GROUP BY pl.id_pelanggan
            ORDER BY pl.id_pelanggan DESC
        ");
        return $stmt->fetchAll();
    }

    public function findById($id_pelanggan) {
        $stmt = $this->pdo->prepare("SELECT * FROM pelanggan WHERE id_pelanggan = :id");
        $stmt->execute([':id' => $id_pelanggan]);
        return $stmt->fetch();
    }

    public function deleteById($id_pelanggan) {
        $stmt = $this->pdo->prepare("DELETE FROM pelanggan WHERE id_pelanggan = :id");
        return $stmt->execute([':id' => $id_pelanggan]);
    }
}