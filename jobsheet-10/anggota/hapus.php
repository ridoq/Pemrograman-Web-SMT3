<?php
require __DIR__ . '/../includes/auth.php';
require __DIR__ . '/../includes/koneksi.php';

// Memastikan request hanya via method POST demi keamanan data (bukan link GET)
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: list.php');
    exit;
}

$id = $_POST['id'] ?? null;
if ($id) {
    try {
        $stmt = $pdo->prepare("DELETE FROM anggota WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $_SESSION['flash'] = ['type' => 'success', 'pesan' => 'Data anggota berhasil dihapus dari sistem.'];
    } catch (PDOException $e) {
        $_SESSION['flash'] = ['type' => 'error', 'pesan' => 'Gagal menghapus anggota: ' . $e->getMessage()];
    }
}

header('Location: list.php');
exit;
