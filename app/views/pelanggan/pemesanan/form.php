<?php require __DIR__ . '/../../layouts/header.php'; ?>
<?php require __DIR__ . '/../../layouts/navbar.php'; ?>

<main>
    <section class="section">
        <div class="container" style="max-width: 820px;">
            <div class="section-header" style="text-align: left; margin-bottom: 24px;">
                <h2>Formulir Pemesanan Jasa Jahit</h2>
                <p>Lengkapi data diri, jenis layanan, ukuran badan, serta pilihan metode pembayaran Anda tanpa perlu login.</p>
            </div>

            <?php if ($error !== ''): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <form method="POST" action="<?= base_url('pesan') ?>">
                <div class="card" style="margin-bottom: 20px;">
                    <h3 style="font-size: 1.05rem; margin-bottom: 16px; color: var(--pastel-sage);">1. Data Pemesan</h3>
                    <div class="grid-2">
                        <div class="form-group">
                            <label for="nama_pelanggan">Nama Lengkap *</label>
                            <input type="text" id="nama_pelanggan" name="nama_pelanggan" class="form-control"
                                   value="<?= htmlspecialchars($_POST['nama_pelanggan'] ?? '') ?>"
                                   placeholder="Masukkan nama lengkap Anda" required>
                        </div>
                        <div class="form-group">
                            <label for="no_hp">Nomor HP / WhatsApp *</label>
                            <input type="text" id="no_hp" name="no_hp" class="form-control"
                                   value="<?= htmlspecialchars($_POST['no_hp'] ?? '') ?>"
                                   placeholder="Contoh: 081234567890" required>
                        </div>
                    </div>
                    <div class="form-group" style="margin-bottom: 0;">
                        <label for="alamat">Alamat Lengkap</label>
                        <textarea id="alamat" name="alamat" rows="2" class="form-control"
                                  placeholder="Masukkan alamat tempat tinggal Anda"><?= htmlspecialchars($_POST['alamat'] ?? '') ?></textarea>
                    </div>
                </div>

                <div class="card" style="margin-bottom: 20px;">
                    <h3 style="font-size: 1.05rem; margin-bottom: 16px; color: var(--pastel-sage);">2. Detail Layanan &amp; Pilihan Pembayaran</h3>
                    <div class="grid-2">
                        <div class="form-group">
                            <label for="id_jasa">Pilih Layanan Jahit *</label>
                            <select id="id_jasa" name="id_jasa" class="form-control" required>
                                <option value="">-- Pilih Jenis Layanan --</option>
                                <?php foreach ($daftar_jasa as $jasa): ?>
                                    <option value="<?= (int) $jasa['id_jasa'] ?>" <?= $id_jasa_terpilih === (int) $jasa['id_jasa'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($jasa['nama_jasa']) ?> (<?= format_rupiah($jasa['harga']) ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="jumlah">Jumlah Pakaian (pcs) *</label>
                            <input type="number" id="jumlah" name="jumlah" class="form-control" min="1"
                                   value="<?= htmlspecialchars($_POST['jumlah'] ?? '1') ?>" required>
                        </div>
                    </div>
                    <div class="grid-2" style="margin-bottom: 0;">
                        <div class="form-group" style="margin-bottom: 0;">
                            <label for="model_pakaian">Model Pakaian &amp; Keterangan Kain *</label>
                            <input type="text" id="model_pakaian" name="model_pakaian" class="form-control"
                                   value="<?= htmlspecialchars($_POST['model_pakaian'] ?? '') ?>"
                                   placeholder="Contoh: Kebaya Modern (Bawa kain sendiri)" required>
                        </div>
                        <div class="form-group" style="margin-bottom: 0;">
                            <label for="rencana_bayar">Pilihan Skema &amp; Metode Pembayaran *</label>
                            <select id="rencana_bayar" name="rencana_bayar" class="form-control" required>
                                <option value="DP 50% - Transfer Bank">DP 50% via Transfer Bank</option>
                                <option value="DP 50% - Tunai di Toko">DP 50% Tunai saat Antar Kain</option>
                                <option value="Lunas di Awal - Transfer Bank">Lunas 100% via Transfer Bank</option>
                                <option value="Lunas di Awal - Tunai di Toko">Lunas 100% Tunai di Toko</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="card" style="margin-bottom: 24px;">
                    <h3 style="font-size: 1.05rem; margin-bottom: 6px; color: var(--pastel-sage);">3. Ukuran Badan &amp; Pakaian (Satuan cm)</h3>
                    <p style="font-size: 0.88rem; color: var(--text-muted); margin-bottom: 16px;">
                        Kosongkan bagian ukuran yang tidak diperlukan (atau tulis di catatan jika ingin diukur langsung di tempat usaha).
                    </p>
                    <div class="grid-3">
                        <div class="form-group">
                            <label for="lingkar_kepala">Lingkar Kepala (cm)</label>
                            <input type="number" step="0.01" min="0" id="lingkar_kepala" name="lingkar_kepala" class="form-control"
                                   value="<?= htmlspecialchars($_POST['lingkar_kepala'] ?? '') ?>" placeholder="Contoh: 55">
                        </div>
                        <div class="form-group">
                            <label for="lingkar_dada">Lingkar Dada (cm)</label>
                            <input type="number" step="0.01" min="0" id="lingkar_dada" name="lingkar_dada" class="form-control"
                                   value="<?= htmlspecialchars($_POST['lingkar_dada'] ?? '') ?>" placeholder="Contoh: 92">
                        </div>
                        <div class="form-group">
                            <label for="lingkar_paha">Lingkar Paha (cm)</label>
                            <input type="number" step="0.01" min="0" id="lingkar_paha" name="lingkar_paha" class="form-control"
                                   value="<?= htmlspecialchars($_POST['lingkar_paha'] ?? '') ?>" placeholder="Contoh: 56">
                        </div>
                        <div class="form-group">
                            <label for="lingkar_pinggang">Lingkar Pinggang (cm)</label>
                            <input type="number" step="0.01" min="0" id="lingkar_pinggang" name="lingkar_pinggang" class="form-control"
                                   value="<?= htmlspecialchars($_POST['lingkar_pinggang'] ?? '') ?>" placeholder="Contoh: 74">
                        </div>
                        <div class="form-group">
                            <label for="lebar_bahu">Lebar Bahu (cm)</label>
                            <input type="number" step="0.01" min="0" id="lebar_bahu" name="lebar_bahu" class="form-control"
                                   value="<?= htmlspecialchars($_POST['lebar_bahu'] ?? '') ?>" placeholder="Contoh: 38">
                        </div>
                    </div>
                    <div class="form-group" style="margin-bottom: 0;">
                        <label for="catatan_ukuran">Catatan Ukuran &amp; Detail Tambahan</label>
                        <textarea id="catatan_ukuran" name="catatan_ukuran" rows="2" class="form-control"
                                  placeholder="Contoh: Panjang baju 75 cm, panjang lengan 55 cm..."><?= htmlspecialchars($_POST['catatan_ukuran'] ?? '') ?></textarea>
                    </div>
                </div>

                <div style="display: flex; gap: 12px;">
                    <button type="submit" class="btn btn-primary">Kirim Pesanan Jahit</button>
                    <a href="<?= base_url('') ?>" class="btn btn-secondary">Kembali ke Beranda</a>
                </div>
            </form>
        </div>
    </section>
</main>

<?php require __DIR__ . '/../../layouts/footer.php'; ?>