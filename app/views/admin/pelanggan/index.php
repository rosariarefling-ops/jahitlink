<?php require __DIR__ . '/../../layouts/header.php'; ?>
<?php require __DIR__ . '/../../layouts/navbar.php'; ?>

<div class="panel-wrap">
    <?php require __DIR__ . '/../../layouts/sidebar.php'; ?>

    <main class="panel-content">
        <div style="margin-bottom: 22px;">
            <h2 style="margin-bottom: 4px;">Data Pelanggan</h2>
            <p style="color: var(--text-muted); margin: 0;">Daftar pelanggan yang pernah melakukan pemesanan jasa jahit.</p>
        </div>

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