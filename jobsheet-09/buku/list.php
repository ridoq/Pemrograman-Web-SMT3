<?php
$page_title = "Daftar Buku";
include __DIR__ . '/../includes/header.php';
require __DIR__ . '/../includes/koneksi.php';

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

// Latihan §7.4 Poin 2: Opsi jumlah baris per halaman dinamis (5, 10, atau default 5)
$perPage = isset($_GET['per_page']) && in_array((int)$_GET['per_page'], [5, 10, 20]) ? (int)$_GET['per_page'] : 5;
$page    = max(1, (int) ($_GET['page'] ?? 1));
$offset  = ($page - 1) * $perPage;
$keyword = trim($_GET['q'] ?? '');

if ($keyword !== '') {
    $hitung = $pdo->prepare("SELECT COUNT(*) FROM buku WHERE judul ILIKE :kw OR pengarang ILIKE :kw");
    $hitung->execute(['kw' => '%' . $keyword . '%']);
    $totalRows = (int) $hitung->fetchColumn();

    $stmt = $pdo->prepare("SELECT * FROM buku WHERE judul ILIKE :kw OR pengarang ILIKE :kw ORDER BY id DESC LIMIT :limit OFFSET :offset");
    $stmt->bindValue('kw', '%' . $keyword . '%');
} else {
    $totalRows = (int) $pdo->query("SELECT COUNT(*) FROM buku")->fetchColumn();
    $stmt = $pdo->prepare("SELECT * FROM buku ORDER BY id DESC LIMIT :limit OFFSET :offset");
}
$stmt->bindValue('limit', $perPage, PDO::PARAM_INT);
$stmt->bindValue('offset', $offset, PDO::PARAM_INT);
$stmt->execute();

$daftarBuku = $stmt->fetchAll(PDO::FETCH_ASSOC);
$totalPages = max(1, (int) ceil($totalRows / $perPage));
?>
        <section>
            <h2>Daftar Buku</h2>

            <?php if ($flash): ?>
                <p class="flash flash-<?php echo $flash['type']; ?>"><?php echo $flash['pesan']; ?></p>
            <?php endif; ?>

            <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 1.25rem; flex-wrap: wrap; gap: 0.75rem;">
                <form method="get" action="list.php" class="search-box" style="margin-bottom: 0; display: flex; gap: 0.5rem; align-items: flex-end; flex-wrap: wrap;">
                    <div>
                        <label for="search-input">Cari Judul / Pengarang Buku</label>
                        <input type="text" id="search-input" name="q" value="<?php echo htmlspecialchars($keyword); ?>" placeholder="Ketik judul / pengarang...">
                    </div>
                    <button type="submit" style="padding: 0.55rem 1rem; background-color: #320042; color: #fff; border: none; border-radius: 4px; cursor: pointer; font-weight: 500;">Cari</button>
                    <?php if ($keyword !== ''): ?>
                        <a href="list.php" style="padding: 0.55rem 0.85rem; background-color: #6c757d; color: #fff; border-radius: 4px; text-decoration: none; font-size: 0.9rem;">Reset</a>
                    <?php endif; ?>
                </form>
                <div>
                    <a href="tambah.php" style="display: inline-block; padding: 0.55rem 0.95rem; background-color: #320042; color: #fff; border-radius: 4px; text-decoration: none; font-size: 0.9rem; font-weight: 500;">+ Tambah Buku</a>
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
                                Belum ada data buku. Silakan klik tombol "Tambah Buku" di atas.
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
                            <td style="white-space: nowrap;">
                                <a href="edit.php?id=<?php echo urlencode($buku['id']); ?>" class="btn-edit">Edit</a>
                                <form class="form-hapus" method="post" action="hapus.php" style="display: inline;">
                                    <input type="hidden" name="id" value="<?php echo htmlspecialchars((string)$buku['id']); ?>">
                                    <button type="submit" class="btn-hapus">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
            </div>

            <?php if ($totalPages > 1 || $perPage !== 5): ?>
            <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 1.25rem; flex-wrap: wrap; gap: 0.75rem;">
                <nav class="pagination" style="margin-top: 0;">
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <a href="list.php?page=<?php echo $i; ?><?php echo $perPage !== 5 ? '&per_page=' . $perPage : ''; ?><?php echo $keyword !== '' ? '&q=' . urlencode($keyword) : ''; ?>"
                       class="<?php echo $i === $page ? 'active' : ''; ?>"><?php echo $i; ?></a>
                    <?php endfor; ?>
                </nav>
                <div style="font-size: 0.9rem; color: #555;">
                    Tampilkan per halaman: 
                    <a href="list.php?per_page=5<?php echo $keyword !== '' ? '&q=' . urlencode($keyword) : ''; ?>" style="<?php echo $perPage === 5 ? 'font-weight: bold; color: #320042; text-decoration: underline;' : 'color: #555;'; ?>">5</a> |
                    <a href="list.php?per_page=10<?php echo $keyword !== '' ? '&q=' . urlencode($keyword) : ''; ?>" style="<?php echo $perPage === 10 ? 'font-weight: bold; color: #320042; text-decoration: underline;' : 'color: #555;'; ?>">10</a>
                </div>
            </div>
            <?php endif; ?>
        </section>
<?php include __DIR__ . '/../includes/footer.php'; ?>
