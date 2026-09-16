<?php
session_start();
require __DIR__ . '/../includes/koneksi.php';

$nama = trim($_POST['nama'] ?? '');
$noAnggota = trim($_POST['no_anggota'] ?? '');
$alamat = trim($_POST['alamat'] ?? '');
$noHp = trim($_POST['no_hp'] ?? '');

$errors = [];

// 1. Validasi Nama (Wajib & min 3 karakter, selaras dengan JS 7)
if ($nama === '') {
    $errors[] = "Nama wajib diisi.";
} elseif (mb_strlen($nama) < 3) {
    $errors[] = "Nama terlalu pendek (minimal 3 karakter).";
}

// 2. Validasi Nomor Anggota (Wajib & format alfanumerik/strip)
if ($noAnggota === '') {
    $errors[] = "No. Anggota wajib diisi.";
} elseif (!preg_match('/^[A-Za-z0-9\-]+$/', $noAnggota)) {
    $errors[] = "Format No. Anggota tidak valid (hanya huruf, angka, dan tanda hubung).";
}

// 3. Validasi Nomor HP (Opsional, tapi jika diisi harus valid & minimal 10 digit)
if ($noHp !== '') {
    if (!preg_match('/^[0-9+\-\s]+$/', $noHp)) {
        $errors[] = "Format nomor HP tidak valid (hanya boleh angka, tanda +, spasi, dan strip).";
    } elseif (strlen(preg_replace('/[^0-9]/', '', $noHp)) < 10) {
        $errors[] = "Nomor HP minimal terdiri dari 10 digit angka.";
    }
}

if (!empty($errors)) {
    $_SESSION['flash'] = ['type' => 'error', 'pesan' => implode(' ', $errors)];
    header('Location: tambah.php');
    exit;
}

// Latihan §7.4 Poin 1: Tangani error UNIQUE dengan rapi via try/catch (PDOException $e)
try {
    $stmt = $pdo->prepare(
        "INSERT INTO anggota (nama, no_anggota, alamat, no_hp)
         VALUES (:nama, :no_anggota, :alamat, :no_hp)
         RETURNING id"
    );
    $stmt->execute([
        'nama' => $nama,
        'no_anggota' => $noAnggota,
        'alamat' => $alamat,
        'no_hp' => $noHp,
    ]);

    $_SESSION['flash'] = ['type' => 'success', 'pesan' => 'Anggota berhasil ditambahkan.'];
    header('Location: list.php');
    exit;
} catch (PDOException $e) {
    // Kode 23505 adalah SQLSTATE standar PostgreSQL untuk unique_violation
    if ($e->getCode() === '23505' || (isset($e->errorInfo[0]) && $e->errorInfo[0] === '23505')) {
        $_SESSION['flash'] = [
            'type' => 'error',
            'pesan' => "No. Anggota '{$noAnggota}' sudah dipakai, gunakan nomor lain."
        ];
    } else {
        $_SESSION['flash'] = [
            'type' => 'error',
            'pesan' => 'Terjadi kesalahan database: ' . $e->getMessage()
        ];
    }
    header('Location: tambah.php');
    exit;
}
