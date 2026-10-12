<?php
require __DIR__ . '/../includes/auth.php';
$page_title = "Edit Anggota";
include __DIR__ . '/../includes/header.php';
require __DIR__ . '/../includes/koneksi.php';

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

$id = (int) ($_GET['id'] ?? 0);
if ($id <= 0) {
    header('Location: list.php');
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM anggota WHERE id = :id");
$stmt->execute(['id' => $id]);
$anggota = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$anggota) {
    $_SESSION['flash'] = ['type' => 'error', 'pesan' => 'Data anggota tidak ditemukan.'];
    header('Location: list.php');
    exit;
}
?>
        <section>
            <h2>Edit Anggota</h2>

            <?php if ($flash): ?>
                <p class="flash flash-<?php echo $flash['type']; ?>"><?php echo $flash['pesan']; ?></p>
            <?php endif; ?>

            <form id="form-edit" class="form-edit" method="post" action="proses_edit.php">
                <input type="hidden" name="id" value="<?php echo (int) $anggota['id']; ?>">
                <?php echo csrf_field(); ?>
                <p>
                    <label for="nama">Nama Lengkap</label><br>
                    <input type="text" id="nama" name="nama" value="<?php echo e($anggota['nama']); ?>" required>
                </p>
                <p>
                    <label for="no_anggota">Nomor Anggota</label><br>
                    <input type="text" id="no_anggota" name="no_anggota" value="<?php echo e($anggota['no_anggota']); ?>" placeholder="Contoh: AG-001" required>
                </p>
                <p>
                    <label for="alamat">Alamat</label><br>
                    <input type="text" id="alamat" name="alamat" value="<?php echo e($anggota['alamat'] ?? ''); ?>">
                </p>
                <p>
                    <label for="no_hp">Nomor HP</label><br>
                    <input type="text" id="no_hp" name="no_hp" value="<?php echo e($anggota['no_hp'] ?? ''); ?>" placeholder="Contoh: 0812-3456-7890">
                </p>
                <p style="display: flex; gap: 0.5rem; align-items: center; margin-top: 1rem;">
                    <button type="submit" style="padding: 0.55rem 1.25rem; background-color: #320042; color: #fff; border: none; border-radius: 4px; cursor: pointer; font-weight: 500;">Simpan Perubahan</button>
                    <a href="list.php" style="display: inline-block; padding: 0.55rem 1rem; background-color: #6c757d; color: #fff; border-radius: 4px; text-decoration: none; font-size: 0.9rem;">Batal</a>
                </p>
            </form>
        </section>
<?php include __DIR__ . '/../includes/footer.php'; ?>
