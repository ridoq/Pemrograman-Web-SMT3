<?php
// Guard clause: di-include di baris paling atas setiap halaman yang
// membutuhkan login (sebelum header.php mengeluarkan output apa pun),
// agar header('Location: ...') masih bisa dipanggil.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    $scriptDir = dirname($_SERVER['SCRIPT_FILENAME'] ?? '');
    $jobsheetRoot = dirname(__DIR__);
    $rel = ltrim(str_replace('\\', '/', substr($scriptDir, strlen($jobsheetRoot))), '/');
    $prefix = $rel === '' ? '' : str_repeat('../', substr_count($rel, '/') + 1);

    header('Location: ' . $prefix . 'auth/login.php');
    exit;
}
