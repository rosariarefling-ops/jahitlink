<?php
require_once __DIR__ . '/../models/JasaJahit.php';
require_once __DIR__ . '/../models/Pelanggan.php';
require_once __DIR__ . '/../models/Pesanan.php';
require_once __DIR__ . '/../models/Pembayaran.php';

class PelangganController {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function formPesan() {
        $jasaModel   = new JasaJahit($this->pdo);
        $daftar_jasa = $jasaModel->getAll();
        $id_jasa_terpilih = (int) ($_POST['id_jasa'] ?? ($_GET['id_jasa'] ?? 0));
        $error = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nama_pelanggan = trim(preg_replace('/\s+/', ' ', $_POST['nama_pelanggan'] ?? ''));
            $no_hp_raw      = trim($_POST['no_hp'] ?? '');
            $no_hp          = preg_replace('/[\s\-]/', '', $no_hp_raw);
            $alamat         = trim($_POST['alamat'] ?? '');
            $id_jasa        = (int) ($_POST['id_jasa'] ?? 0);
            $jumlah         = (int) ($_POST['jumlah'] ?? 1);
            $model_pakaian  = trim(preg_replace('/\s+/', ' ', $_POST['model_pakaian'] ?? ''));
            $rencana_bayar  = trim($_POST['rencana_bayar'] ?? 'DP 50% - Transfer Bank');
            $catatan_input  = trim($_POST['catatan_ukuran'] ?? '');
            $catatan_ukuran = ($catatan_input !== '' ? $catatan_input . ' | ' : '') . 'Preferensi Bayar: ' . $rencana_bayar;

            $ukuran_keys = ['lingkar_kepala', 'lingkar_dada', 'lingkar_paha', 'lingkar_pinggang', 'lebar_bahu'];
            $ukuran = [];
            $ukuran_valid = true;

            foreach ($ukuran_keys as $key) {
                $val = trim($_POST[$key] ?? '');
                if ($val === '') {
                    $ukuran[$key] = null;
                } elseif (!is_numeric($val) || (float) $val < 10 || (float) $val > 250) {
                    $ukuran_valid = false;
                } else {
                    $ukuran[$key] = (float) $val;
                }
            }

            $jasa = $jasaModel->findById($id_jasa);

            if ($nama_pelanggan === '' || $no_hp === '' || !$jasa || $model_pakaian === '') {
                $error = 'Nama lengkap, nomor HP, jenis layanan, dan model pakaian wajib diisi.';
            } elseif (
                mb_strlen($nama_pelanggan) < 3 ||
                !preg_match("/^[a-zA-Z\s.,'-]+$/", $nama_pelanggan) ||
                !preg_match("/[a-zA-Z]/", $nama_pelanggan)
            ) {
                $error = 'Nama lengkap hanya boleh berisi huruf, spasi, titik (.), atau koma (,), minimal 3 karakter.';
            } elseif (!preg_match('/^(\+62|62|0)8[1-9][0-9]{7,11}$/', $no_hp)) {
                $error = 'Format Nomor HP tidak valid. Gunakan nomor seluler Indonesia yang aktif (contoh: 081234567890).';
            } elseif ($alamat !== '' && (mb_strlen($alamat) < 5 || !preg_match('/[a-zA-Z]/', $alamat))) {
                $error = 'Alamat harus diisi dengan jelas (minimal 5 karakter dan mengandung nama jalan/wilayah).';
            } elseif ($jumlah < 1 || $jumlah > 100) {
                $error = 'Jumlah pakaian harus antara 1 hingga 100 pcs.';
            } elseif (mb_strlen($model_pakaian) < 3 || !preg_match('/[a-zA-Z]/', $model_pakaian)) {
                $error = 'Model pakaian wajib berisi keterangan huruf yang jelas (contoh: Kebaya Modern).';
            } elseif (!$ukuran_valid) {
                $error = 'Nilai ukuran badan tidak logis. Masukkan angka antara 10 cm hingga 250 cm.';
            } else {
                try {
                    $this->pdo->beginTransaction();

                    $pelangganModel = new Pelanggan($this->pdo);
                    $pesananModel   = new Pesanan($this->pdo);

                    $id_pelanggan = $pelangganModel->findOrCreate($nama_pelanggan, $no_hp, $alamat);
                    $kode_pesanan = $pesananModel->generateKodePesanan();
                    $harga_satuan = (float) $jasa['harga'];
                    $total_harga  = $harga_satuan * $jumlah;

                    $pesananModel->create([
                        'kode_pesanan'     => $kode_pesanan,
                        'id_pelanggan'     => $id_pelanggan,
                        'id_jasa'          => $id_jasa,
                        'jumlah'           => $jumlah,
                        'harga_satuan'     => $harga_satuan,
                        'total_harga'      => $total_harga,
                        'model_pakaian'    => $model_pakaian,
                        'lingkar_kepala'   => $ukuran['lingkar_kepala'],
                        'lingkar_dada'     => $ukuran['lingkar_dada'],
                        'lingkar_paha'     => $ukuran['lingkar_paha'],
                        'lingkar_pinggang' => $ukuran['lingkar_pinggang'],
                        'lebar_bahu'       => $ukuran['lebar_bahu'],
                        'catatan_ukuran'   => $catatan_ukuran
                    ]);

                    $this->pdo->commit();
                    redirect('pesan/sukses?kode=' . urlencode($kode_pesanan) . '&hp=' . urlencode($no_hp));
                } catch (Exception $e) {
                    $this->pdo->rollBack();
                    $error = 'Gagal menyimpan pesanan: ' . $e->getMessage();
                }
            }
        }

