<?php
session_start();
require __DIR__ . '/../includes/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: list.php');
    exit;
}

$id        = $_POST['id'] ?? null;
$nama      = trim($_POST['nama'] ?? '');
$noAnggota = trim($_POST['no_anggota'] ?? '');
$alamat    = trim($_POST['alamat'] ?? '');
$noHp      = trim($_POST['no_hp'] ?? '');

if (!$id) {
    header('Location: list.php');
    exit;
}

$errors = [];
if ($nama === '') {
    $errors[] = "Nama wajib diisi.";
} elseif (mb_strlen($nama) < 3) {
    $errors[] = "Nama terlalu pendek (minimal 3 karakter).";
}

if ($noAnggota === '') {
    $errors[] = "No. Anggota wajib diisi.";
} elseif (!preg_match('/^[A-Za-z0-9\-]+$/', $noAnggota)) {
    $errors[] = "Format No. Anggota tidak valid (hanya huruf, angka, dan tanda hubung).";
}

if ($noHp !== '') {
    if (!preg_match('/^[0-9+\-\s]+$/', $noHp)) {
        $errors[] = "Format nomor HP tidak valid (hanya boleh angka, tanda +, spasi, dan strip).";
    } elseif (strlen(preg_replace('/[^0-9]/', '', $noHp)) < 10) {
        $errors[] = "Nomor HP minimal terdiri dari 10 digit angka.";
    }
}

if (!empty($errors)) {
    $_SESSION['flash'] = ['type' => 'error', 'pesan' => implode(' ', $errors)];
    header('Location: edit.php?id=' . urlencode($id));
    exit;
}

try {
    $stmt = $pdo->prepare(
        "UPDATE anggota SET nama = :nama, no_anggota = :no_anggota,
         alamat = :alamat, no_hp = :no_hp WHERE id = :id"
    );
    $stmt->execute([
        'nama'       => $nama,
        'no_anggota' => $noAnggota,
        'alamat'     => $alamat,
        'no_hp'      => $noHp,
        'id'         => $id,
    ]);

    $_SESSION['flash'] = ['type' => 'success', 'pesan' => "Data anggota \"$nama\" berhasil diperbarui."];
    header('Location: list.php');
    exit;
} catch (PDOException $e) {
    if ($e->getCode() === '23505' || (isset($e->errorInfo[0]) && $e->errorInfo[0] === '23505')) {
        $_SESSION['flash'] = [
            'type'  => 'error',
            'pesan' => "Gagal memperbarui: Nomor Anggota '$noAnggota' sudah digunakan oleh anggota lain."
        ];
    } else {
        $_SESSION['flash'] = [
            'type'  => 'error',
            'pesan' => "Terjadi kesalahan database: " . $e->getMessage()
        ];
    }
    header('Location: edit.php?id=' . urlencode($id));
    exit;
}
