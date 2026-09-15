<?php
$page_title = "Daftar Anggota";
include __DIR__ . '/../includes/header.php';

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);
$daftarAnggota = $_SESSION['anggota'] ?? [];
?>
        <section>
            <h2>Daftar Anggota</h2>

            <?php if ($flash): ?>
                <p class="flash flash-<?php echo $flash['type']; ?>"><?php echo $flash['pesan']; ?></p>
            <?php endif; ?>

            <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 1rem; flex-wrap: wrap; gap: 0.5rem;">
                <div class="search-box" style="margin-bottom: 0;">
                    <label for="search-input">Cari Nama Anggota</label>
                    <input type="text" id="search-input" placeholder="Ketik nama anggota...">
                </div>
                <div>
                    <a href="../debug_session.php" style="display: inline-block; padding: 0.45rem 0.85rem; background-color: #320042; color: #fff; border-radius: 4px; text-decoration: none; font-size: 0.85rem; font-weight: 500;">Debug Sesi</a>
                    <a href="../reset_session.php" onclick="return confirm('Apakah Anda yakin ingin mengosongkan seluruh data sesi?');" style="display: inline-block; padding: 0.45rem 0.85rem; background-color: #d9534f; color: #fff; border-radius: 4px; text-decoration: none; font-size: 0.85rem; font-weight: 500;">Reset Sesi</a>
                </div>
            </div>

            <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>No. Anggota</th>
                        <th>Nama</th>
                        <th>Alamat</th>
                        <th>No. Telepon</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($daftarAnggota)): ?>
                    <tr>
                        <td colspan="5">Belum ada data anggota. Silakan tambah lewat menu "Tambah Anggota".</td>
                    </tr>
                    <?php else: ?>
                        <?php foreach ($daftarAnggota as $anggota): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($anggota['no_anggota']); ?></td>
                            <td><?php echo htmlspecialchars($anggota['nama']); ?></td>
                            <td><?php echo htmlspecialchars($anggota['alamat'] !== '' ? $anggota['alamat'] : '-'); ?></td>
                            <td><?php echo htmlspecialchars($anggota['no_hp'] !== '' ? $anggota['no_hp'] : '-'); ?></td>
                            <td>
                                <button type="button">Edit</button>
                                <button type="button" class="btn-hapus">Hapus</button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
            </div>
        </section>
<?php include __DIR__ . '/../includes/footer.php'; ?>
