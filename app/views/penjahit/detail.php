<?php require __DIR__ . '/../layouts/header.php'; ?>
<?php require __DIR__ . '/../layouts/navbar.php'; ?>

<div class="panel-wrap">
    <?php require __DIR__ . '/../layouts/sidebar.php'; ?>

    <main class="panel-content">
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px; margin-bottom: 20px;">
            <div>
                <h2 style="margin-bottom: 4px;">Detail Tugas: <?= htmlspecialchars($tugas['kode_pesanan']) ?></h2>
                <p style="color: var(--text-muted); margin: 0;">Periksa ukuran badan dan perbarui progres pengerjaan.</p>
            </div>
            <a href="<?= base_url('penjahit/dashboard') ?>" class="btn btn-secondary">&larr; Kembali ke Daftar Tugas</a>
        </div>

        <?php if (!empty($flash)): ?>
            <div class="alert alert-<?= htmlspecialchars($flash['tipe']) ?>"><?= htmlspecialchars($flash['pesan']) ?></div>
        <?php endif; ?>

        <div class="grid-2">
            <div class="card">
                <h3 style="font-size: 1.05rem; margin-bottom: 12px; color: var(--pastel-sage);">Rincian Pakaian &amp; Ukuran Badan</h3>
                <p style="margin-bottom: 6px;"><strong>Pelanggan:</strong> <?= htmlspecialchars($tugas['nama_pelanggan']) ?> (<?= htmlspecialchars($tugas['no_hp']) ?>)</p>
                <p style="margin-bottom: 6px;"><strong>Jenis Layanan:</strong> <?= htmlspecialchars($tugas['nama_jasa']) ?> (<?= (int) $tugas['jumlah'] ?> pcs)</p>
                <p style="margin-bottom: 6px;"><strong>Model Pakaian:</strong> <?= htmlspecialchars($tugas['model_pakaian']) ?></p>
                <p style="margin-bottom: 14px;"><strong>Target Selesai:</strong> <?= $tugas['perkiraan_selesai'] ? htmlspecialchars($tugas['perkiraan_selesai']) : '-' ?></p>

                <div style="background: var(--bg-main); padding: 14px; border-radius: 10px; font-size: 0.92rem;">
                    <strong>Ukuran Badan (Satuan cm):</strong><br>
                    &bull; Lingkar Kepala: <strong><?= $tugas['lingkar_kepala'] !== null ? $tugas['lingkar_kepala'] . ' cm' : '-' ?></strong><br>
                    &bull; Lingkar Dada: <strong><?= $tugas['lingkar_dada'] !== null ? $tugas['lingkar_dada'] . ' cm' : '-' ?></strong><br>
                    &bull; Lingkar Paha: <strong><?= $tugas['lingkar_paha'] !== null ? $tugas['lingkar_paha'] . ' cm' : '-' ?></strong><br>
                    &bull; Lingkar Pinggang: <strong><?= $tugas['lingkar_pinggang'] !== null ? $tugas['lingkar_pinggang'] . ' cm' : '-' ?></strong><br>
                    &bull; Lebar Bahu: <strong><?= $tugas['lebar_bahu'] !== null ? $tugas['lebar_bahu'] . ' cm' : '-' ?></strong><br><br>
                    <strong>Catatan Ukuran &amp; Detail:</strong><br>
                    <?= nl2br(htmlspecialchars($tugas['catatan_ukuran'] ?: '-')) ?>
                </div>
            </div>

            <div class="card">
                <h3 style="font-size: 1.05rem; margin-bottom: 12px; color: var(--pastel-sage);">Perbarui Progres Jahitan</h3>
                <p style="margin-bottom: 14px;">Status saat ini: <span class="badge badge-info"><?= htmlspecialchars($tugas['status_pesanan']) ?></span></p>

                <?php if ($tugas['status_pesanan'] === 'Selesai'): ?>
                    <div class="alert alert-success">Pesanan ini sudah dikonfirmasi selesai dan diambil pelanggan.</div>
                <?php else: ?>
                    <form method="POST" action="<?= base_url('penjahit/pesanan/update-status') ?>">
                        <input type="hidden" name="id_pesanan" value="<?= (int) $tugas['id_pesanan'] ?>">

                        <div class="form-group">
                            <label for="status_pesanan">Pilih Status Pengerjaan Terbaru *</label>
                            <select id="status_pesanan" name="status_pesanan" class="form-control" required>
                                <?php foreach (['Dikonfirmasi', 'Sedang Dikerjakan', 'Siap Diambil'] as $st): ?>
                                    <option value="<?= $st ?>" <?= $tugas['status_pesanan'] === $st ? 'selected' : '' ?>><?= $st ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary">Simpan Status Pengerjaan</button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </main>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>