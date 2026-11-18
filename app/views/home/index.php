<?php require __DIR__ . '/../layouts/header.php'; ?>
<?php require __DIR__ . '/../layouts/navbar.php'; ?>

<main>
    <section class="hero" style="padding: 64px 0 48px; text-align: center; background: var(--pastel-sage-light);">
        <div class="container" style="max-width: 760px;">
            <span class="hero-badge">Jahit Custom &amp; Permak Pakaian</span>
            <h1 style="font-size: 2.2rem; margin: 14px 0;">Wujudkan Pakaian Custom Sesuai Ukuran &amp; Model Impian Anda</h1>
            <p style="color: var(--text-muted); margin-bottom: 26px;">
                Pesan layanan penjahitan secara online, masukkan ukuran badan Anda secara detail, dan pantau progres jahitan kapan saja cukup menggunakan kode pesanan.
            </p>
            <div style="display: flex; gap: 12px; justify-content: center; flex-wrap: wrap;">
                <a href="<?= base_url('pesan') ?>" class="btn btn-primary">Pesan Jasa Jahit Sekarang</a>
                <a href="<?= base_url('cek-status') ?>" class="btn btn-secondary">Cek Status Pesanan</a>
            </div>
        </div>
    </section>

    <section class="section">
        <div class="container">
            <div class="section-header">
                <h2>Daftar Layanan Penjahitan</h2>
                <p>Pilih layanan jahit yang sesuai dengan kebutuhan busana Anda.</p>
            </div>

            <?php if (count($daftar_jasa) > 0): ?>
                <div class="grid-3">
                    <?php foreach ($daftar_jasa as $jasa): ?>
                        <div class="card" style="display: flex; flex-direction: column; justify-content: space-between;">
                            <div>
                                <h3 style="font-size: 1.15rem; margin-bottom: 8px; color: var(--pastel-sage);">
                                    <?= htmlspecialchars($jasa['nama_jasa']) ?>
                                </h3>
                                <p style="font-size: 0.92rem; color: var(--text-muted); margin-bottom: 16px;">
                                    <?= htmlspecialchars($jasa['deskripsi'] ?: 'Layanan penjahitan pakaian custom rapi dan presisi sesuai ukuran pelanggan.') ?>
                                </p>
                            </div>
                            <div>
                                <div style="font-size: 1.2rem; font-weight: 700; margin-bottom: 14px;">
                                    <?= format_rupiah($jasa['harga']) ?>
                                </div>
                                <a href="<?= base_url('pesan?id_jasa=' . (int) $jasa['id_jasa']) ?>" class="btn btn-outline btn-sm" style="width: 100%; text-align: center;">
                                    Pilih Layanan Ini
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="card" style="text-align: center;">
                    <p style="color: var(--text-muted); margin: 0;">Belum ada data layanan jahit yang tersedia saat ini.</p>
                </div>
            <?php endif; ?>
        </div>
    </section>
</main>

<?php require __DIR__ . '/../layouts/footer.php'; ?>