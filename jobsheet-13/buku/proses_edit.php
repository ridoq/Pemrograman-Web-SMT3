<?php
require __DIR__ . '/../includes/auth.php';
require __DIR__ . '/../includes/csrf.php';
require __DIR__ . '/../includes/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: list.php');
    exit;
}

csrf_verify();

$id        = (int) ($_POST['id'] ?? 0);
$judul     = trim($_POST['judul'] ?? '');
$pengarang = trim($_POST['pengarang'] ?? '');
$tahun     = $_POST['tahun'] ?? '';
$isbn      = trim($_POST['isbn'] ?? '');
$stok      = $_POST['stok'] ?? '';
$kategori  = trim($_POST['kategori'] ?? 'fiksi');

if ($id <= 0) {
    header('Location: list.php');
    exit;
}

$errors = [];
if ($judul === '') {
    $errors[] = "Judul buku wajib diisi.";
}
if ($pengarang === '') {
    $errors[] = "Pengarang wajib diisi.";
}
if (!is_numeric($tahun) || (int)$tahun < 1900 || (int)$tahun > 2026) {
    $errors[] = "Tahun terbit harus berupa angka di antara 1900 - 2026.";
}
if ($isbn !== '' && !preg_match('/^[0-9\-]+$/', $isbn)) {
    $errors[] = "Format ISBN tidak valid (hanya boleh angka dan tanda hubung).";
}
if (!is_numeric($stok) || (int)$stok < 0) {
    $errors[] = "Stok tidak boleh bernilai negatif.";
}
$kategoriWhitelist = ['fiksi', 'non-fiksi', 'referensi'];
if (!in_array($kategori, $kategoriWhitelist, true)) {
    $errors[] = "Kategori tidak valid.";
}

if (!empty($errors)) {
    $_SESSION['flash'] = ['type' => 'error', 'pesan' => implode(' ', $errors)];
    header('Location: edit.php?id=' . urlencode((string)$id));
    exit;
}

try {
    $stmt = $pdo->prepare(
        "UPDATE buku SET judul = :judul, pengarang = :pengarang, tahun = :tahun,
         isbn = :isbn, stok = :stok, kategori = :kategori WHERE id = :id"
    );
    $stmt->execute([
        'judul'     => $judul,
        'pengarang' => $pengarang,
        'tahun'     => (int) $tahun,
        'isbn'      => $isbn,
        'stok'      => (int) $stok,
        'kategori'  => $kategori,
        'id'        => $id,
    ]);

    $_SESSION['flash'] = ['type' => 'success', 'pesan' => "Buku \"$judul\" berhasil diperbarui."];
    header('Location: list.php');
    exit;
} catch (PDOException $e) {
    $_SESSION['flash'] = ['type' => 'error', 'pesan' => "Gagal memperbarui buku: " . $e->getMessage()];
    header('Location: edit.php?id=' . urlencode((string)$id));
    exit;
}
