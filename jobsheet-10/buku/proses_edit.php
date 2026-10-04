<?php
require __DIR__ . '/../includes/auth.php';
require __DIR__ . '/../includes/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: list.php');
    exit;
}

$id        = $_POST['id'] ?? null;
$judul     = trim($_POST['judul'] ?? '');
$pengarang = trim($_POST['pengarang'] ?? '');
$tahun     = $_POST['tahun'] ?? '';
$isbn      = trim($_POST['isbn'] ?? '');
$stok      = $_POST['stok'] ?? '';
$kategori  = trim($_POST['kategori'] ?? 'fiksi');

if (!$id) {
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
if (!is_numeric($stok) || (int)$stok < 0) {
    $errors[] = "Stok tidak boleh bernilai negatif.";
}

if (!empty($errors)) {
    $_SESSION['flash'] = ['type' => 'error', 'pesan' => implode(' ', $errors)];
    header('Location: edit.php?id=' . urlencode($id));
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
    header('Location: edit.php?id=' . urlencode($id));
    exit;
}
