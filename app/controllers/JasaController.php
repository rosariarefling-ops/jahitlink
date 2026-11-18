<?php
require_once __DIR__ . '/../models/JasaJahit.php';

class JasaController {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function index() {
        wajib_login('admin');
        $jasaModel = new JasaJahit($this->pdo);

        view('admin/jasa/index', [
            'judul_halaman' => 'Kelola Jasa Jahit',
            'daftar_jasa'   => $jasaModel->getAll(),
            'flash'         => get_flash()
        ]);
    }

    public function tambah() {
        wajib_login('admin');
        $error = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nama_jasa = trim($_POST['nama_jasa'] ?? '');
            $deskripsi = trim($_POST['deskripsi'] ?? '');
            $harga     = (float) ($_POST['harga'] ?? 0);

            if ($nama_jasa === '' || $harga < 0) {
                $error = 'Nama jasa wajib diisi dan harga tidak boleh bernilai negatif.';
            } else {
                $jasaModel = new JasaJahit($this->pdo);
                $jasaModel->create($nama_jasa, $deskripsi, $harga);
                set_flash('success', 'Layanan jasa jahit baru berhasil ditambahkan.');
                redirect('admin/jasa');
            }
        }

        view('admin/jasa/tambah', [
            'judul_halaman' => 'Tambah Jasa Jahit',
            'error'         => $error
        ]);
    }

    public function edit() {
        wajib_login('admin');
        $id = (int) ($_GET['id'] ?? 0);
        $jasaModel = new JasaJahit($this->pdo);
        $jasa = $jasaModel->findById($id);

        if (!$jasa) {
            set_flash('danger', 'Data jasa jahit tidak ditemukan.');
            redirect('admin/jasa');
        }

        $error = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nama_jasa = trim($_POST['nama_jasa'] ?? '');
            $deskripsi = trim($_POST['deskripsi'] ?? '');
            $harga     = (float) ($_POST['harga'] ?? 0);

            if ($nama_jasa === '' || $harga < 0) {
                $error = 'Nama jasa wajib diisi dan harga tidak boleh bernilai negatif.';
            } else {
                $jasaModel->update($id, $nama_jasa, $deskripsi, $harga);
                set_flash('success', 'Data jasa jahit berhasil diperbarui.');
                redirect('admin/jasa');
            }
        }

        view('admin/jasa/edit', [
            'judul_halaman' => 'Edit Jasa Jahit',
            'jasa'          => $jasa,
            'error'         => $error
        ]);
    }

    public function hapus() {
        wajib_login('admin');
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int) ($_POST['id_jasa'] ?? 0);
            $jasaModel = new JasaJahit($this->pdo);

            if ($jasaModel->isUsedInPesanan($id)) {
                set_flash('danger', 'Jasa jahit tidak dapat dihapus karena sudah tercatat pada riwayat pesanan.');
            } else {
                $jasaModel->delete($id);
                set_flash('success', 'Data jasa jahit berhasil dihapus.');
            }
        }
        redirect('admin/jasa');
    }
}