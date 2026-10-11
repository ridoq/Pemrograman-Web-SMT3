<?php
require __DIR__ . '/../includes/auth.php';
$page_title = "Riwayat Peminjaman";
include __DIR__ . '/../includes/header.php';
require __DIR__ . '/../includes/koneksi.php';

$anggotaId = (int)($_GET['anggota_id'] ?? 0);
$daftarAnggota = $pdo->query("SELECT * FROM anggota ORDER BY nama")->fetchAll(PDO::FETCH_ASSOC);

$riwayat = [];
$anggotaTerpilih = null;

if ($anggotaId > 0) {
    $stmtA = $pdo->prepare("SELECT * FROM anggota WHERE id = :id");
    $stmtA->execute(['id' => $anggotaId]);
    $anggotaTerpilih = $stmtA->fetch(PDO::FETCH_ASSOC);

    if ($anggotaTerpilih) {
        $stmt = $pdo->prepare(
            "SELECT b.judul, b.kategori, p.tanggal_pinjam, p.tanggal_kembali, p.status
             FROM peminjaman p
             JOIN buku b ON b.id = p.buku_id
             WHERE p.anggota_id = :id
             ORDER BY p.tanggal_pinjam DESC, p.id DESC"
        );
        $stmt->execute(['id' => $anggotaId]);
        $riwayat = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
        <section>
            <h2>Riwayat Peminjaman Buku</h2>

            <form method="get" action="riwayat.php" style="margin-bottom: 1.5rem;">
                <p>
                    <label for="anggota_id">Pilih Anggota</label><br>
                    <select id="anggota_id" name="anggota_id" onchange="this.form.submit()">
                        <option value="">-- Pilih Anggota --</option>
                        <?php foreach ($daftarAnggota as $anggota): ?>
                        <option value="<?php echo $anggota['id']; ?>" <?php echo $anggotaId === (int)$anggota['id'] ? 'selected' : ''; ?>>
                            <?php echo e($anggota['nama']); ?> (<?php echo e($anggota['no_anggota']); ?>)
                        </option>
                        <?php endforeach; ?>
                    </select>
                    <button type="submit">Tampilkan</button>
                </p>
            </form>

            <?php if ($anggotaTerpilih): ?>
            <h3>Riwayat &mdash; <?php echo e($anggotaTerpilih['nama']); ?> (<?php echo e($anggotaTerpilih['no_anggota']); ?>)</h3>
            <div class="table-responsive" style="margin-top: 1rem;">
            <table>
                <thead>
                    <tr>
                        <th>Buku</th>
                        <th>Kategori</th>
                        <th>Tgl Pinjam</th>
                        <th>Tgl Kembali</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($riwayat)): ?>
                    <tr>
                        <td colspan="5" style="text-align: center; padding: 1.5rem;">Belum ada riwayat peminjaman untuk anggota ini.</td>
                    </tr>
                    <?php else: ?>
                        <?php foreach ($riwayat as $r): ?>
                        <tr>
                            <td><strong><?php echo e($r['judul']); ?></strong></td>
                            <td><?php echo e($r['kategori'] ?? '-'); ?></td>
                            <td><?php echo $r['tanggal_pinjam']; ?></td>
                            <td><?php echo $r['tanggal_kembali'] ?? '-'; ?></td>
                            <td>
                                <?php if ($r['status'] === 'dipinjam'): ?>
                                    <span style="display: inline-block; background-color: #f0ad4e; color: #fff; font-size: 0.8rem; padding: 2px 8px; border-radius: 4px; font-weight: bold;">Dipinjam</span>
                                <?php else: ?>
                                    <span style="display: inline-block; background-color: #5cb85c; color: #fff; font-size: 0.8rem; padding: 2px 8px; border-radius: 4px; font-weight: bold;">Selesai</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
            </div>
            <?php else: ?>
                <p style="color: #666; font-style: italic;">Silakan pilih salah satu anggota di atas untuk melihat riwayat peminjamannya.</p>
            <?php endif; ?>
        </section>
<?php include __DIR__ . '/../includes/footer.php'; ?>
