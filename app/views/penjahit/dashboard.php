<?php require __DIR__ . '/../layouts/header.php'; ?>
<?php require __DIR__ . '/../layouts/navbar.php'; ?>

<div class="panel-wrap">
    <?php require __DIR__ . '/../layouts/sidebar.php'; ?>

    <main class="panel-content">
        <div style="margin-bottom: 22px;">
            <h2 style="margin-bottom: 4px;">Daftar Tugas Jahitan Saya</h2>
            <p style="color: var(--text-muted); margin: 0;">Pesanan yang ditugaskan oleh admin kepada Anda.</p>
        </div>

        <?php if (!empty($flash)): ?>
            <div class="alert alert-<?= htmlspecialchars($flash['tipe']) ?>"><?= htmlspecialchars($flash['pesan']) ?></div>
        <?php endif; ?>

        <div class="card">
            <?php if (count($daftar_tugas) > 0): ?>
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>Kode Pesanan</th>
                                <th>Pelanggan</th>
                                <th>Layanan &amp; Model</th>
                                <th>Jumlah</th>
                                <th>Perkiraan Selesai</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($daftar_tugas as $tugas): ?>
                                <tr>
                                    <td><strong><?= htmlspecialchars($tugas['kode_pesanan']) ?></strong></td>
                                    <td><?= htmlspecialchars($tugas['nama_pelanggan']) ?></td>
                                    <td><?= htmlspecialchars($tugas['nama_jasa']) ?><br><small style="color: var(--text-muted);"><?= htmlspecialchars($tugas['model_pakaian']) ?></small></td>
                                    <td><?= (int) $tugas['jumlah'] ?> pcs</td>
                                    <td><?= $tugas['perkiraan_selesai'] ? htmlspecialchars($tugas['perkiraan_selesai']) : '-' ?></td>
                                    <td><span class="badge badge-info"><?= htmlspecialchars($tugas['status_pesanan']) ?></span></td>
                                    <td>
                                        <a href="<?= base_url('penjahit/pesanan/detail?id=' . (int) $tugas['id_pesanan']) ?>" class="btn btn-primary btn-sm">Detail &amp; Update</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p style="color: var(--text-muted); margin: 0;">Belum ada tugas pesanan yang ditugaskan kepada Anda saat ini.</p>
            <?php endif; ?>
        </div>
    </main>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>