<?php
$page_title = "Daftar Buku";
include __DIR__ . '/../includes/header.php';
require __DIR__ . '/../includes/koneksi.php';

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

// Latihan §7.4 Poin 3: Query pencarian di server via WHERE judul ILIKE :keyword
$keyword = trim($_GET['q'] ?? '');
if ($keyword !== '') {
    $stmt = $pdo->prepare("SELECT * FROM buku WHERE judul ILIKE :keyword ORDER BY id DESC");
    $stmt->execute(['keyword' => "%$keyword%"]);
    $daftarBuku = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    $daftarBuku = $pdo->query("SELECT * FROM buku ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
}
?>
        <section>
            <h2>Daftar Buku</h2>

            <?php if ($flash): ?>
                <p class="flash flash-<?php echo $flash['type']; ?>"><?php echo $flash['pesan']; ?></p>
            <?php endif; ?>

            <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 1.25rem; flex-wrap: wrap; gap: 0.75rem;">
                <form method="get" action="list.php" class="search-box" style="margin-bottom: 0; display: flex; gap: 0.5rem; align-items: flex-end; flex-wrap: wrap;">
                    <div>
                        <label for="search-input">Cari Judul Buku (Server ILIKE &amp; Client)</label>
                        <input type="text" id="search-input" name="q" value="<?php echo htmlspecialchars($keyword); ?>" placeholder="Ketik judul buku...">
                    </div>
                    <button type="submit" style="padding: 0.55rem 1rem; background-color: #320042; color: #fff; border: none; border-radius: 4px; cursor: pointer; font-weight: 500;">Cari</button>
                    <?php if ($keyword !== ''): ?>
                        <a href="list.php" style="padding: 0.55rem 0.85rem; background-color: #6c757d; color: #fff; border-radius: 4px; text-decoration: none; font-size: 0.9rem;">Reset</a>
                    <?php endif; ?>
                </form>
                <div>
                    <a href="../migrasi_buku.php" style="display: inline-block; padding: 0.55rem 0.95rem; background-color: #28a745; color: #fff; border-radius: 4px; text-decoration: none; font-size: 0.9rem; font-weight: 500;">📦 Migrasi dari JSON</a>
                </div>
            </div>

            <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Judul</th>
                        <th>Pengarang</th>
                        <th>Tahun</th>
                        <th>ISBN</th>
                        <th>Kategori</th>
                        <th>Stok</th>
                        <th>Ditambahkan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($daftarBuku)): ?>
                    <tr>
                        <td colspan="8">
                            <?php if ($keyword !== ''): ?>
                                Tidak ada buku yang cocok dengan kata kunci "<strong><?php echo htmlspecialchars($keyword); ?></strong>". <a href="list.php">Lihat semua buku</a>
                            <?php else: ?>
                                Belum ada data buku. Silakan tambah lewat menu "Tambah Buku" atau klik "Migrasi dari JSON".
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php else: ?>
                        <?php foreach ($daftarBuku as $buku): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($buku['judul']); ?></td>
                            <td><?php echo htmlspecialchars($buku['pengarang']); ?></td>
                            <td><?php echo htmlspecialchars((string)$buku['tahun']); ?></td>
                            <td><?php echo htmlspecialchars(!empty($buku['isbn']) ? $buku['isbn'] : '-'); ?></td>
                            <td><?php echo htmlspecialchars(!empty($buku['kategori']) ? ucfirst($buku['kategori']) : '-'); ?></td>
                            <td><?php echo htmlspecialchars((string)$buku['stok']); ?></td>
                            <td><?php echo htmlspecialchars(!empty($buku['tanggal_ditambahkan']) ? date('d/m/Y H:i', strtotime($buku['tanggal_ditambahkan'])) : '-'); ?></td>
                            <td>
                                <button type="button">Edit</button>
                                <button type="button" class="btn-hapus">Hapus</button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
            </div>
        </section>
<?php include __DIR__ . '/../includes/footer.php'; ?>
