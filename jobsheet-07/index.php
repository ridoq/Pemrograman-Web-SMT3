<?php
$page_title = "Beranda";
include __DIR__ . '/includes/header.php';

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

$totalBuku = count($_SESSION['buku'] ?? []);
$totalAnggota = count($_SESSION['anggota'] ?? []);
?>
        <section>
            <h2>Selamat Datang di Sistem Perpustakaan Mini</h2>
            <p>Aplikasi sederhana untuk mengelola data buku dan anggota perpustakaan.</p>
        </section>

        <?php if ($flash): ?>
            <p class="flash flash-<?php echo $flash['type']; ?>"><?php echo $flash['pesan']; ?></p>
        <?php endif; ?>

        <section>
            <h2>Ringkasan</h2>
            <article>
                <h3>Total Buku</h3>
                <p><?php echo $totalBuku; ?></p>
            </article>
            <article>
                <h3>Total Anggota</h3>
                <p><?php echo $totalAnggota; ?></p>
            </article>
            <article>
                <h3>Sedang Dipinjam</h3>
                <p>0</p>
            </article>
        </section>

        <section>
            <h2>Manajemen Sesi</h2>
            <p>Fitur untuk menginspeksi dan mengelola data sementara pada array superglobal <code>$_SESSION</code>:</p>
            <div style="margin-top: 1rem; display: flex; gap: 0.75rem; flex-wrap: wrap;">
                <a href="debug_session.php" style="display: inline-block; padding: 0.5rem 1rem; background-color: #320042; color: #fff; border-radius: 4px; text-decoration: none; font-weight: 500;">🔍 Debug Isi Session</a>
                <a href="reset_session.php" onclick="return confirm('Apakah Anda yakin ingin mengosongkan seluruh data sesi (session_destroy)?');" style="display: inline-block; padding: 0.5rem 1rem; background-color: #d9534f; color: #fff; border-radius: 4px; text-decoration: none; font-weight: 500;">🗑️ Reset Data Sesi</a>
            </div>
        </section>
<?php include __DIR__ . '/includes/footer.php'; ?>
