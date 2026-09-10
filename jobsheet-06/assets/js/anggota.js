// Mengambil & menampilkan Daftar Anggota menggunakan fungsi generik muatDataTabel (Latihan §8.4 No. 2)
function muatDaftarAnggota() {
    return muatDataTabel({
        url: "../data/anggota.json",
        tbodySelector: ".table-responsive table tbody",
        loadingId: "loading-indicator",
        delay: 800, // Latihan §8.4 No. 5: delay 800ms agar visual loading terasa pas
        colspan: 5, // 5 kolom (No. Anggota, Nama, Alamat, No. HP, Aksi)
        renderRow: function (anggota) {
            const tr = document.createElement("tr");
            tr.innerHTML =
                "<td>" + anggota.no_anggota + "</td>" +
                "<td>" + anggota.nama + "</td>" +
                "<td>" + anggota.alamat + "</td>" +
                "<td>" + anggota.no_hp + "</td>" +
                "<td>" +
                "<button type=\"button\">Edit</button> " +
                "<button type=\"button\" class=\"btn-hapus\">Hapus</button>" +
                "</td>";
            return tr;
        }
    });
}

document.addEventListener("DOMContentLoaded", function () {
    muatDaftarAnggota();
    const btnReload = document.getElementById("btn-reload");
    if (btnReload) {
        btnReload.addEventListener("click", function () {
            muatDaftarAnggota();
        });
    }
});
