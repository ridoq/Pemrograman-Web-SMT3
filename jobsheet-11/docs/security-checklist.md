# Checklist Keamanan — Jobsheet 11 (SIMPUS-Mini)

Audit menyeluruh terhadap integritas dan ketahanan keamanan web pada Jobsheet 7–10, dengan matriks komparasi before/after.

| # | Kerentanan | Ditemukan di | Sebelum | Sesudah (Perbaikan / Hardening) |
|---|---|---|---|---|
| 1 | **SQL Injection** | Seluruh query di `buku/`, `anggota/`, `auth/` | Menggunakan prepared statement PDO (`:parameter`) sejak Jobsheet 8. | **Diaudit ulang & 100% aman** — Tidak ada query string concatenation dari `$_POST` atau `$_GET`. Pengujian payload injection `' OR '1'='1` pada login dan parameter `id` gagal bypass. |
| 2 | **XSS (Cross-Site Scripting)** | `buku/list.php`, `buku/edit.php`, `anggota/list.php`, `anggota/edit.php`, `includes/header.php` | Output data dari database dan query string dicetak tanpa escaping universal. | Dibungkus helper global `e()` (`includes/helpers.php` berbasis `htmlspecialchars(..., ENT_QUOTES, 'UTF-8')`). Pengujian input `<script>alert(1)</script>` ternetralisir dan dicetak sebagai teks literal murni. |
| 3 | **CSRF (Cross-Site Request Forgery)** | Form POST Tambah, Edit, Hapus (Buku & Anggota), Form Login, Form Register | Request POST tidak diverifikasi dengan token unik — rentan dipicu via cross-site request. | Dilindungi via `includes/csrf.php`: helper `csrf_field()` menyisipkan hidden input token 32-byte acak (`bin2hex(random_bytes(32))`) dan `csrf_verify()` memvalidasi kecocokan token session dengan `hash_equals()`. |
| 4 | **Validasi & Sanitasi Input** | `proses_tambah.php`, `proses_edit.php` (Buku & Anggota) | Validasi dasar wajib-isi dan numerik parsial. | Ditambahkan type-casting eksplisit `(int)` pada seluruh parameter `id`, `tahun`, dan `stok`, validasi whitelist kategori buku (`fiksi`, `non-fiksi`, `referensi`), serta pencegahan input negatif. |
| 5 | **Session Fixation** | `auth/proses_login.php` | ID session tetap konstan sebelum dan sesudah pengguna terautentikasi. | Diterapkan `session_regenerate_id(true)` seketika setelah `password_verify()` berhasil, memusnahkan ID session lama dan mencegah pembajakan sesi. |

---

## 🛡️ Catatan Arsitektur & Urutan Eksekusi
1. **Prioritas Guard Clause:**
   `includes/auth.php` selalu dieksekusi **sebelum** `includes/csrf.php` pada berkas aksi/proses — memastikan akses tanpa autentikasi langsung dialihkan (*redirect*) ke Login tanpa membebani verifikasi token CSRF.
2. **Penolakan CSRF Otentik:**
   Fungsi `csrf_verify()` merespons dengan HTTP Status Code `403 Forbidden` dan menghentikan proses (`die()`) seketika jika token hilang, manipulatif, atau kedaluwarsa.
