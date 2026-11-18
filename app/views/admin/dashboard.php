<?php require __DIR__ . '/../layouts/header.php'; ?>
<?php require __DIR__ . '/../layouts/navbar.php'; ?>

<div class="panel-wrap">
    <?php require __DIR__ . '/../layouts/sidebar.php'; ?>

    <main class="panel-content">
        <div style="margin-bottom: 24px;">
            <h2 style="margin-bottom: 4px;">Dashboard Admin</h2>
            <p style="color: var(--text-muted); margin: 0;">Ringkasan operasional usaha penjahitan JahitLink.</p>
        </div>

        <div class="grid-3" style="margin-bottom: 28px;">
            <div class="card">
                <span style="font-size: 0.85rem; color: var(--text-muted);">Total Semua Pesanan</span>
                <div class="stat-value"><?= $total_pesanan ?></div>
            </div>
            <div class="card">
                <span style="font-size: 0.85rem; color: var(--text-muted);">Pesanan Aktif / Berjalan</span>
                <div class="stat-value"><?= $pesanan_aktif ?></div>
            </div>
            <div class="card">
                <span style="font-size: 0.85rem; color: var(--text-muted);">Total Pemasukan Tercatat</span>
                <div class="stat-value" style="font-size: 1.45rem;"><?= format_rupiah($total_pemasukan) ?></div>
            </div>
            <div class="card">
                <span style="font-size: 0.85rem; color: var(--text-muted);">Jenis Jasa Jahit</span>
                <div class="stat-value"><?= $total_jasa ?></div>
            </div>
            <div class="card">
                <span style="font-size: 0.85rem; color: var(--text-muted);">Jumlah Penjahit</span>
                <div class="stat-value"><?= $total_penjahit ?></div>
            </div>
        </div>

        <div class="card">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                <h3 style="margin: 0; font-size: 1.1rem;">Pesanan Terbaru</h3>
                <a href="<?= base_url('admin/pesanan') ?>" class="btn btn-outline btn-sm">Lihat Semua Pesanan</a>
            </div>

            <?php if (count($pesanan_terbaru) > 0): ?>
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>Kode Pesanan</th>
                                <th>Pelanggan</th>
                                <th>Layanan</th>
                                <th>Jumlah</th>
                                <th>Total Biaya</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($pesanan_terbaru as $p): ?>
                                <tr>
                                    <td><strong><?= htmlspecialchars($p['kode_pesanan']) ?></strong></td>
                                    <td><?= htmlspecialchars($p['nama_pelanggan']) ?></td>
                                    <td><?= htmlspecialchars($p['nama_jasa']) ?></td>
                                    <td><?= (int) $p['jumlah'] ?> pcs</td>
                                    <td><?= format_rupiah($p['total_harga']) ?></td>
                                    <td><span class="badge badge-info"><?= htmlspecialchars($p['status_pesanan']) ?></span></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p style="color: var(--text-muted); margin: 0;">Belum ada pesanan masuk.</p>
            <?php endif; ?>
        </div>
    </main>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>