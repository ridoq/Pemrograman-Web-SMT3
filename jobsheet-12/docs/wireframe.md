# Wireframe & User Flow — SIMPUS-Mini-24

- Halaman yang sudah ada (Beranda, Daftar/Tambah Buku, Daftar/Tambah Anggota — Jobsheet 1-3)
- Belum mencakup fitur Login, Register, Dashboard Petugas, dan Peminjaman/Pengembalian.
Dokumen ini merancang wireframe untuk halaman-halaman tersebut sebelum diimplementasikan mulai Jobsheet 5 dan seterusnya.

## Aktor
- **Tamu**: hanya bisa melihat katalog buku (Beranda, Daftar Buku) tanpa login, serta mendaftar akun anggota secara mandiri.
- **Petugas**: login untuk mengakses seluruh fitur CRUD buku/anggota dan transaksi peminjaman/pengembalian.

## User Flow — Registrasi Tamu

```
[Tamu buka Web] -> [Klik Menu Login] -> [Pilih "Daftar di sini"] -> [Isi Form Register & Data Diri] -> [Sistem simpan & buat No. Anggota otomatis] -> [Muncul notifikasi sukses] -> [Redirect ke Form Login]
```

## User Flow — Peminjaman Buku

```
[Petugas Login] -> [Dashboard] -> [Pilih menu "Peminjaman Baru"] -> [Pilih Anggota (No. Anggota / Nama)] -> [Pilih Buku (Kategori != Referensi & Stok > 0)] -> [Simpan] -> [Stok buku berkurang 1] -> [Kembali ke Dashboard]
```

## User Flow — Pengembalian Buku

```
[Dashboard] -> [Menu "Pengembalian"] -> [Cari transaksi aktif (No. Anggota / Judul Buku)] -> [Sistem cek keterlambatan & kalkulasi denda] -> [Tandai "Dikembalikan"] -> [Stok buku bertambah 1] -> [Kembali ke Dashboard]
```

## Wireframe: Halaman Login

```
+--------------------------------------+
|              SIMPUS-Mini             |
|--------------------------------------|
|                                      |
|                Login                 |
|                                      |
|    Username                          |
|    [____________________________]    |
|                                      |
|    Password                          |
|    [____________________________]    |
|                                      |
|            [   Masuk   ]             |
|                                      |
|   Belum punya akun? Daftar di sini   |
+--------------------------------------+
```

## Wireframe: Halaman Register (Tamu -> Anggota)

```
+--------------------------------------+
|              SIMPUS-Mini             |
|--------------------------------------|
|                                      |
|               Register               |
|                                      |
|    Nama Lengkap                      |
|    [____________________________]    |
|                                      |
|    Username                          |
|    [____________________________]    |
|                                      |
|    Email / No. HP                    |
|    [____________________________]    |
|                                      |
|    Password                          |
|    [____________________________]    |
|                                      |
|    Konfirmasi Password               |
|    [____________________________]    |
|                                      |
|            [   Daftar   ]            |
|                                      |
|   Sudah punya akun? Masuk di sini    |
+--------------------------------------+
```

## Wireframe: Dashboard Petugas

```
+-------------------------------------------------------------------------------------------------+
| SIMPUS-Mini      Beranda | Buku | Anggota | Peminjaman | Pengembalian | (Petugas: Ridoo) Logout |
|-------------------------------------------------------------------------------------------------|
|  [Total Buku: 12]   [Total Anggota: 8]   [Sedang Dipinjam: 3]   [Buku Terlambat: 2]             |
|                                                                                                 |
|  Aksi Cepat:                                                                                    |
|  [ + Peminjaman Baru ]   [ + Pengembalian Buku ]   [ + Tambah Buku ]                            |
|                                                                                                 |
|  Transaksi Peminjaman Terbaru                                                                   |
|  ---------------------------------------------------------------------------------------------  |
|  No. Anggota | Nama Anggota | Judul Buku       | Tgl Pinjam | Jatuh Tempo | Status              |
|  A001        | Siti Aminah  | Bumi Manusia     | 15/08/2026 | 22/08/2026  | Dipinjam            |
|  A002        | Budi Santoso | Laskar Pelangi   | 01/08/2026 | 08/08/2026  | Terlambat           |
+-------------------------------------------------------------------------------------------------+
```

## Wireframe: Form Peminjaman

```
+-------------------------------------------------------------+
|  Form Peminjaman Buku                                       |
|-------------------------------------------------------------|
|  Pilih Anggota  : [ dropdown: (A001) Siti Aminah          v]|
|  Pilih Buku     : [ dropdown: Bumi Manusia (Stok: 2)      v]|
|  Kategori Buku  : [ Non-Fiksi (Bisa Dipinjam)              ]|
|  Tanggal Pinjam : [ auto: 31/08/2026                       ]|
|  Batas Kembali  : [ auto: 07/09/2026 (+7 hari)             ]|
|                                                             |
|                  [  Simpan Peminjaman  ]                    |
+-------------------------------------------------------------+
```

