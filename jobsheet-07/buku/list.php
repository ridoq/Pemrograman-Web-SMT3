<?php
$page_title = "Daftar Buku";
include __DIR__ . '/../includes/header.php';

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);
$daftarBuku = $_SESSION['buku'] ?? [];
?>
        <section>
            <h2>Daftar Buku</h2>

            <?php if ($flash): ?>
                <p class="flash flash-<?php echo $flash['type']; ?>"><?php echo $flash['pesan']; ?></p>
            <?php endif; ?>

            <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 1rem; flex-wrap: wrap; gap: 0.5rem;">
                <div class="search-box" style="margin-bottom: 0;">
                    <label for="search-input">Cari Judul Buku</label>
                    <input type="text" id="search-input" placeholder="Ketik judul buku...">
                </div>
                <div>
                    <a href="../debug_session.php" style="display: inline-block; padding: 0.45rem 0.85rem; background-color: #320042; color: #fff; border-radius: 4px; text-decoration: none; font-size: 0.85rem; font-weight: 500;">Debug Sesi</a>
                    <a href="../reset_session.php" onclick="return confirm('Apakah Anda yakin ingin mengosongkan seluruh data sesi?');" style="display: inline-block; padding: 0.45rem 0.85rem; background-color: #d9534f; color: #fff; border-radius: 4px; text-decoration: none; font-size: 0.85rem; font-weight: 500;">Reset Sesi</a>
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
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($daftarBuku)): ?>
                    <tr>
                        <td colspan="7">Belum ada data buku. Silakan tambah lewat menu "Tambah Buku".</td>
                    </tr>
                    <?php else: ?>
                        <?php foreach ($daftarBuku as $buku): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($buku['judul']); ?></td>
                            <td><?php echo htmlspecialchars($buku['pengarang']); ?></td>
                            <td><?php echo htmlspecialchars((string)$buku['tahun']); ?></td>
                            <td><?php echo htmlspecialchars($buku['isbn'] !== '' ? $buku['isbn'] : '-'); ?></td>
                            <td><?php echo htmlspecialchars(ucfirst($buku['kategori'] ?? '-')); ?></td>
                            <td><?php echo htmlspecialchars((string)$buku['stok']); ?></td>
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
