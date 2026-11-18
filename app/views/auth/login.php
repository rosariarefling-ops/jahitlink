<?php require __DIR__ . '/../layouts/header.php'; ?>
<?php require __DIR__ . '/../layouts/navbar.php'; ?>

<main>
    <section class="section">
        <div class="container" style="max-width: 440px;">
            <div class="card">
                <div style="text-align: center; margin-bottom: 22px;">
                    <h2 style="margin-bottom: 6px;">Login Staf JahitLink</h2>
                    <p style="font-size: 0.9rem; color: var(--text-muted);">Masuk sebagai Admin atau Penjahit untuk mengelola pesanan.</p>
                </div>

                <?php if ($error !== ''): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>

                <form method="POST" action="<?= base_url('login') ?>">
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" id="username" name="username" class="form-control"
                               value="<?= htmlspecialchars($_POST['username'] ?? '') ?>"
                               placeholder="Masukkan username" required autofocus>
                    </div>

                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" class="form-control"
                               placeholder="Masukkan password" required>
                    </div>

                    <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 6px;">Masuk ke Sistem</button>
                </form>
            </div>
        </div>
    </section>
</main>

<?php require __DIR__ . '/../layouts/footer.php'; ?>