<?php $url_aktif = trim($_GET['url'] ?? '', '/'); ?>
<header class="navbar">
    <div class="container navbar-wrap">
        <a href="<?= base_url('') ?>" class="brand">Jahit<span>Link</span></a>
        <nav class="nav-links">
            <?php if (isset($_SESSION['id_pengguna'])): ?>
                <span style="font-size: 0.9rem; color: var(--text-muted);">Halo, <strong><?= htmlspecialchars($_SESSION['nama_lengkap']) ?></strong></span>
                <a href="<?= base_url('logout') ?>" class="btn btn-secondary btn-sm">Logout</a>
            <?php else: ?>
                <a href="<?= base_url('') ?>" class="<?= $url_aktif === '' ? 'active' : '' ?>">Beranda</a>
                <a href="<?= base_url('pesan') ?>" class="<?= $url_aktif === 'pesan' ? 'active' : '' ?>">Pesan Jahit</a>
                <a href="<?= base_url('cek-status') ?>" class="<?= $url_aktif === 'cek-status' ? 'active' : '' ?>">Cek Status Pesanan</a>
                <a href="<?= base_url('login') ?>" class="btn btn-outline btn-sm">Login Staf</a>
            <?php endif; ?>
        </nav>
    </div>
</header>