        view('pelanggan/pemesanan/form', [
            'judul_halaman'    => 'Form Pemesanan Jahit',
            'daftar_jasa'      => $daftar_jasa,
            'id_jasa_terpilih' => $id_jasa_terpilih,
            'error'            => $error
        ]);
    }

    public function sukses() {
        $kode  = trim($_GET['kode'] ?? '');
        $no_hp = trim($_GET['hp'] ?? '');

        if ($kode === '' || $no_hp === '') {
            redirect('pesan');
        }

        $pesananModel = new Pesanan($this->pdo);
        $pesanan = $pesananModel->findByKodeAndHp($kode, $no_hp);

        if (!$pesanan) {
            redirect('pesan');
        }

        view('pelanggan/pemesanan/sukses', [
            'judul_halaman' => 'Pesanan Berhasil Dibuat',
            'pesanan'       => $pesanan
        ]);
    }

    public function cekStatus() {
        $kode  = strtoupper(trim($_POST['kode_pesanan'] ?? ($_GET['kode'] ?? '')));
        $no_hp = preg_replace('/[\s\-]/', '', trim($_POST['no_hp'] ?? ($_GET['hp'] ?? '')));
        $pesanan = null;
        $riwayat_bayar = [];
        $error = '';

        if ($kode !== '' || $no_hp !== '') {
            if ($kode === '' || $no_hp === '') {
                $error = 'Masukkan Kode Pesanan dan Nomor HP secara lengkap.';
            } elseif (!preg_match('/^JHL-\d{8}-\d{3,}$/', $kode)) {
                $error = 'Format Kode Pesanan tidak sesuai (contoh format benar: JHL-20261118-001).';
            } elseif (!preg_match('/^(\+62|62|0)8[1-9][0-9]{7,11}$/', $no_hp)) {
                $error = 'Format Nomor HP tidak valid. Masukkan 10–14 digit nomor HP terdaftar.';
            } else {
                $pesananModel    = new Pesanan($this->pdo);
                $pembayaranModel = new Pembayaran($this->pdo);

                $pesanan = $pesananModel->findByKodeAndHp($kode, $no_hp);
                if ($pesanan) {
                    $riwayat_bayar = $pembayaranModel->getByPesanan($pesanan['id_pesanan']);
                } else {
                    $error = 'Pesanan tidak ditemukan. Pastikan Kode Pesanan dan Nomor HP sesuai.';
                }
            }
        }

        view('pelanggan/cek_status', [
            'judul_halaman' => 'Cek Status Pesanan',
            'kode'          => $kode,
            'no_hp'         => $no_hp,
            'pesanan'       => $pesanan,
            'riwayat_bayar' => $riwayat_bayar,
            'error'         => $error
        ]);
    }

    public function indexAdmin() {
        wajib_login('admin');
        $pelangganModel = new Pelanggan($this->pdo);

        view('admin/pelanggan/index', [
            'judul_halaman'    => 'Data Pelanggan',
            'daftar_pelanggan' => $pelangganModel->getAllWithTotalPesanan(),
            'flash'            => get_flash()
        ]);
    }

    public function hapus() {
        wajib_login('admin');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('admin/pelanggan');
        }

        $id_pelanggan   = (int) ($_POST['id_pelanggan'] ?? 0);
        $pelangganModel = new Pelanggan($this->pdo);
        $pelanggan      = $pelangganModel->findById($id_pelanggan);

        if (!$pelanggan) {
            set_flash('danger', 'Data pelanggan tidak ditemukan.');
            redirect('admin/pelanggan');
        }

        // Pastikan pelanggan tidak memiliki riwayat pesanan (ON DELETE RESTRICT)
        $stmtCek = $this->pdo->prepare("SELECT COUNT(*) FROM pesanan WHERE id_pelanggan = :id");
        $stmtCek->execute([':id' => $id_pelanggan]);
        if ((int) $stmtCek->fetchColumn() > 0) {
            set_flash('danger', 'Pelanggan tidak dapat dihapus karena masih memiliki riwayat pesanan.');
            redirect('admin/pelanggan');
        }

        $pelangganModel->deleteById($id_pelanggan);
        set_flash('success', 'Data pelanggan ' . $pelanggan['nama_pelanggan'] . ' berhasil dihapus.');
        redirect('admin/pelanggan');
    }
}