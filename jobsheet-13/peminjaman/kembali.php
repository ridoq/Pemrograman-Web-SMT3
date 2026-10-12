<?php
require __DIR__ . '/../includes/auth.php';
$page_title = "Pengembalian Buku";
include __DIR__ . '/../includes/header.php';
require __DIR__ . '/../includes/koneksi.php';

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

$keyword = trim($_GET['q'] ?? '');

// Multi-table JOIN dengan atribut kontak no_hp dan kalkulasi tanggal jatuh tempo (14 hari)
$sqlDasar = "SELECT p.id, b.judul, b.kategori, a.nama, a.no_hp, p.tanggal_pinjam,
                    (p.tanggal_pinjam + INTERVAL '14 days')::DATE AS tanggal_jatuh_tempo,
                    (CURRENT_DATE - (p.tanggal_pinjam + 14)) AS hari_terlambat
             FROM peminjaman p
             JOIN buku b ON b.id = p.buku_id
             JOIN anggota a ON a.id = p.anggota_id
             WHERE p.status = 'dipinjam'";

if ($keyword !== '') {
    $stmt = $pdo->prepare($sqlDasar . " AND (b.judul ILIKE :kw OR a.nama ILIKE :kw OR a.no_hp ILIKE :kw) ORDER BY p.tanggal_pinjam");
    $stmt->execute(['kw' => '%' . $keyword . '%']);
} else {
    $stmt = $pdo->query($sqlDasar . " ORDER BY p.tanggal_pinjam");
}
$daftarAktif = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
        <section>
            <h2>Pengembalian Buku</h2>

            <?php if ($flash): ?>
                <p class="flash flash-<?php echo $flash['type']; ?>"><?php echo e($flash['pesan']); ?></p>
            <?php endif; ?>

            <div class="search-box">
                <form method="get" action="kembali.php">
                    <span>
                        <label for="search-input">Cari anggota/buku/no.hp</label><br>
                        <input type="text" id="search-input" name="q" value="<?php echo e($keyword); ?>" placeholder="Nama anggota, judul buku, no hp...">
                    </span>
                    <button type="submit">Cari</button>
                    <?php if ($keyword !== ''): ?>
                        <a href="kembali.php" class="btn-secondary" style="padding: 0.4rem 0.8rem; border-radius: 4px; background: #6c757d; color: #fff; text-decoration: none;">Reset</a>
                    <?php endif; ?>
                </form>
            </div>

            <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Anggota</th>
                        <th>Buku</th>
                        <th>Tgl Pinjam</th>
                        <th>Jatuh Tempo</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($daftarAktif)): ?>
                    <tr>
                        <td colspan="5" style="text-align: center; padding: 1.5rem;">Tidak ada transaksi peminjaman aktif.</td>
                    </tr>
                    <?php else: ?>
                        <?php foreach ($daftarAktif as $trx): ?>
                        <tr>
                            <td>
                                <strong><?php echo e($trx['nama']); ?></strong>
                                <?php if (!empty($trx['no_hp'])): ?>
                                    <br><small style="color: #666;">Telp: <?php echo e($trx['no_hp']); ?></small>
                                <?php endif; ?>
                            </td>
                            <td>
                                <strong><?php echo e($trx['judul']); ?></strong>
                                <?php if (!empty($trx['kategori'])): ?>
                                    <br><small style="color: #666;">Kategori: <?php echo e($trx['kategori']); ?></small>
                                <?php endif; ?>
                            </td>
                            <td><?php echo $trx['tanggal_pinjam']; ?></td>
                            <td>
                                <span><?php echo $trx['tanggal_jatuh_tempo']; ?></span>
                                <?php if ((int)$trx['hari_terlambat'] > 0): ?>
                                    <br><span style="display: inline-block; background-color: #d9534f; color: #fff; font-size: 0.75rem; padding: 2px 6px; border-radius: 4px; font-weight: bold;">Lewat <?php echo (int)$trx['hari_terlambat']; ?> Hari</span>
                                <?php else: ?>
                                    <br><span style="display: inline-block; background-color: #5cb85c; color: #fff; font-size: 0.75rem; padding: 2px 6px; border-radius: 4px;">Aktif</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <form method="post" action="proses_kembali.php" onsubmit="return confirm('Apakah buku ini benar sudah dikembalikan?');">
                                    <?php echo csrf_field(); ?>
                                    <input type="hidden" name="id" value="<?php echo $trx['id']; ?>">
                                    <button type="submit" style="background-color: #320042; color: #fff; padding: 0.35rem 0.7rem; border: none; border-radius: 4px; cursor: pointer;">Kembalikan</button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
            </div>
        </section>
<?php include __DIR__ . '/../includes/footer.php'; ?>
