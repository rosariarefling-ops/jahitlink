<?php require __DIR__ . '/../../layouts/header.php'; ?>
<?php require __DIR__ . '/../../layouts/navbar.php'; ?>

<div class="panel-wrap">
    <?php require __DIR__ . '/../../layouts/sidebar.php'; ?>

    <main class="panel-content">
        <div style="margin-bottom: 22px;">
            <h2 style="margin-bottom: 4px;">Pencatatan &amp; Riwayat Pembayaran</h2>
            <p style="color: var(--text-muted); margin: 0;">Catat pembayaran DP maupun pelunasan yang diterima secara tunai atau transfer.</p>
        </div>

        <?php if ($flash): ?>
            <div class="alert alert-<?= htmlspecialchars($flash['tipe']) ?>"><?= htmlspecialchars($flash['pesan']) ?></div>
        <?php endif; ?>

        <div class="card" style="margin-bottom: 24px;">
            <h3 style="font-size: 1.05rem; margin-bottom: 14px; color: var(--pastel-sage);">Catat Pembayaran Baru</h3>
            <?php if (count($pesanan_belum_lunas) > 0): ?>
                <form method="POST" action="<?= base_url('admin/pembayaran/simpan') ?>">
                    <div class="grid-2">
                        <div class="form-group">
                            <label for="id_pesanan">Pilih Pesanan (Belum Lunas) *</label>
                            <select id="id_pesanan" name="id_pesanan" class="form-control" required>
                                <option value="">-- Pilih Kode Pesanan --</option>
                                <?php foreach ($pesanan_belum_lunas as $pbl): ?>
                                    <?php $sisa = (float) $pbl['total_harga'] - (float) $pbl['total_dibayar']; ?>
                                    <option value="<?= (int) $pbl['id_pesanan'] ?>" <?= $id_pesanan_terpilih === (int) $pbl['id_pesanan'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($pbl['kode_pesanan']) ?> - <?= htmlspecialchars($pbl['nama_pelanggan']) ?> (Sisa: <?= format_rupiah($sisa) ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="jumlah_bayar">Nominal Pembayaran (Rp) *</label>
                            <input type="number" id="jumlah_bayar" name="jumlah_bayar" class="form-control" min="1" step="1" placeholder="Contoh: 150000" required>
                        </div>
                    </div>

                    <div class="grid-2">
                        <div class="form-group">
                            <label for="jenis_pembayaran">Jenis Pembayaran *</label>
                            <select id="jenis_pembayaran" name="jenis_pembayaran" class="form-control" required>
                                <option value="DP">DP (Uang Muka)</option>
                                <option value="Pelunasan">Pelunasan</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="metode_pembayaran">Metode Pembayaran *</label>
                            <select id="metode_pembayaran" name="metode_pembayaran" class="form-control" required>
                                <option value="Tunai">Tunai</option>
                                <option value="Transfer">Transfer</option>
                            </select>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">Simpan Transaksi Pembayaran</button>
                </form>
            <?php else: ?>
                <p style="color: var(--text-muted); margin: 0;">Seluruh pesanan yang masuk saat ini sudah berstatus Lunas.</p>
            <?php endif; ?>
        </div>

        <div class="card">
            <h3 style="font-size: 1.05rem; margin-bottom: 14px;">Riwayat Seluruh Pembayaran</h3>
            <?php if (count($daftar_pembayaran) > 0): ?>
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Kode Pesanan</th>
                                <th>Pelanggan</th>
                                <th>Jenis</th>
                                <th>Metode</th>
                                <th>Nominal</th>
                                <th>Admin Pencatat</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($daftar_pembayaran as $pb): ?>
                                <tr>
                                    <td><?= htmlspecialchars($pb['tanggal_bayar']) ?></td>
                                    <td><a href="<?= base_url('admin/pesanan/detail?id=' . (int) $pb['id_pesanan']) ?>"><strong><?= htmlspecialchars($pb['kode_pesanan']) ?></strong></a></td>
                                    <td><?= htmlspecialchars($pb['nama_pelanggan']) ?></td>
                                    <td><?= htmlspecialchars($pb['jenis_pembayaran']) ?></td>
                                    <td><?= htmlspecialchars($pb['metode_pembayaran']) ?></td>
                                    <td><strong><?= format_rupiah($pb['jumlah_bayar']) ?></strong></td>
                                    <td><?= htmlspecialchars($pb['nama_admin']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p style="color: var(--text-muted); margin: 0;">Belum ada transaksi pembayaran yang tercatat.</p>
            <?php endif; ?>
        </div>
    </main>
</div>

<?php require __DIR__ . '/../../layouts/footer.php'; ?>