document.addEventListener('DOMContentLoaded', function () {
    // Inisialisasi untuk tabel bahan menipis
    var datatablesSimple = document.getElementById('datatablesSimple');
    if (datatablesSimple) {
        new simpleDatatables.DataTable(datatablesSimple);
    }

    // Inisialisasi untuk tabel barang menipis
    var datatablesSimpleBarangs = document.getElementById('datatablesSimpleBarangs');
    if (datatablesSimpleBarangs) {
        new simpleDatatables.DataTable(datatablesSimpleBarangs);
    }
});
