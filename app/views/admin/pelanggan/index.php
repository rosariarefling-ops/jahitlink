<?php require __DIR__ . '/../../layouts/header.php'; ?>
<?php require __DIR__ . '/../../layouts/navbar.php'; ?>

<div class="panel-wrap">
    <?php require __DIR__ . '/../../layouts/sidebar.php'; ?>

    <main class="panel-content">
        <div style="margin-bottom: 22px;">
            <h2 style="margin-bottom: 4px;">Data Pelanggan</h2>
            <p style="color: var(--text-muted); margin: 0;">Daftar pelanggan yang pernah melakukan pemesanan jasa jahit.</p>
        </div>

        <!-- 1. Tambahkan Kotak Notifikasi Flash di Sini -->
        <?php if (!empty($flash)): ?>
            <div class="alert alert-<?= htmlspecialchars($flash['tipe']) ?>"><?= htmlspecialchars($flash['pesan']) ?></div>
        <?php endif; ?>

        <div class="card">
            <?php if (count($daftar_pelanggan) > 0): ?>
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th style="width: 55px;">No</th>
                                <th>Nama Pelanggan</th>
                                <th>Nomor HP / WA</th>
                                <th>Alamat</th>
                                <th>Total Pesanan</th>
                                <th>Aksi</th> <!-- 2. Tambahkan th Aksi di Sini -->
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; foreach ($daftar_pelanggan as $pl): ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><strong><?= htmlspecialchars($pl['nama_pelanggan']) ?></strong></td>
                                    <td><?= htmlspecialchars($pl['no_hp']) ?></td>
                                    <td><?= htmlspecialchars($pl['alamat'] ?: '-') ?></td>
                                    <td><span class="badge badge-info"><?= (int) $pl['total_pesanan'] ?> Pesanan</span></td>
                                    
                                    <!-- 3. Tambahkan td Aksi / Tombol Hapus di Sini -->
                                    <td>
                                        <?php if ((int) $pl['total_pesanan'] === 0): ?>
                                            <form method="POST" action="<?= base_url('admin/pelanggan/hapus') ?>" style="display:inline;" onsubmit="return confirm('Yakin ingin menghapus data pelanggan ini?');">
                                                <input type="hidden" name="id_pelanggan" value="<?= (int) $pl['id_pelanggan'] ?>">
                                                <button type="submit" class="btn btn-sm" style="background-color: #dc3545; color: white;">Hapus</button>
                                            </form>
                                        <?php else: ?>
                                            <small style="color: var(--text-muted);">Terkunci</small>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p style="color: var(--text-muted); margin: 0;">Belum ada data pelanggan tercatat.</p>
            <?php endif; ?>
        </div>
    </main>
</div>

<?php require __DIR__ . '/../../layouts/footer.php'; ?>