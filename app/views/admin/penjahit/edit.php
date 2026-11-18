<?php require __DIR__ . '/../../layouts/header.php'; ?>
<?php require __DIR__ . '/../../layouts/navbar.php'; ?>

<div class="panel-wrap">
    <?php require __DIR__ . '/../../layouts/sidebar.php'; ?>

    <main class="panel-content">
        <div style="max-width: 560px;">
            <div style="margin-bottom: 20px;">
                <h2 style="margin-bottom: 4px;">Edit Akun Penjahit</h2>
                <p style="color: var(--text-muted); margin: 0;">Perbarui profil atau atur ulang password akun penjahit.</p>
            </div>

            <div class="card">
                <?php if ($error !== ''): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>

                <form method="POST" action="<?= base_url('admin/penjahit/edit?id=' . (int) $penjahit['id_pengguna']) ?>">
                    <div class="form-group">
                        <label for="nama_lengkap">Nama Lengkap Penjahit *</label>
                        <input type="text" id="nama_lengkap" name="nama_lengkap" class="form-control"
                               value="<?= htmlspecialchars($_POST['nama_lengkap'] ?? $penjahit['nama_lengkap']) ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="username">Username Login *</label>
                        <input type="text" id="username" name="username" class="form-control"
                               value="<?= htmlspecialchars($_POST['username'] ?? $penjahit['username']) ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="password">Password Baru (Opsional)</label>
                        <input type="password" id="password" name="password" class="form-control"
                               placeholder="Kosongkan jika tidak ingin mengganti password">
                    </div>

                    <div style="display: flex; gap: 10px;">
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        <a href="<?= base_url('admin/penjahit') ?>" class="btn btn-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </main>
</div>

<?php require __DIR__ . '/../../layouts/footer.php'; ?>