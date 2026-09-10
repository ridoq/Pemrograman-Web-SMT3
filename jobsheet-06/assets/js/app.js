
function initNavToggle() {
    const toggleBtn = document.getElementById("nav-toggle-btn");
    const nav = document.querySelector("header nav");
    if (!toggleBtn || !nav) return;

    toggleBtn.addEventListener("click", function () {
        nav.classList.toggle("nav-open");
    });
}

function updateTableCounter() {
    const counterEl = document.getElementById("table-counter");
    const table = document.querySelector(".table-responsive table");
    if (!counterEl || !table) return;

    const allRows = table.querySelectorAll("tbody tr");
    const totalRows = allRows.length;
    let visibleRows = 0;

    allRows.forEach(function (row) {
        if (row.style.display !== "none") {
            visibleRows++;
        }
    });

    const isBuku = window.location.pathname.includes("buku");
    const labelData = isBuku ? "buku" : "anggota";

    if (totalRows === 0) {
        counterEl.textContent = "Belum ada data " + labelData + ".";
    } else if (visibleRows === 0) {
        counterEl.textContent = "Tidak ada " + labelData + " yang cocok dengan pencarian (0 dari " + totalRows + " " + labelData + ").";
    } else {
        counterEl.textContent = "Menampilkan " + visibleRows + " dari " + totalRows + " " + labelData + ".";
    }
}

// Memakai event delegation di document karena baris tabel sekarang
// dirender dinamis via fetch (lihat buku.js/anggota.js) sehingga
// tombol .btn-hapus belum tentu ada saat DOMContentLoaded.
function initHapusConfirm() {
    document.addEventListener("click", function (e) {
        const btn = e.target.closest(".btn-hapus");
        if (!btn) return;

        const row = btn.closest("tr");
        const nama = row ? row.querySelector("td")?.textContent.trim() : "data ini";
        const yakin = confirm("Yakin ingin menghapus \"" + nama + "\"?");
        if (yakin && row) {
            row.remove();
            updateTableCounter();
        }
    });
}

function initTableFilter() {
    const input = document.getElementById("search-input");
    const table = document.querySelector(".table-responsive table");
    if (!input || !table) return;

    const isAnggota = window.location.pathname.includes("anggota");

    input.addEventListener("keyup", function () {
        const keyword = input.value.toLowerCase().trim();
        const rows = table.querySelectorAll("tbody tr");
        rows.forEach(function (row) {
            // Latihan 3 (§8.4): Batasi pencarian hanya ke satu kolom spesifik
            // Halaman buku: kolom 1 (Judul), Halaman anggota: kolom 2 (Nama)
            const targetCell = isAnggota
                ? row.querySelectorAll("td")[1]
                : row.querySelector("td");

            const teks = targetCell ? targetCell.textContent.toLowerCase() : "";
            row.style.display = teks.includes(keyword) ? "" : "none";
        });
        updateTableCounter();
    });
}

function tampilkanError(input, pesan) {
    hapusError(input);
    const span = document.createElement("span");
    span.className = "error";
    span.textContent = pesan;
    input.insertAdjacentElement("afterend", span);
}

function hapusError(input) {
    const next = input.nextElementSibling;
    if (next && next.classList.contains("error")) {
        next.remove();
    }
}

function initValidasiForm() {
    const form = document.getElementById("form-tambah");
    if (!form) return;

    form.addEventListener("submit", function (e) {
        let valid = true;

        const fieldWajib = [
            { name: "judul", label: "Judul" },
            { name: "nama", label: "Nama" },
            { name: "pengarang", label: "Pengarang" },
            { name: "no_anggota", label: "No. Anggota" }
        ];

        fieldWajib.forEach(function (field) {
            const input = form.querySelector("[name='" + field.name + "']");
            if (input) {
                if (input.value.trim() === "") {
                    tampilkanError(input, field.label + " wajib diisi.");
                    valid = false;
                } else {
                    hapusError(input);
                }
            }
        });

        const tahun = form.querySelector("[name='tahun']");
        if (tahun) {
            const nilai = parseInt(tahun.value, 10);
            if (isNaN(nilai) || nilai < 1900 || nilai > 2026) {
                tampilkanError(tahun, "Tahun harus di antara 1900-2026.");
                valid = false;
            } else {
                hapusError(tahun);
            }
        }

        const isbn = form.querySelector("[name='isbn']");
        if (isbn && isbn.value.trim() !== "") {
            const isbnPattern = /^[0-9-]+$/;
            if (!isbnPattern.test(isbn.value.trim())) {
                tampilkanError(isbn, "ISBN hanya boleh berisi angka dan tanda hubung (-).");
                valid = false;
            } else {
                hapusError(isbn);
            }
        } else if (isbn) {
            hapusError(isbn);
        }

        const noHp = form.querySelector("[name='no_hp']");
        if (noHp && noHp.value.trim() !== "") {
            const hpPattern = /^[0-9+\s-]+$/;
            if (!hpPattern.test(noHp.value.trim())) {
                tampilkanError(noHp, "No. HP hanya boleh berisi angka dan simbol (+, -).");
                valid = false;
            } else {
                hapusError(noHp);
            }
        } else if (noHp) {
            hapusError(noHp);
        }

        const stok = form.querySelector("[name='stok']");
        if (stok) {
            const nilai = parseInt(stok.value, 10);
            if (isNaN(nilai) || nilai < 0) {
                tampilkanError(stok, "Stok tidak boleh negatif.");
                valid = false;
            } else {
                hapusError(stok);
            }
        }

        if (!valid) {
            e.preventDefault();
        }
    });
}

document.addEventListener("DOMContentLoaded", function () {
    initNavToggle();
    initHapusConfirm();
    initTableFilter();
    initValidasiForm();
    updateTableCounter();
});
