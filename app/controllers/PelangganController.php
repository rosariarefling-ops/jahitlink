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
            $nama_pelanggan = trim($_POST['nama_pelanggan'] ?? '');
            $no_hp          = trim($_POST['no_hp'] ?? '');
            $alamat         = trim($_POST['alamat'] ?? '');
            $id_jasa        = (int) ($_POST['id_jasa'] ?? 0);
            $jumlah         = (int) ($_POST['jumlah'] ?? 1);
            $model_pakaian  = trim($_POST['model_pakaian'] ?? '');
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
                } elseif (!is_numeric($val) || (float) $val < 0) {
                    $ukuran_valid = false;
                } else {
                    $ukuran[$key] = (float) $val;
                }
            }

            $jasa = $jasaModel->findById($id_jasa);

            if ($nama_pelanggan === '' || $no_hp === '' || !$jasa || $model_pakaian === '') {
                $error = 'Nama lengkap, nomor HP, jenis layanan, dan model pakaian wajib diisi.';
            } elseif ($jumlah < 1) {
                $error = 'Jumlah pakaian minimal 1 pcs.';
            } elseif (!$ukuran_valid) {
                $error = 'Nilai ukuran badan harus berupa angka positif (satuan cm).';
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
        $kode  = trim($_POST['kode_pesanan'] ?? ($_GET['kode'] ?? ''));
        $no_hp = trim($_POST['no_hp'] ?? ($_GET['hp'] ?? ''));
        $pesanan = null;
        $riwayat_bayar = [];
        $error = '';

        if ($kode !== '' || $no_hp !== '') {
            if ($kode === '' || $no_hp === '') {
                $error = 'Masukkan Kode Pesanan dan Nomor HP secara lengkap.';
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
            'daftar_pelanggan' => $pelangganModel->getAllWithTotalPesanan()
        ]);
    }
}