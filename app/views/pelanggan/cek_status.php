<?php require __DIR__ . '/../layouts/header.php'; ?>
<?php require __DIR__ . '/../layouts/navbar.php'; ?>

<?php
$wa_lupa = rawurlencode("Halo Admin JahitLink, saya lupa kode pesanan jahit saya. Nama: ... | No. HP: ... Mohon bantuannya.");
?>

<main>
    <section class="section">
        <div class="container" style="max-width: 820px;">
            <div class="section-header" style="text-align: left; margin-bottom: 22px;">
                <h2>Cek Status &amp; Pembayaran Pesanan</h2>
                <p>Masukkan Kode Pesanan dan Nomor HP yang digunakan saat mendaftar untuk menjaga privasi data Anda.</p>
            </div>

            <div class="card" style="margin-bottom: 24px;">
                <?php if ($error !== ''): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>

                <form method="POST" action="<?= base_url('cek-status') ?>">
                    <div class="grid-2">
                        <div class="form-group">
                            <label for="kode_pesanan">Kode Pesanan *</label>
                            <input type="text" id="kode_pesanan" name="kode_pesanan" class="form-control"
                                   value="<?= htmlspecialchars($kode) ?>" placeholder="Contoh: JHL-20261118-001" required>
                        </div>
                        <div class="form-group">
                            <label for="no_hp">Nomor HP Terdaftar *</label>
                            <input type="text" id="no_hp" name="no_hp" class="form-control"
                                   value="<?= htmlspecialchars($no_hp) ?>" placeholder="Contoh: 081234567890" required>
                        </div>
                    </div>

                    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
                        <button type="submit" class="btn btn-primary">Lihat Status Pesanan</button>
                        <a href="https://wa.me/6281245001122?text=<?= $wa_lupa ?>" target="_blank" style="font-size: 0.9rem; color: var(--pastel-sage); font-weight: 600;">
                            Lupa Kode Pesanan? Tanya Admin via WhatsApp &rarr;
                        </a>
                    </div>
                </form>
            </div>

            <?php if ($pesanan): ?>
                <?php
                $total_harga   = (float) $pesanan['total_harga'];
                $total_dibayar = (float) $pesanan['total_dibayar'];
                $sisa_tagihan  = max(0, $total_harga - $total_dibayar);
                $dp_minimal    = $total_harga * 0.5;
                $status_bayar  = ($total_dibayar <= 0) ? 'Belum Bayar' : (($total_dibayar < $total_harga) ? 'Belum Lunas' : 'Lunas');
                $wa_konfirmasi = rawurlencode("Halo Admin JahitLink, saya ingin konfirmasi pembayaran untuk Kode Pesanan {$pesanan['kode_pesanan']} a.n. {$pesanan['nama_pelanggan']}.");
                ?>
                <div class="card" style="margin-bottom: 20px;">
                    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px; border-bottom: 1px solid var(--border-soft); padding-bottom: 14px; margin-bottom: 16px;">
                        <div>
                            <span style="font-size: 0.85rem; color: var(--text-muted);">Kode Pesanan</span>
                            <h3 style="margin: 0; color: var(--pastel-sage);"><?= htmlspecialchars($pesanan['kode_pesanan']) ?></h3>
                        </div>
                        <span class="badge badge-info" style="font-size: 0.9rem;"><?= htmlspecialchars($pesanan['status_pesanan']) ?></span>
                    </div>

                    <div class="grid-2" style="margin-bottom: 16px;">
                        <div>
                            <p style="margin-bottom: 6px;"><strong>Nama Pelanggan:</strong> <?= htmlspecialchars($pesanan['nama_pelanggan']) ?></p>
                            <p style="margin-bottom: 6px;"><strong>Layanan:</strong> <?= htmlspecialchars($pesanan['nama_jasa']) ?> (<?= (int) $pesanan['jumlah'] ?> pcs)</p>
                            <p style="margin-bottom: 6px;"><strong>Model Pakaian:</strong> <?= htmlspecialchars($pesanan['model_pakaian']) ?></p>
                        </div>
                        <div>
                            <p style="margin-bottom: 6px;"><strong>Tanggal Pesan:</strong> <?= htmlspecialchars($pesanan['tanggal_pesan']) ?></p>
                            <p style="margin-bottom: 6px;"><strong>Perkiraan Selesai:</strong> <?= $pesanan['perkiraan_selesai'] ? htmlspecialchars($pesanan['perkiraan_selesai']) : 'Menunggu konfirmasi admin' ?></p>
                            <p style="margin-bottom: 6px;"><strong>Penjahit Bertugas:</strong> <?= $pesanan['nama_penjahit'] ? htmlspecialchars($pesanan['nama_penjahit']) : 'Belum ditentukan' ?></p>
                        </div>
                    </div>

                    <div style="background: var(--bg-main); border-radius: 10px; padding: 14px; margin-bottom: 16px; font-size: 0.9rem;">
                        <strong>Rincian Ukuran Badan:</strong><br>
                        Lingkar Kepala: <?= $pesanan['lingkar_kepala'] !== null ? $pesanan['lingkar_kepala'] . ' cm' : '-' ?> |
                        Lingkar Dada: <?= $pesanan['lingkar_dada'] !== null ? $pesanan['lingkar_dada'] . ' cm' : '-' ?> |
                        Lingkar Paha: <?= $pesanan['lingkar_paha'] !== null ? $pesanan['lingkar_paha'] . ' cm' : '-' ?> |
                        Lingkar Pinggang: <?= $pesanan['lingkar_pinggang'] !== null ? $pesanan['lingkar_pinggang'] . ' cm' : '-' ?> |
                        Lebar Bahu: <?= $pesanan['lebar_bahu'] !== null ? $pesanan['lebar_bahu'] . ' cm' : '-' ?>
                        <?php if (!empty($pesanan['catatan_ukuran'])): ?>
                            <br><strong>Catatan Ukuran:</strong> <?= htmlspecialchars($pesanan['catatan_ukuran']) ?>
                        <?php endif; ?>
                    </div>

                    <div class="grid-3" style="margin-bottom: 18px;">
                        <div style="padding: 12px; border: 1px solid var(--border-soft); border-radius: 10px;">
                            <div style="font-size: 0.82rem; color: var(--text-muted);">Total Tagihan</div>
                            <div style="font-weight: 700; font-size: 1.1rem;"><?= format_rupiah($total_harga) ?></div>
                        </div>
                        <div style="padding: 12px; border: 1px solid var(--border-soft); border-radius: 10px;">
                            <div style="font-size: 0.82rem; color: var(--text-muted);">Sudah Dibayar (<?= $status_bayar ?>)</div>
                            <div style="font-weight: 700; font-size: 1.1rem; color: var(--pastel-sage);"><?= format_rupiah($total_dibayar) ?></div>
                        </div>
                        <div style="padding: 12px; border: 1px solid var(--border-soft); border-radius: 10px;">
                            <div style="font-size: 0.82rem; color: var(--text-muted);">Sisa Tagihan</div>
                            <div style="font-weight: 700; font-size: 1.1rem;"><?= format_rupiah($sisa_tagihan) ?></div>
                        </div>
                    </div>

                    <?php if ($sisa_tagihan > 0): ?>
                        <div style="background: var(--pastel-sage-light); border: 1px solid var(--pastel-sage); border-radius: 10px; padding: 14px; margin-bottom: 18px; font-size: 0.9rem;">
                            <strong>Informasi Pembayaran (DP Minimal 50%: <?= format_rupiah($dp_minimal) ?>)</strong><br>
                            Silakan lakukan pembayaran tunai langsung di toko (Jl. Towua No. 24) atau transfer ke <strong>BRI 1029-01-004512-53-1 (a.n. Hj. Rahmawati)</strong>, lalu konfirmasi ke Admin agar status pembayaran diperbarui:<br>
                            <a href="https://wa.me/6281245001122?text=<?= $wa_konfirmasi ?>" target="_blank" class="btn btn-primary btn-sm" style="margin-top: 10px;">
                                Konfirmasi Pembayaran via WhatsApp
                            </a>
                        </div>
                    <?php endif; ?>

                    <h4 style="font-size: 0.98rem; margin-bottom: 10px;">Riwayat Pembayaran</h4>
                    <?php if (count($riwayat_bayar) > 0): ?>
                        <div class="table-responsive">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Jenis</th>
                                        <th>Metode</th>
                                        <th>Nominal</th>
                                        <th>Dicatat Oleh</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($riwayat_bayar as $rb): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($rb['tanggal_bayar']) ?></td>
                                            <td><?= htmlspecialchars($rb['jenis_pembayaran']) ?></td>
                                            <td><?= htmlspecialchars($rb['metode_pembayaran']) ?></td>
                                            <td><strong><?= format_rupiah($rb['jumlah_bayar']) ?></strong></td>
                                            <td><?= htmlspecialchars($rb['nama_admin']) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <p style="font-size: 0.9rem; color: var(--text-muted); margin: 0;">Belum ada pembayaran yang tercatat untuk pesanan ini.</p>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </section>
</main>

<?php require __DIR__ . '/../layouts/footer.php'; ?>