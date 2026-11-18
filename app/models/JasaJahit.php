<?php
class JasaJahit {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getAll() {
        $stmt = $this->pdo->query("SELECT * FROM jasa_jahit ORDER BY id_jasa DESC");
        return $stmt->fetchAll();
    }

    public function findById($id_jasa) {
        $stmt = $this->pdo->prepare("SELECT * FROM jasa_jahit WHERE id_jasa = ?");
        $stmt->execute([$id_jasa]);
        return $stmt->fetch();
    }

    public function countAll() {
        return (int) $this->pdo->query("SELECT COUNT(*) FROM jasa_jahit")->fetchColumn();
    }

    public function create($nama_jasa, $deskripsi, $harga) {
        $stmt = $this->pdo->prepare("INSERT INTO jasa_jahit (nama_jasa, deskripsi, harga) VALUES (?, ?, ?)");
        return $stmt->execute([$nama_jasa, $deskripsi, $harga]);
    }

    public function update($id_jasa, $nama_jasa, $deskripsi, $harga) {
        $stmt = $this->pdo->prepare("UPDATE jasa_jahit SET nama_jasa = ?, deskripsi = ?, harga = ? WHERE id_jasa = ?");
        return $stmt->execute([$nama_jasa, $deskripsi, $harga, $id_jasa]);
    }

    public function isUsedInPesanan($id_jasa) {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM pesanan WHERE id_jasa = ?");
        $stmt->execute([$id_jasa]);
        return (int) $stmt->fetchColumn() > 0;
    }

    public function delete($id_jasa) {
        $stmt = $this->pdo->prepare("DELETE FROM jasa_jahit WHERE id_jasa = ?");
        return $stmt->execute([$id_jasa]);
    }
}