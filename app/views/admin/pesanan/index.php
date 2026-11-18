<?php require __DIR__ . '/../../layouts/header.php'; ?>
<?php require __DIR__ . '/../../layouts/navbar.php'; ?>

<div class="panel-wrap">
    <?php require __DIR__ . '/../../layouts/sidebar.php'; ?>

    <main class="panel-content">
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px; margin-bottom: 22px;">
            <div>
                <h2 style="margin-bottom: 4px;">Kelola Pesanan Jahit</h2>
                <p style="color: var(--text-muted); margin: 0;">Konfirmasi pesanan masuk, tetapkan penjahit, dan pantau progres penyelesaian.</p>
            </div>
            <form method="GET" action="<?= base_url('admin/pesanan') ?>" style="display: flex; gap: 8px;">
                <select name="status" class="form-control" onchange="this.form.submit()">
                    <option value="">Semua Status</option>
                    <?php foreach (['Menunggu Konfirmasi', 'Dikonfirmasi', 'Sedang Dikerjakan', 'Siap Diambil', 'Selesai'] as $st): ?>
                        <option value="<?= $st ?>" <?= $filter_status === $st ? 'selected' : '' ?>><?= $st ?></option>
                    <?php endforeach; ?>
                </select>
            </form>
        </div>

        <?php if ($flash): ?>
            <div class="alert alert-<?= htmlspecialchars($flash['tipe']) ?>"><?= htmlspecialchars($flash['pesan']) ?></div>
        <?php endif; ?>

        <div class="card">
            <?php if (count($daftar_pesanan) > 0): ?>
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>Kode</th>
                                <th>Pelanggan</th>
                                <th>Layanan &amp; Jumlah</th>
                                <th>Penjahit</th>
                                <th>Tagihan &amp; Bayar</th>
                                <th>Status Jahit</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($daftar_pesanan as $ps): ?>
                                <?php
                                $total = (float) $ps['total_harga'];
                                $bayar = (float) $ps['total_dibayar'];
                                $status_bayar = ($bayar <= 0) ? 'Belum Bayar' : (($bayar < $total) ? 'Belum Lunas' : 'Lunas');
                                ?>
                                <tr>
                                    <td><strong><?= htmlspecialchars($ps['kode_pesanan']) ?></strong><br><small style="color: var(--text-muted);"><?= htmlspecialchars($ps['tanggal_pesan']) ?></small></td>
                                    <td><?= htmlspecialchars($ps['nama_pelanggan']) ?><br><small style="color: var(--text-muted);"><?= htmlspecialchars($ps['no_hp']) ?></small></td>
                                    <td><?= htmlspecialchars($ps['nama_jasa']) ?> (<?= (int) $ps['jumlah'] ?> pcs)</td>
                                    <td><?= $ps['nama_penjahit'] ? htmlspecialchars($ps['nama_penjahit']) : '<em style="color: var(--text-muted);">Belum ditugaskan</em>' ?></td>
                                    <td>
                                        <strong><?= format_rupiah($total) ?></strong><br>
                                        <small><?= $status_bayar ?></small>
                                    </td>
                                    <td><span class="badge badge-info"><?= htmlspecialchars($ps['status_pesanan']) ?></span></td>
                                    <td>
                                        <div style="display: flex; gap: 6px; align-items: center; flex-wrap: wrap;">
                                            <a href="<?= base_url('admin/pesanan/detail?id=' . (int) $ps['id_pesanan']) ?>" class="btn btn-primary btn-sm">Detail &amp; Kelola</a>

                                            <?php if ($ps['status_pesanan'] === 'Menunggu Konfirmasi'): ?>
                                                <form method="POST" action="<?= base_url('admin/pesanan/hapus') ?>" style="margin: 0;"
                                                      onsubmit="return confirm('Yakin ingin menghapus pesanan <?= htmlspecialchars($ps['kode_pesanan']) ?>? Data yang dihapus tidak dapat dikembalikan.');">
                                                    <input type="hidden" name="id_pesanan" value="<?= (int) $ps['id_pesanan'] ?>">
                                                    <button type="submit" class="btn btn-sm" style="background-color: #dc3545; color: #fff; border: none; cursor: pointer;">Hapus</button>
                                                </form>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p style="color: var(--text-muted); margin: 0;">Tidak ada data pesanan untuk ditampilkan.</p>
            <?php endif; ?>
        </div>
    </main>
</div>

<?php require __DIR__ . '/../../layouts/footer.php'; ?>