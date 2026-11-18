<?php require __DIR__ . '/../../layouts/header.php'; ?>
<?php require __DIR__ . '/../../layouts/navbar.php'; ?>

<div class="panel-wrap">
    <?php require __DIR__ . '/../../layouts/sidebar.php'; ?>

    <main class="panel-content">
        <div style="max-width: 620px;">
            <div style="margin-bottom: 20px;">
                <h2 style="margin-bottom: 4px;">Edit Jasa Jahit</h2>
                <p style="color: var(--text-muted); margin: 0;">Perubahan harga hanya berlaku untuk pesanan baru.</p>
            </div>

            <div class="card">
                <?php if ($error !== ''): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>

                <form method="POST" action="<?= base_url('admin/jasa/edit?id=' . (int) $jasa['id_jasa']) ?>">
                    <div class="form-group">
                        <label for="nama_jasa">Nama Jasa Jahit *</label>
                        <input type="text" id="nama_jasa" name="nama_jasa" class="form-control"
                               value="<?= htmlspecialchars($_POST['nama_jasa'] ?? $jasa['nama_jasa']) ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="harga">Harga Jasa (Rp) *</label>
                        <input type="number" id="harga" name="harga" class="form-control" min="0" step="1000"
                               value="<?= htmlspecialchars($_POST['harga'] ?? (int) $jasa['harga']) ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="deskripsi">Deskripsi Singkat</label>
                        <textarea id="deskripsi" name="deskripsi" rows="3" class="form-control"><?= htmlspecialchars($_POST['deskripsi'] ?? $jasa['deskripsi']) ?></textarea>
                    </div>

                    <div style="display: flex; gap: 10px;">
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        <a href="<?= base_url('admin/jasa') ?>" class="btn btn-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </main>
</div>

<?php require __DIR__ . '/../../layouts/footer.php'; ?>