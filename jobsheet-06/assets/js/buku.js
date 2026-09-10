// Mengambil & menampilkan Daftar Buku menggunakan fungsi generik muatDataTabel (Latihan §8.4 No. 2)
function muatDaftarBuku() {
    return muatDataTabel({
        url: "../data/buku.json",
        tbodySelector: ".table-responsive table tbody",
        loadingId: "loading-indicator",
        delay: 800, // Latihan §8.4 No. 5: delay 800ms agar visual loading terasa pas
        colspan: 6, // 6 kolom (Judul, Pengarang, Tahun, Kategori, Stok, Aksi)
        renderRow: function (buku) {
            const tr = document.createElement("tr");
            // Latihan §8.4 No. 3: Menampilkan kolom kategori di baris tabel
            tr.innerHTML =
                "<td>" + buku.judul + "</td>" +
                "<td>" + buku.pengarang + "</td>" +
                "<td>" + buku.tahun + "</td>" +
                "<td>" + (buku.kategori || "-") + "</td>" +
                "<td>" + buku.stok + "</td>" +
                "<td>" +
                "<button type=\"button\">Edit</button> " +
                "<button type=\"button\" class=\"btn-hapus\">Hapus</button>" +
                "</td>";
            return tr;
        }
    });
}

document.addEventListener("DOMContentLoaded", function () {
    // Muat data buku pertama kali
    muatDaftarBuku();

    // Latihan §8.4 No. 1: Pasang event listener pada tombol "Muat Ulang"
    const btnReload = document.getElementById("btn-reload");
    if (btnReload) {
        btnReload.addEventListener("click", function () {
            muatDaftarBuku();
        });
    }
});
