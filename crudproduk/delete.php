<?php
 include('../service/koneksi.php'); // Menghubungkan ke database

// Mendapatkan ID dari URL
$id = $_GET["id"];

// Menyiapkan query DELETE dengan prepared statement
$query = "DELETE FROM produk WHERE id = ?";

// Membuat prepared statement
$stmt = mysqli_prepare($koneksi, $query);

// Mengikat parameter (menggunakan tipe integer untuk ID)
mysqli_stmt_bind_param($stmt, "i", $id);

// Menjalankan query
$hasil_query = mysqli_stmt_execute($stmt);

// Memeriksa hasil query
if(!$hasil_query) {
    die("Gagal menghapus data: ".mysqli_errno($koneksi)." - ".mysqli_error($koneksi));
} else {
    echo "<script>alert('Data berhasil dihapus.');window.location='view_dataproduk.php';</script>";
}

// Menutup statement setelah eksekusi
mysqli_stmt_close($stmt);
?>
