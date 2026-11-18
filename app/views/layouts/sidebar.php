<?php
$url_aktif = trim($_GET['url'] ?? '', '/');
$role = $_SESSION['role'] ?? '';
?>
<aside class="sidebar">
    <div class="sidebar-title">Menu <?= ucfirst(htmlspecialchars($role)) ?></div>
    <?php if ($role === 'admin'): ?>
        <a href="<?= base_url('admin/dashboard') ?>" class="<?= $url_aktif === 'admin/dashboard' ? 'active' : '' ?>">Dashboard</a>
        <a href="<?= base_url('admin/pesanan') ?>" class="<?= strpos($url_aktif, 'admin/pesanan') === 0 ? 'active' : '' ?>">Kelola Pesanan</a>
        <a href="<?= base_url('admin/jasa') ?>" class="<?= strpos($url_aktif, 'admin/jasa') === 0 ? 'active' : '' ?>">Kelola Jasa Jahit</a>
        <a href="<?= base_url('admin/penjahit') ?>" class="<?= strpos($url_aktif, 'admin/penjahit') === 0 ? 'active' : '' ?>">Kelola Penjahit</a>
        <a href="<?= base_url('admin/pelanggan') ?>" class="<?= strpos($url_aktif, 'admin/pelanggan') === 0 ? 'active' : '' ?>">Data Pelanggan</a>
        <a href="<?= base_url('admin/pembayaran') ?>" class="<?= strpos($url_aktif, 'admin/pembayaran') === 0 ? 'active' : '' ?>">Pembayaran</a>
    <?php elseif ($role === 'penjahit'): ?>
        <a href="<?= base_url('penjahit/dashboard') ?>" class="<?= $url_aktif === 'penjahit/dashboard' ? 'active' : '' ?>">Tugas Jahitan Saya</a>
    <?php endif; ?>
</aside>