<?php
session_start();

// 1. Kosongkan semua data di array $_SESSION
$_SESSION = [];

// 2. Hapus cookie sesi dari browser jika session.use_cookies aktif
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

// 3. Hancurkan seluruh data sesi di server (Latihan §7.4 Poin 4)
session_destroy();

// 4. Buka sesi baru khusus untuk mengirim flash message konfirmasi
session_start();
$_SESSION['flash'] = [
    'type' => 'success',
    'pesan' => 'Seluruh data sesi berhasil di-reset via session_destroy() (Latihan §7.4).'
];

header('Location: index.php');
exit;
