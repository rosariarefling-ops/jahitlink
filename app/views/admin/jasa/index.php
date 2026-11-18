<?php require __DIR__ . '/../../layouts/header.php'; ?>
<?php require __DIR__ . '/../../layouts/navbar.php'; ?>

<div class="panel-wrap">
    <?php require __DIR__ . '/../../layouts/sidebar.php'; ?>

    <main class="panel-content">
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px; margin-bottom: 22px;">
            <div>
                <h2 style="margin-bottom: 4px;">Kelola Jasa Jahit</h2>
                <p style="color: var(--text-muted); margin: 0;">Daftar layanan penjahitan beserta tarif dasarnya.</p>
            </div>
            <a href="<?= base_url('admin/jasa/tambah') ?>" class="btn btn-primary">+ Tambah Jasa Jahit</a>
        </div>

        <?php if ($flash): ?>
            <div class="alert alert-<?= htmlspecialchars($flash['tipe']) ?>"><?= htmlspecialchars($flash['pesan']) ?></div>
        <?php endif; ?>

        <div class="card">
            <?php if (count($daftar_jasa) > 0): ?>
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th style="width: 55px;">No</th>
                                <th>Nama Jasa Jahit</th>
                                <th>Deskripsi Layanan</th>
                                <th>Harga</th>
                                <th style="width: 170px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; foreach ($daftar_jasa as $jasa): ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><strong><?= htmlspecialchars($jasa['nama_jasa']) ?></strong></td>
                                    <td><?= htmlspecialchars($jasa['deskripsi'] ?: '-') ?></td>
                                    <td><strong><?= format_rupiah($jasa['harga']) ?></strong></td>
                                    <td>
                                        <a href="<?= base_url('admin/jasa/edit?id=' . (int) $jasa['id_jasa']) ?>" class="btn btn-outline btn-sm">Edit</a>
                                        <form method="POST" action="<?= base_url('admin/jasa/hapus') ?>" style="display: inline;" onsubmit="return confirm('Yakin ingin menghapus layanan jasa ini?');">
                                            <input type="hidden" name="id_jasa" value="<?= (int) $jasa['id_jasa'] ?>">
                                            <button type="submit" class="btn btn-secondary btn-sm">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p style="color: var(--text-muted); margin: 0;">Belum ada data jasa jahit. Silakan tambahkan layanan baru.</p>
            <?php endif; ?>
        </div>
    </main>
</div>

<?php require __DIR__ . '/../../layouts/footer.php'; ?>