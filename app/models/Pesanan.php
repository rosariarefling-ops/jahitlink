<?php
class Pesanan {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function countAll() {
        return (int) $this->pdo->query("SELECT COUNT(*) FROM pesanan")->fetchColumn();
    }

    public function countByStatus($status_list) {
        $placeholders = implode(',', array_fill(0, count($status_list), '?'));
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM pesanan WHERE status_pesanan IN ($placeholders)");
        $stmt->execute($status_list);
        return (int) $stmt->fetchColumn();
    }

    public function getRecent($limit = 5) {
        $limit = (int) $limit;
        $stmt = $this->pdo->query("
            SELECT p.id_pesanan, p.kode_pesanan, pl.nama_pelanggan, j.nama_jasa,
                   p.jumlah, p.total_harga, p.tanggal_pesan, p.status_pesanan
            FROM pesanan p
            JOIN pelanggan pl ON p.id_pelanggan = pl.id_pelanggan
            JOIN jasa_jahit j ON p.id_jasa = j.id_jasa
            ORDER BY p.id_pesanan DESC
            LIMIT $limit
        ");
        return $stmt->fetchAll();
    }

    public function getAllAdmin($filter_status = '') {
        $sql = "
            SELECT p.*, pl.nama_pelanggan, pl.no_hp, j.nama_jasa,
                   u.nama_lengkap AS nama_penjahit,
                   COALESCE((SELECT SUM(pb.jumlah_bayar) FROM pembayaran pb WHERE pb.id_pesanan = p.id_pesanan), 0) AS total_dibayar
            FROM pesanan p
            JOIN pelanggan pl ON p.id_pelanggan = pl.id_pelanggan
            JOIN jasa_jahit j ON p.id_jasa = j.id_jasa
            LEFT JOIN pengguna u ON p.id_penjahit = u.id_pengguna
        ";
        if ($filter_status !== '') {
            $sql .= " WHERE p.status_pesanan = ? ORDER BY p.id_pesanan DESC";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$filter_status]);
            return $stmt->fetchAll();
        }
        $sql .= " ORDER BY p.id_pesanan DESC";
        return $this->pdo->query($sql)->fetchAll();
    }

    public function getBelumLunas() {
        $stmt = $this->pdo->query("
            SELECT p.id_pesanan, p.kode_pesanan, p.total_harga, pl.nama_pelanggan, j.nama_jasa,
                   COALESCE((SELECT SUM(pb.jumlah_bayar) FROM pembayaran pb WHERE pb.id_pesanan = p.id_pesanan), 0) AS total_dibayar
            FROM pesanan p
            JOIN pelanggan pl ON p.id_pelanggan = pl.id_pelanggan
            JOIN jasa_jahit j ON p.id_jasa = j.id_jasa
            HAVING total_dibayar < p.total_harga
            ORDER BY p.id_pesanan DESC
        ");
        return $stmt->fetchAll();
    }

    public function findById($id_pesanan) {
        $stmt = $this->pdo->prepare("
            SELECT p.*, pl.nama_pelanggan, pl.no_hp, pl.alamat,
                   j.nama_jasa, j.deskripsi AS deskripsi_jasa,
                   u.nama_lengkap AS nama_penjahit,
                   COALESCE((SELECT SUM(pb.jumlah_bayar) FROM pembayaran pb WHERE pb.id_pesanan = p.id_pesanan), 0) AS total_dibayar
            FROM pesanan p
            JOIN pelanggan pl ON p.id_pelanggan = pl.id_pelanggan
            JOIN jasa_jahit j ON p.id_jasa = j.id_jasa
            LEFT JOIN pengguna u ON p.id_penjahit = u.id_pengguna
            WHERE p.id_pesanan = ?
            LIMIT 1
        ");
        $stmt->execute([$id_pesanan]);
        return $stmt->fetch();
    }

    public function getByPenjahit($id_penjahit) {
        $stmt = $this->pdo->prepare("
            SELECT p.id_pesanan, p.kode_pesanan, pl.nama_pelanggan, j.nama_jasa,
                   p.model_pakaian, p.jumlah, p.perkiraan_selesai, p.status_pesanan
            FROM pesanan p
            JOIN pelanggan pl ON p.id_pelanggan = pl.id_pelanggan
            JOIN jasa_jahit j ON p.id_jasa = j.id_jasa
            WHERE p.id_penjahit = ?
            ORDER BY p.id_pesanan DESC
        ");
        $stmt->execute([$id_penjahit]);
        return $stmt->fetchAll();
    }

    public function findByIdAndPenjahit($id_pesanan, $id_penjahit) {
        $stmt = $this->pdo->prepare("
            SELECT p.*, pl.nama_pelanggan, pl.no_hp, j.nama_jasa
            FROM pesanan p
            JOIN pelanggan pl ON p.id_pelanggan = pl.id_pelanggan
            JOIN jasa_jahit j ON p.id_jasa = j.id_jasa
            WHERE p.id_pesanan = ? AND p.id_penjahit = ?
            LIMIT 1
        ");
        $stmt->execute([$id_pesanan, $id_penjahit]);
        return $stmt->fetch();
    }

    public function generateKodePesanan() {
        $prefix = 'JHL-' . date('Ymd') . '-';
        $stmt = $this->pdo->prepare("SELECT kode_pesanan FROM pesanan WHERE kode_pesanan LIKE ? ORDER BY id_pesanan DESC LIMIT 1");
        $stmt->execute([$prefix . '%']);
        $last = $stmt->fetchColumn();
        $urut = $last ? ((int) substr($last, -3)) + 1 : 1;
        return $prefix . str_pad($urut, 3, '0', STR_PAD_LEFT);
    }

    public function create($data) {
        $stmt = $this->pdo->prepare("
            INSERT INTO pesanan (
                kode_pesanan, id_pelanggan, id_jasa, id_penjahit,
                jumlah, harga_satuan, total_harga, model_pakaian,
                lingkar_kepala, lingkar_dada, lingkar_paha, lingkar_pinggang, lebar_bahu, catatan_ukuran,
                tanggal_pesan, perkiraan_selesai, tanggal_selesai, status_pesanan
            ) VALUES (
                ?, ?, ?, NULL,
                ?, ?, ?, ?,
                ?, ?, ?, ?, ?, ?,
                CURDATE(), NULL, NULL, 'Menunggu Konfirmasi'
            )
        ");

        $stmt->execute([
            $data['kode_pesanan'],
            $data['id_pelanggan'],
            $data['id_jasa'],
            $data['jumlah'],
            $data['harga_satuan'],
            $data['total_harga'],
            $data['model_pakaian'],
            $data['lingkar_kepala'],
            $data['lingkar_dada'],
            $data['lingkar_paha'],
            $data['lingkar_pinggang'],
            $data['lebar_bahu'],
            $data['catatan_ukuran']
        ]);

        return (int) $this->pdo->lastInsertId();
    }

    public function findByKodeAndHp($kode_pesanan, $no_hp) {
        $stmt = $this->pdo->prepare("
            SELECT p.*, pl.nama_pelanggan, pl.no_hp, pl.alamat,
                   j.nama_jasa, j.deskripsi AS deskripsi_jasa,
                   u.nama_lengkap AS nama_penjahit,
                   COALESCE((SELECT SUM(pb.jumlah_bayar) FROM pembayaran pb WHERE pb.id_pesanan = p.id_pesanan), 0) AS total_dibayar
            FROM pesanan p
            JOIN pelanggan pl ON p.id_pelanggan = pl.id_pelanggan
            JOIN jasa_jahit j ON p.id_jasa = j.id_jasa
            LEFT JOIN pengguna u ON p.id_penjahit = u.id_pengguna
            WHERE p.kode_pesanan = ? AND pl.no_hp = ?
            LIMIT 1
        ");
        $stmt->execute([$kode_pesanan, $no_hp]);
        return $stmt->fetch();
    }

    public function updateAdmin($id_pesanan, $id_penjahit, $harga_satuan, $total_harga, $perkiraan_selesai, $status_pesanan) {
        $tanggal_selesai_sql = ($status_pesanan === 'Selesai') ? "COALESCE(tanggal_selesai, CURDATE())" : "NULL";
        $stmt = $this->pdo->prepare("
            UPDATE pesanan
            SET id_penjahit = ?,
                harga_satuan = ?,
                total_harga = ?,
                perkiraan_selesai = ?,
                status_pesanan = ?,
                tanggal_selesai = $tanggal_selesai_sql
            WHERE id_pesanan = ?
        ");
        return $stmt->execute([
            $id_penjahit ?: null,
            $harga_satuan,
            $total_harga,
            $perkiraan_selesai !== '' ? $perkiraan_selesai : null,
            $status_pesanan,
            $id_pesanan
        ]);
    }

    public function updateStatusByPenjahit($id_pesanan, $id_penjahit, $status_pesanan) {
        $stmt = $this->pdo->prepare("
            UPDATE pesanan
            SET status_pesanan = ?
            WHERE id_pesanan = ? AND id_penjahit = ?
        ");
        return $stmt->execute([$status_pesanan, $id_pesanan, $id_penjahit]);
    }
}