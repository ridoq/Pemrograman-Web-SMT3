<?php
$host = "localhost";
$port = "5432";
$db   = "simpus_mini";
$user = "postgres";
$pass = "12345678";

try {
    $pdo = new PDO("pgsql:host=$host;port=$port;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    try {
        $pdo = new PDO("pgsql:host=$host;port=$port;dbname=$db", $user, "postgres");
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e2) {
        die("Koneksi database gagal: " . $e->getMessage());
    }
}
