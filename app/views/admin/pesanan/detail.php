<?php require __DIR__ . '/../../layouts/header.php'; ?>
<?php require __DIR__ . '/../../layouts/navbar.php'; ?>

<?php
$total_harga   = (float) $pesanan['total_harga'];
$total_dibayar = (float) $pesanan['total_dibayar'];
$sisa_tagihan  = max(0, $total_harga - $total_dibayar);
$dp_saran      = ($total_dibayar <= 0) ? ($total_harga * 0.5) : $sisa_tagihan;
$jenis_saran   = ($total_dibayar <= 0) ? 'DP' : 'Pelunasan';
$status_bayar  = ($total_dibayar <= 0) ? 'Belum Bayar' : (($total_dibayar < $total_harga) ? 'Belum Lunas' : 'Lunas');
?>

<div class="panel-wrap">
    <?php require __DIR__ . '/../../layouts/sidebar.php'; ?>

    <main class="panel-content">
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px; margin-bottom: 20px;">
            <div>
                <h2 style="margin-bottom: 4px;">Kelola Pesanan: <?= htmlspecialchars($pesanan['kode_pesanan']) ?></h2>
                <p style="color: var(--text-muted); margin: 0;">Pemesan: <strong><?= htmlspecialchars($pesanan['nama_pelanggan']) ?></strong> (<?= htmlspecialchars($pesanan['no_hp']) ?>)</p>
            </div>
            <a href="<?= base_url('admin/pesanan') ?>" class="btn btn-secondary">&larr; Kembali</a>
        </div>

        <?php if ($flash): ?>
            <div class="alert alert-<?= htmlspecialchars($flash['tipe']) ?>"><?= htmlspecialchars($flash['pesan']) ?></div>
        <?php endif; ?>

        <div class="grid-2" style="margin-bottom: 20px;">
            <div class="card">
                <h3 style="font-size: 1.05rem; margin-bottom: 12px; color: var(--pastel-sage);">1. Penugasan Penjahit &amp; Status</h3>
                <form method="POST" action="<?= base_url('admin/pesanan/update') ?>">
                    <input type="hidden" name="id_pesanan" value="<?= (int) $pesanan['id_pesanan'] ?>">

                    <div class="form-group">
                        <label for="id_penjahit">Tugaskan Penjahit *</label>
                        <select id="id_penjahit" name="id_penjahit" class="form-control">
                            <option value="0">-- Pilih Penjahit --</option>
                            <?php foreach ($daftar_penjahit as $pj): ?>
                                <option value="<?= (int) $pj['id_pengguna'] ?>" <?= (int) $pesanan['id_penjahit'] === (int) $pj['id_pengguna'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($pj['nama_lengkap']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="grid-2">
                        <div class="form-group">
                            <label for="harga_satuan">Harga Satuan (Rp) *</label>
                            <input type="number" id="harga_satuan" name="harga_satuan" class="form-control" min="0" step="1000"
                                   value="<?= (int) $pesanan['harga_satuan'] ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="perkiraan_selesai">Target Selesai</label>
                            <input type="date" id="perkiraan_selesai" name="perkiraan_selesai" class="form-control"
                                   value="<?= htmlspecialchars($pesanan['perkiraan_selesai'] ?: date('Y-m-d', strtotime('+7 days'))) ?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="status_pesanan">Status Pesanan *</label>
                        <select id="status_pesanan" name="status_pesanan" class="form-control" required>
                            <?php foreach (['Menunggu Konfirmasi', 'Dikonfirmasi', 'Sedang Dikerjakan', 'Siap Diambil', 'Selesai'] as $st): ?>
                                <option value="<?= $st ?>" <?= $pesanan['status_pesanan'] === $st ? 'selected' : '' ?>><?= $st ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary">Simpan Perubahan Pesanan</button>
                </form>
            </div>

            <div class="card">
                <h3 style="font-size: 1.05rem; margin-bottom: 8px; color: var(--pastel-sage);">2. Catat Pembayaran Langsung (<?= $status_bayar ?>)</h3>
                <p style="font-size: 0.9rem; margin-bottom: 14px;">
                    Total: <strong><?= format_rupiah($total_harga) ?></strong> |
                    Dibayar: <strong><?= format_rupiah($total_dibayar) ?></strong> |
                    Sisa: <strong><?= format_rupiah($sisa_tagihan) ?></strong>
                </p>

                <?php if ($sisa_tagihan > 0): ?>
                    <form method="POST" action="<?= base_url('admin/pembayaran/simpan') ?>">
                        <input type="hidden" name="id_pesanan" value="<?= (int) $pesanan['id_pesanan'] ?>">
                        <input type="hidden" name="redirect_detail" value="1">

                        <div class="grid-2">
                            <div class="form-group">
                                <label for="jenis_pembayaran">Jenis *</label>
                                <select id="jenis_pembayaran" name="jenis_pembayaran" class="form-control" required>
                                    <option value="DP" <?= $jenis_saran === 'DP' ? 'selected' : '' ?>>DP (Uang Muka)</option>
                                    <option value="Pelunasan" <?= $jenis_saran === 'Pelunasan' ? 'selected' : '' ?>>Pelunasan</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="metode_pembayaran">Metode *</label>
                                <select id="metode_pembayaran" name="metode_pembayaran" class="form-control" required>
                                    <option value="Transfer">Transfer</option>
                                    <option value="Tunai">Tunai</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="jumlah_bayar">Nominal Bayar (Rp) — Otomatis disarankan *</label>
                            <input type="number" id="jumlah_bayar" name="jumlah_bayar" class="form-control"
                                   min="1" max="<?= (int) $sisa_tagihan ?>" value="<?= (int) $dp_saran ?>" required>
                        </div>

                        <button type="submit" class="btn btn-primary">Catat Pembayaran Ini</button>
                    </form>
                <?php else: ?>
                    <div class="alert alert-success" style="margin-bottom: 0;">Tagihan pesanan ini sudah <strong>Lunas</strong> sepenuhnya.</div>
                <?php endif; ?>
            </div>
        </div>

        <div class="card">
            <h3 style="font-size: 1.02rem; margin-bottom: 10px;">Rincian Pakaian &amp; Ukuran Pelanggan</h3>
            <p style="font-size: 0.9rem; margin-bottom: 8px;">
                <strong>Layanan:</strong> <?= htmlspecialchars($pesanan['nama_jasa']) ?> (<?= (int) $pesanan['jumlah'] ?> pcs) &bull;
                <strong>Model:</strong> <?= htmlspecialchars($pesanan['model_pakaian']) ?> &bull;
                <strong>Alamat:</strong> <?= htmlspecialchars($pesanan['alamat'] ?: '-') ?>
            </p>
            <p style="font-size: 0.9rem; margin: 0; color: var(--text-muted);">
                Kepala: <?= $pesanan['lingkar_kepala'] !== null ? $pesanan['lingkar_kepala'] . ' cm' : '-' ?> |
                Dada: <?= $pesanan['lingkar_dada'] !== null ? $pesanan['lingkar_dada'] . ' cm' : '-' ?> |
                Paha: <?= $pesanan['lingkar_paha'] !== null ? $pesanan['lingkar_paha'] . ' cm' : '-' ?> |
                Pinggang: <?= $pesanan['lingkar_pinggang'] !== null ? $pesanan['lingkar_pinggang'] . ' cm' : '-' ?> |
                Bahu: <?= $pesanan['lebar_bahu'] !== null ? $pesanan['lebar_bahu'] . ' cm' : '-' ?> |
                Catatan: <?= htmlspecialchars($pesanan['catatan_ukuran'] ?: '-') ?>
            </p>
        </div>
    </main>
</div>

<?php require __DIR__ . '/../../layouts/footer.php'; ?>