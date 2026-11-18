<?php require __DIR__ . '/../../layouts/header.php'; ?>
<?php require __DIR__ . '/../../layouts/navbar.php'; ?>

<main>
    <section class="section">
        <div class="container" style="max-width: 620px;">
            <div class="card" style="text-align: center; padding: 36px 28px;">
                <span class="hero-badge" style="margin-bottom: 12px;">Pesanan Diterima</span>
                <h2 style="margin-bottom: 8px;">Terima Kasih, <?= htmlspecialchars($pesanan['nama_pelanggan']) ?>!</h2>
                <p style="color: var(--text-muted); margin-bottom: 22px;">
                    Pesanan Anda telah masuk dan berstatus <strong>Menunggu Konfirmasi</strong>. Simpan kode pesanan berikut untuk mengecek progres jahitan Anda:
                </p>

                <div style="background: var(--pastel-sage-light); border: 1px dashed var(--pastel-sage); border-radius: 12px; padding: 18px; margin-bottom: 22px;">
                    <div style="font-size: 0.85rem; color: var(--text-muted);">Kode Pesanan Anda</div>
                    <div style="font-size: 1.75rem; font-weight: 700; color: var(--pastel-sage); letter-spacing: 1px; margin: 4px 0;">
                        <?= htmlspecialchars($pesanan['kode_pesanan']) ?>
                    </div>
                    <div style="font-size: 0.85rem; color: var(--text-muted);">Nomor HP Terdaftar: <strong><?= htmlspecialchars($pesanan['no_hp']) ?></strong></div>
                </div>

                <div style="text-align: left; font-size: 0.92rem; margin-bottom: 24px; border-top: 1px solid var(--border-soft); padding-top: 16px;">
                    <p style="margin-bottom: 6px;"><strong>Layanan:</strong> <?= htmlspecialchars($pesanan['nama_jasa']) ?> (<?= (int) $pesanan['jumlah'] ?> pcs)</p>
                    <p style="margin-bottom: 6px;"><strong>Model:</strong> <?= htmlspecialchars($pesanan['model_pakaian']) ?></p>
                    <p style="margin-bottom: 0;"><strong>Estimasi Total Biaya:</strong> <?= format_rupiah($pesanan['total_harga']) ?></p>
                </div>

                <div style="display: flex; gap: 10px; justify-content: center; flex-wrap: wrap;">
                    <a href="<?= base_url('cek-status?kode=' . urlencode($pesanan['kode_pesanan']) . '&hp=' . urlencode($pesanan['no_hp'])) ?>" class="btn btn-primary">
                        Lihat Status Pesanan
                    </a>
                    <a href="<?= base_url('') ?>" class="btn btn-secondary">Kembali ke Beranda</a>
                </div>
            </div>
        </div>
    </section>
</main>

<?php require __DIR__ . '/../../layouts/footer.php'; ?>