## Wireframe: Form Pengembalian

```
+----------------------------------------------------------------------------------------------+
|  Pengembalian Buku & Validasi Keterlambatan                                                  |
|----------------------------------------------------------------------------------------------|
|  Cari Transaksi Aktif : [ Ketik No. Anggota / Judul Buku ________________ ] [ Cari ]         |
|                                                                                              |
|  No. Anggota | Nama Anggota | Judul Buku      | Tgl Pinjam | Status / Denda    | Aksi        |
|  A001        | Siti Aminah  | Bumi Manusia    | 30/08/2026 | Tepat Waktu (Rp0) | [Kembalikan]|
|  A002        | Budi Santoso | Laskar Pelangi  | 01/08/2026 | Terlambat (Rp5rb) | [Kembalikan]|
+----------------------------------------------------------------------------------------------+
```

## Wireframe: Riwayat Peminjaman per Anggota

```
+---------------------------------------------------------------------------------------------+
|  Riwayat Transaksi Anggota — A001 (Siti Aminah)                                             |
|---------------------------------------------------------------------------------------------|
|  Judul Buku           | Kategori   | Tgl Pinjam | Tgl Kembali | Denda | Status              |
|  Laskar Pelangi       | Fiksi      | 01/07/2026 | 08/07/2026  | Rp 0  | Selesai             |
|  Bumi Manusia         | Non-Fiksi  | 15/08/2026 | -           | Rp 0  | Sedang Dipinjam     |
|  Ronggeng Dukuh Paruk | Fiksi      | 10/06/2026 | 20/06/2026  | Rp3rb | Selesai (Denda)     |
+---------------------------------------------------------------------------------------------+
```

## Identifikasi 4 Edge Case & Validasi Bisnis Sistem

1. **Peminjaman Buku Kategori "Referensi" (Field `kategori` pada Buku)**
   - *Kasus:* Anggota mencoba meminjam buku berkategori `Referensi` (seperti Kamus Besar atau Ensiklopedia).
   - *Aturan Bisnis:* Buku referensi **hanya boleh dibaca di ruang perpustakaan** dan dilarang dipinjam bawa pulang.
   - *Validasi:* Dropdown pilihan buku pada Form Peminjaman otomatis memfilter atau menolak buku kategori referensi dengan pesan peringatan: *"Buku kategori referensi hanya dapat dibaca di tempat"*.

2. **Peminjaman Buku dengan Stok Habis (`stok: 0` pada Buku)**
   - *Kasus:* Anggota ingin meminjam buku yang jumlah fisiknya di perpustakaan sedang kosong (stok = 0, contoh: buku 'Negeri 5 Menara').
   - *Aturan Bisnis:* Sistem tidak boleh memproses peminjaman jika stok $\le 0$.
   - *Validasi:* Dropdown form peminjaman hanya menampilkan buku yang memiliki `stok > 0`. Saat transaksi disimpan, stok buku otomatis berkurang 1.

3. **Peminjaman Judul Buku yang Sama 2x oleh Anggota yang Sama (Field `no_anggota` & `judul`)**
   - *Kasus:* Seorang anggota yang masih memegang 1 eksemplar buku mencoba meminjam eksemplar kedua dari judul buku yang sama.
   - *Aturan Bisnis:* 1 anggota tidak diizinkan meminjam lebih dari 1 eksemplar untuk judul buku yang sama secara bersamaan guna mencegah monopoli buku.
   - *Validasi:* Sistem mengecek tabel transaksi aktif. Jika kombinasi `no_anggota` dan `judul_buku` masih berstatus "Dipinjam", transaksi ditolak dengan notifikasi: *"Anggota masih meminjam salinan buku ini"*.

4. **Keterlambatan Pengembalian & Sinkronisasi Kartu "Buku Terlambat" (Field `tgl_pinjam` & Kartu Statistik)**
   - *Kasus:* Peminjam mengembalikan buku melewati batas waktu peminjaman (durasi > 7 hari).
   - *Aturan Bisnis:* Keterlambatan dikenakan denda harian otomatis dan menambah akumulasi data keterlambatan perpustakaan.
   - *Validasi:* Sistem otomatis mengubah status transaksi menjadi "Terlambat", menghitung nominal denda (Hari Terlambat x Rp 1.000), dan menambahkan counter pada kartu statistik `Buku Terlambat` di Dashboard.

## Konsistensi dengan Desain yang Sudah Berjalan
- Warna aksen, tipografi navbar, dan gaya tabel/kartu mengikuti `assets/css/style.css` yang sudah dibangun sejak Jobsheet 2-3 (palet `#320042`, grid 4 kolom simetris).
- Navbar memuat menu **Peminjaman** dan indikator status login (`(Petugas: Nama) Logout`).
- Data entitas buku dan anggota sepenuhnya sinkron dengan struktur kolom Jobsheet 1-3.