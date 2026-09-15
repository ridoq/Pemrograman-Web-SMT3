<?php
session_start();

$nama = trim($_POST['nama'] ?? '');
$noAnggota = trim($_POST['no_anggota'] ?? '');
$alamat = trim($_POST['alamat'] ?? '');
$noHp = trim($_POST['no_hp'] ?? '');

$errors = [];

// 1. Validasi Nama
if ($nama === '') {
    $errors[] = "Nama wajib diisi.";
} elseif (mb_strlen($nama) < 3) {
    $errors[] = "Nama terlalu pendek (minimal 3 karakter).";
}

// 2. Validasi Nomor Anggota (Wajib, format alfanumerik/strip, & cek keunikan)
if ($noAnggota === '') {
    $errors[] = "No. Anggota wajib diisi.";
} elseif (!preg_match('/^[A-Za-z0-9\-]+$/', $noAnggota)) {
    $errors[] = "Format No. Anggota tidak valid (hanya huruf, angka, dan tanda hubung).";
} elseif (!empty($_SESSION['anggota'])) {
    // Latihan §7.4: Cek duplikasi nomor anggota di dalam session
    foreach ($_SESSION['anggota'] as $existing) {
        if (strcasecmp($existing['no_anggota'], $noAnggota) === 0) {
            $errors[] = "No. Anggota '{$noAnggota}' sudah terdaftar, gunakan nomor lain.";
            break;
        }
    }
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

if (!isset($_SESSION['anggota'])) {
    $_SESSION['anggota'] = [];
}

$_SESSION['anggota'][] = [
    'nama' => $nama,
    'no_anggota' => $noAnggota,
    'alamat' => $alamat,
    'no_hp' => $noHp,
];

$_SESSION['flash'] = ['type' => 'success', 'pesan' => 'Anggota berhasil ditambahkan.'];
header('Location: list.php');
exit;
