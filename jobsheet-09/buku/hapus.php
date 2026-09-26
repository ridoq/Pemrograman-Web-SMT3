<?php
session_start();
require __DIR__ . '/../includes/koneksi.php';

// Memastikan request hanya via method POST demi keamanan data (bukan link GET)
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: list.php');
    exit;
}

$id = $_POST['id'] ?? null;
if ($id) {
    try {
        $stmt = $pdo->prepare("DELETE FROM buku WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $_SESSION['flash'] = ['type' => 'success', 'pesan' => 'Data buku berhasil dihapus dari sistem.'];
    } catch (PDOException $e) {
        $_SESSION['flash'] = ['type' => 'error', 'pesan' => 'Gagal menghapus buku: ' . $e->getMessage()];
    }
}

header('Location: list.php');
exit;
