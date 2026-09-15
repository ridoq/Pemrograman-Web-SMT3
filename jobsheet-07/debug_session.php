<?php
$page_title = "Debug Session";
include __DIR__ . '/includes/header.php';

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);
?>
        <section>
            <h2>Debug Isi Session</h2>
            <p>Halaman ini menampilkan struktur dan data mentah yang tersimpan di dalam superglobal <code>$_SESSION</code> di sisi server secara real-time.</p>

            <?php if ($flash): ?>
                <p class="flash flash-<?php echo $flash['type']; ?>"><?php echo $flash['pesan']; ?></p>
            <?php endif; ?>

            <div style="margin-top: 1.25rem;">
                <h3>Data Mentah <code>$_SESSION</code>:</h3>
                <pre style="background-color: #1e1e24; color: #a9b7c6; padding: 1.25rem; border-radius: 6px; overflow-x: auto; font-family: 'Consolas', 'Courier New', monospace; font-size: 0.95rem; line-height: 1.5; border: 1px solid #320042; margin-top: 0.5rem;"><?php print_r($_SESSION); ?></pre>
            </div>

            <div style="margin-top: 1.5rem; display: flex; gap: 0.75rem; flex-wrap: wrap;">
                <a href="index.php" style="display: inline-block; padding: 0.5rem 1rem; background-color: #320042; color: #fff; border-radius: 4px; text-decoration: none; font-weight: 500;">&larr; Kembali ke Beranda</a>
                <a href="reset_session.php" onclick="return confirm('Apakah Anda yakin ingin mengosongkan seluruh data sesi (session_destroy)?');" style="display: inline-block; padding: 0.5rem 1rem; background-color: #d9534f; color: #fff; border-radius: 4px; text-decoration: none; font-weight: 500;">🗑️ Reset Data Sesi</a>
            </div>
        </section>
<?php include __DIR__ . '/includes/footer.php'; ?>
