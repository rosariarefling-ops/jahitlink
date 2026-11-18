<?php
class Pengguna {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function findByUsername($username) {
        $stmt = $this->pdo->prepare("SELECT * FROM pengguna WHERE username = ? LIMIT 1");
        $stmt->execute([$username]);
        return $stmt->fetch();
    }

    public function findById($id_pengguna) {
        $stmt = $this->pdo->prepare("SELECT id_pengguna, nama_lengkap, username, role FROM pengguna WHERE id_pengguna = ? LIMIT 1");
        $stmt->execute([$id_pengguna]);
        return $stmt->fetch();
    }

    public function countByRole($role) {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM pengguna WHERE role = ?");
        $stmt->execute([$role]);
        return (int) $stmt->fetchColumn();
    }

    public function getAllPenjahit() {
        $stmt = $this->pdo->query("SELECT id_pengguna, nama_lengkap, username, role FROM pengguna WHERE role = 'penjahit' ORDER BY nama_lengkap ASC");
        return $stmt->fetchAll();
    }

    public function usernameExists($username, $exclude_id = 0) {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM pengguna WHERE username = ? AND id_pengguna != ?");
        $stmt->execute([$username, $exclude_id]);
        return (int) $stmt->fetchColumn() > 0;
    }

    public function createPenjahit($nama_lengkap, $username, $password_hash) {
        $stmt = $this->pdo->prepare("INSERT INTO pengguna (nama_lengkap, username, password, role) VALUES (?, ?, ?, 'penjahit')");
        return $stmt->execute([$nama_lengkap, $username, $password_hash]);
    }

    public function updatePenjahit($id_pengguna, $nama_lengkap, $username, $password_hash = null) {
        if ($password_hash !== null) {
            $stmt = $this->pdo->prepare("UPDATE pengguna SET nama_lengkap = ?, username = ?, password = ? WHERE id_pengguna = ? AND role = 'penjahit'");
            return $stmt->execute([$nama_lengkap, $username, $password_hash, $id_pengguna]);
        }
        $stmt = $this->pdo->prepare("UPDATE pengguna SET nama_lengkap = ?, username = ? WHERE id_pengguna = ? AND role = 'penjahit'");
        return $stmt->execute([$nama_lengkap, $username, $id_pengguna]);
    }

    public function isAssignedInPesanan($id_penjahit) {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM pesanan WHERE id_penjahit = ?");
        $stmt->execute([$id_penjahit]);
        return (int) $stmt->fetchColumn() > 0;
    }

    public function deletePenjahit($id_pengguna) {
        $stmt = $this->pdo->prepare("DELETE FROM pengguna WHERE id_pengguna = ? AND role = 'penjahit'");
        return $stmt->execute([$id_pengguna]);
    }
}