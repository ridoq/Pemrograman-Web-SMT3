<?php
$page_title = "Edit Buku";
include __DIR__ . '/../includes/header.php';
require __DIR__ . '/../includes/koneksi.php';

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

$id = $_GET['id'] ?? null;
if (!$id) {
    header('Location: list.php');
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM buku WHERE id = :id");
$stmt->execute(['id' => $id]);
$buku = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$buku) {
    $_SESSION['flash'] = ['type' => 'error', 'pesan' => 'Data buku tidak ditemukan.'];
    header('Location: list.php');
    exit;
}
?>
        <section>
            <h2>Edit Buku</h2>

            <?php if ($flash): ?>
                <p class="flash flash-<?php echo $flash['type']; ?>"><?php echo $flash['pesan']; ?></p>
            <?php endif; ?>

            <form id="form-edit" class="form-edit" method="post" action="proses_edit.php">
                <input type="hidden" name="id" value="<?php echo htmlspecialchars((string)$buku['id']); ?>">
                <p>
                    <label for="judul">Judul</label><br>
                    <input type="text" id="judul" name="judul" value="<?php echo htmlspecialchars($buku['judul']); ?>" required>
                </p>
                <p>
                    <label for="pengarang">Pengarang</label><br>
                    <input type="text" id="pengarang" name="pengarang" value="<?php echo htmlspecialchars($buku['pengarang']); ?>" required>
                </p>
                <p>
                    <label for="tahun">Tahun Terbit</label><br>
                    <input type="number" id="tahun" name="tahun" min="1900" max="2026" value="<?php echo htmlspecialchars((string)$buku['tahun']); ?>" required>
                </p>
                <p>
                    <label for="isbn">ISBN</label><br>
                    <input type="text" id="isbn" name="isbn" value="<?php echo htmlspecialchars(!empty($buku['isbn']) ? $buku['isbn'] : ''); ?>" placeholder="Contoh: 978-602-03-2478-4">
                </p>
                <p>
                    <label for="stok">Stok</label><br>
                    <input type="number" id="stok" name="stok" min="0" value="<?php echo htmlspecialchars((string)$buku['stok']); ?>" required>
                </p>
                <p>
                    <label for="kategori">Kategori</label><br>
                    <select id="kategori" name="kategori">
                        <?php 
                        $kategoriList = ['fiksi' => 'Fiksi', 'non-fiksi' => 'Non-Fiksi', 'referensi' => 'Referensi'];
                        $currentKategori = strtolower($buku['kategori'] ?? 'fiksi');
                        foreach ($kategoriList as $val => $label): 
                        ?>
                        <option value="<?php echo $val; ?>" <?php echo $currentKategori === $val ? 'selected' : ''; ?>><?php echo $label; ?></option>
                        <?php endforeach; ?>
                    </select>
                </p>
                <p style="display: flex; gap: 0.5rem; align-items: center; margin-top: 1rem;">
                    <button type="submit" style="padding: 0.55rem 1.25rem; background-color: #320042; color: #fff; border: none; border-radius: 4px; cursor: pointer; font-weight: 500;">Simpan Perubahan</button>
                    <a href="list.php" style="display: inline-block; padding: 0.55rem 1rem; background-color: #6c757d; color: #fff; border-radius: 4px; text-decoration: none; font-size: 0.9rem;">Batal</a>
                </p>
            </form>
        </section>
<?php include __DIR__ . '/../includes/footer.php'; ?>
