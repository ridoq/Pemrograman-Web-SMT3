<?php
// Vercel Serverless PHP Router untuk SIMPUS-Mini
$savePath = sys_get_temp_dir() . '/sessions';
if (!file_exists($savePath)) {
    @mkdir($savePath, 0777, true);
}
ini_set('session.save_path', $savePath);

// Ambil path request
$uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
$uri = rawurldecode($uri);

// Normalisasi jika user mengakses dengan awalan /jobsheet-13
if (str_starts_with($uri, '/jobsheet-13/')) {
    $uri = substr($uri, strlen('/jobsheet-13'));
} elseif ($uri === '/jobsheet-13') {
    $uri = '/index.php';
}

// Default ke index.php jika root
if ($uri === '/' || $uri === '') {
    $uri = '/index.php';
}

// Lokasi target di folder jobsheet-13
$baseDir = realpath(__DIR__ . '/../jobsheet-13') ?: (__DIR__ . '/../jobsheet-13');
$target = realpath($baseDir . $uri) ?: ($baseDir . $uri);

// Layani static asset jika terpanggil lewat router
if (file_exists($target) && is_file($target)) {
    $ext = strtolower(pathinfo($target, PATHINFO_EXTENSION));
    $mimes = [
        'css'  => 'text/css',
        'js'   => 'application/javascript',
        'png'  => 'image/png',
        'jpg'  => 'image/jpeg',
        'svg'  => 'image/svg+xml',
        'ico'  => 'image/x-icon',
        'woff' => 'font/woff',
        'woff2'=> 'font/woff2',
    ];
    if (isset($mimes[$ext])) {
        header("Content-Type: {$mimes[$ext]}");
        readfile($target);
        exit;
    }

    if ($ext === 'php') {
        $_SERVER['SCRIPT_FILENAME'] = $target;
        chdir(dirname($target));
        require $target;
        exit;
    }
}

// Fallback: Jika diakses tanpa ekstensi .php (misal /auth/login)
$targetPhp = realpath($baseDir . $uri . '.php') ?: ($baseDir . $uri . '.php');
if (file_exists($targetPhp) && is_file($targetPhp)) {
    $_SERVER['SCRIPT_FILENAME'] = $targetPhp;
    chdir(dirname($targetPhp));
    require $targetPhp;
    exit;
}

// 404 Not Found
http_response_code(404);
echo "404 Not Found: Halaman " . htmlspecialchars($uri) . " tidak ditemukan.";
