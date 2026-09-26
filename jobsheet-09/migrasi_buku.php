<?php
$isCli = (php_sapi_name() === 'cli');

require __DIR__ . '/includes/koneksi.php';

$jsonPath = __DIR__ . '/../jobsheet-06/data/buku.json';
$error = null;
$migratedCount = 0;
$skippedCount = 0;
$logs = [];

if (!file_exists($jsonPath)) {
    $error = "File sumber JSON tidak ditemukan di: " . realpath(__DIR__ . '/..') . "/jobsheet-06/data/buku.json";
} else {
    $jsonContent = file_get_contents($jsonPath);
    $dataBuku = json_decode($jsonContent, true);

    if (!is_array($dataBuku)) {
        $error = "Gagal mem-parsing format JSON dari file sumber.";
    } else {
        // Prepared statements untuk cek duplikasi judul & insert data
        $checkStmt = $pdo->prepare("SELECT COUNT(*) FROM buku WHERE judul = :judul");
        $insertStmt = $pdo->prepare(
            "INSERT INTO buku (judul, pengarang, tahun, stok, kategori)
             VALUES (:judul, :pengarang, :tahun, :stok, :kategori)
             RETURNING id"
        );

        foreach ($dataBuku as $item) {
            $judul = trim($item['judul'] ?? '');
            $pengarang = trim($item['pengarang'] ?? '');
            $tahun = (int) ($item['tahun'] ?? 2026);
            $stok = (int) ($item['stok'] ?? 0);
            $kategori = trim($item['kategori'] ?? 'Umum');

            if ($judul === '') {
                continue;
            }

            // Cek apakah buku sudah ada di database
            $checkStmt->execute(['judul' => $judul]);
            $exists = ($checkStmt->fetchColumn() > 0);

            if ($exists) {
                $skippedCount++;
                $logs[] = [
                    'status' => 'SKIP',
                    'judul' => $judul,
                    'pesan' => 'Sudah ada di database'
                ];
            } else {
                $insertStmt->execute([
                    'judul' => $judul,
                    'pengarang' => $pengarang,
                    'tahun' => $tahun,
                    'stok' => $stok,
                    'kategori' => $kategori,
                ]);
                $migratedCount++;
                $logs[] = [
                    'status' => 'INSERT',
                    'judul' => $judul,
                    'pesan' => 'Berhasil ditambahkan ke PostgreSQL'
                ];
            }
        }
    }
}

// Jika dijalankan via terminal CLI
if ($isCli) {
    if ($error) {
        echo "[ERROR] " . $error . PHP_EOL;
        exit(1);
    }
    echo "==========================================" . PHP_EOL;
    echo "  MIGRASI DATA BUKU: JSON -> POSTGRESQL   " . PHP_EOL;
    echo "==========================================" . PHP_EOL;
    foreach ($logs as $log) {
        echo sprintf("[%s] %-30s : %s\n", $log['status'], $log['judul'], $log['pesan']);
    }
    echo "------------------------------------------" . PHP_EOL;
    echo sprintf("Total Ditambahkan : %d\n", $migratedCount);
    echo sprintf("Total Dilewati    : %d\n", $skippedCount);
    echo "==========================================" . PHP_EOL;
    exit(0);
}

// Jika dijalankan via Web Browser
$page_title = "Migrasi Data Buku";
include __DIR__ . '/includes/header.php';
?>
        <section>
            <h2>Migrasi Data Buku (Latihan §7.4 Poin 4)</h2>
            <p>Skrip ini membaca file data <code>data/buku.json</code> dari <strong>jobsheet-06</strong> dan memasukkan seluruh rekaman buku ke dalam database PostgreSQL <strong>simpus_mini</strong> secara otomatis.</p>

            <?php if ($error): ?>
                <p class="flash flash-error"><?php echo htmlspecialchars($error); ?></p>
            <?php else: ?>
                <div style="display: flex; gap: 1rem; margin: 1.25rem 0;">
                    <div style="background: #eef4fa; border-radius: 6px; padding: 1rem 1.5rem; text-align: center; flex: 1;">
                        <h4 style="color: #55677a; margin-bottom: 0.25rem; font-size: 0.9rem;">Berhasil Diimpor</h4>
                        <p style="font-size: 1.8rem; font-weight: 700; color: #28a745; margin: 0;"><?php echo $migratedCount; ?></p>
                    </div>
                    <div style="background: #eef4fa; border-radius: 6px; padding: 1rem 1.5rem; text-align: center; flex: 1;">
                        <h4 style="color: #55677a; margin-bottom: 0.25rem; font-size: 0.9rem;">Dilewati (Duplikat)</h4>
                        <p style="font-size: 1.8rem; font-weight: 700; color: #6c757d; margin: 0;"><?php echo $skippedCount; ?></p>
                    </div>
                </div>

                <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Status</th>
                            <th>Judul Buku</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($logs as $log): ?>
                        <tr>
                            <td>
                                <?php if ($log['status'] === 'INSERT'): ?>
                                    <span style="display: inline-block; padding: 0.2rem 0.5rem; background: #d4edda; color: #155724; border-radius: 4px; font-weight: 600; font-size: 0.85rem;">INSERT</span>
                                <?php else: ?>
                                    <span style="display: inline-block; padding: 0.2rem 0.5rem; background: #e2e3e5; color: #383d41; border-radius: 4px; font-weight: 600; font-size: 0.85rem;">SKIP</span>
                                <?php endif; ?>
                            </td>
                            <td><strong><?php echo htmlspecialchars($log['judul']); ?></strong></td>
                            <td><?php echo htmlspecialchars($log['pesan']); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                </div>
            <?php endif; ?>

            <div style="margin-top: 1.5rem; display: flex; gap: 0.75rem;">
                <a href="buku/list.php" style="display: inline-block; padding: 0.55rem 1.1rem; background-color: #320042; color: #fff; border-radius: 4px; text-decoration: none; font-weight: 500;">&larr; Lihat Daftar Buku</a>
                <a href="index.php" style="display: inline-block; padding: 0.55rem 1.1rem; background-color: #6c757d; color: #fff; border-radius: 4px; text-decoration: none; font-weight: 500;">Kembali ke Beranda</a>
            </div>
        </section>
<?php include __DIR__ . '/includes/footer.php'; ?>
