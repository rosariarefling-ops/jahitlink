<?php require __DIR__ . '/../../layouts/header.php'; ?>
<?php require __DIR__ . '/../../layouts/navbar.php'; ?>

<div class="panel-wrap">
    <?php require __DIR__ . '/../../layouts/sidebar.php'; ?>

    <main class="panel-content">
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px; margin-bottom: 22px;">
            <div>
                <h2 style="margin-bottom: 4px;">Kelola Akun Penjahit</h2>
                <p style="color: var(--text-muted); margin: 0;">Daftar penjahit yang memiliki akses masuk ke sistem.</p>
            </div>
            <a href="<?= base_url('admin/penjahit/tambah') ?>" class="btn btn-primary">+ Tambah Penjahit</a>
        </div>

        <?php if ($flash): ?>
            <div class="alert alert-<?= htmlspecialchars($flash['tipe']) ?>"><?= htmlspecialchars($flash['pesan']) ?></div>
        <?php endif; ?>

        <div class="card">
            <?php if (count($daftar_penjahit) > 0): ?>
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th style="width: 55px;">No</th>
                                <th>Nama Lengkap Penjahit</th>
                                <th>Username Login</th>
                                <th>Peran</th>
                                <th style="width: 170px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; foreach ($daftar_penjahit as $p): ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><strong><?= htmlspecialchars($p['nama_lengkap']) ?></strong></td>
                                    <td><code><?= htmlspecialchars($p['username']) ?></code></td>
                                    <td><span class="badge badge-info"><?= htmlspecialchars($p['role']) ?></span></td>
                                    <td>
                                        <a href="<?= base_url('admin/penjahit/edit?id=' . (int) $p['id_pengguna']) ?>" class="btn btn-outline btn-sm">Edit</a>
                                        <form method="POST" action="<?= base_url('admin/penjahit/hapus') ?>" style="display: inline;" onsubmit="return confirm('Yakin ingin menghapus akun penjahit ini?');">
                                            <input type="hidden" name="id_pengguna" value="<?= (int) $p['id_pengguna'] ?>">
                                            <button type="submit" class="btn btn-secondary btn-sm">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p style="color: var(--text-muted); margin: 0;">Belum ada data penjahit yang terdaftar.</p>
            <?php endif; ?>
        </div>
    </main>
</div>

<?php require __DIR__ . '/../../layouts/footer.php'; ?>