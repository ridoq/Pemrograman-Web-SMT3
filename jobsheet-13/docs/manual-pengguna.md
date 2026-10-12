# Manual Pengguna — SIMPUS-Mini

> Panduan operasional sistem perpustakaan mini (SIMPUS-Mini) untuk pengguna akhir (petugas perpustakaan).

---

## 1. Registrasi & Login Petugas

1. Buka halaman registrasi pada URL: `http://localhost/simpus-mini-24/jobsheet-13/auth/register.php`.
2. Isi Nama Lengkap, Username unik, dan Password (minimal 6 karakter), lalu klik tombol **Daftar**.
3. Setelah pendaftaran berhasil, sistem mengarahkan ke halaman Login (`auth/login.php`). Masukkan username dan password yang telah didaftarkan, lalu klik **Masuk**.
4. Setelah berhasil login, bilah navigasi (navbar) menampilkan nama petugas yang aktif serta membuka menu-menu privat (Tambah Buku, Kelola Anggota, Peminjaman Baru, Pengembalian, dan Riwayat).

---

## 2. Mengelola Data Buku

1. Klik menu **Tambah Buku**, lengkapi formulir: Judul Buku, Pengarang, Tahun Terbit, ISBN, Jumlah Stok, dan Kategori Buku, kemudian klik **Simpan**.
2. Buku yang baru ditambahkan langsung muncul pada **Daftar Buku** (`buku/list.php`).
3. Gunakan bilah pencarian untuk memfilter data buku secara real-time berdasarkan judul.
4. Klik tombol **Edit** pada baris buku untuk memperbarui data buku, atau tombol **Hapus** untuk menghapus buku (sistem akan menampilkan dialog konfirmasi keamanan sebelum data dihapus).

---

## 3. Mengelola Data Anggota

1. Klik menu **Tambah Anggota**, lengkapi formulir: Nomor Anggota, Nama Anggota, Alamat, dan Nomor Telepon/HP, kemudian klik **Simpan**.
2. Seluruh data anggota terdaftar dapat dilihat, dicari, diedit, atau dihapus melalui menu **Daftar Anggota** (`anggota/list.php`).

---

## 4. Transaksi Peminjaman Buku Baru

1. Klik menu **Peminjaman Baru** (`peminjaman/tambah.php`).
2. Pilih nama **Anggota** peminjam dan judul **Buku** yang akan dipinjam. Dropdown buku secara otomatis memfilter hanya buku yang memiliki stok fisik tersedia (`stok > 0`).
3. Klik tombol **Simpan Peminjaman**.
4. Sistem secara transaksional mencatat riwayat peminjaman dan memotong stok fisik buku sebanyak 1 eksemplar. Pada halaman Beranda, kartu statistik "Sedang Dipinjam" akan bertambah secara otomatis.

---

## 5. Pengembalian Buku

1. Klik menu **Pengembalian** (`peminjaman/kembali.php`).
2. Daftar transaksi aktif yang berstatus `'dipinjam'` akan ditampilkan lengkap dengan informasi kontak peminjam, tanggal pinjam, dan tanggal jatuh tempo (14 hari).
3. Cari transaksi yang bersangkutan, kemudian klik tombol **Kembalikan**.
4. Sistem memproses pengembalian secara atomik: status transaksi diperbarui menjadi `'dikembalikan'`, tanggal kembali dicatat, dan stok fisik buku bertambah kembali 1 eksemplar di database.

---

## 6. Riwayat Peminjaman Buku

1. Klik menu **Riwayat** (`peminjaman/riwayat.php`).
2. Pilih nama anggota dari dropdown, kemudian klik **Tampilkan**.
3. Tabel akan menyajikan histori lengkap peminjaman anggota tersebut, mencakup judul buku, tanggal pinjam, tanggal kembali, serta status transaksi (Dipinjam atau Selesai).

---

## 7. Mengakhiri Sesi (Logout)

1. Klik tombol **Logout** pada bilah navigasi kanan atas.
2. Sesi autentikasi akan dimusnahkan secara aman, cookie sesi dibersihkan, dan sistem mengalihkan tampilan ke Beranda publik dalam mode tamu (read-only).
