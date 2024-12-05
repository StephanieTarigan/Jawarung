<?php
include "../dbconfig.php"; // Pastikan koneksi database sudah benar

// Ambil ProdukID dari URL
$produkID = $_GET["ProdukID"];

// Query untuk menghapus data produk berdasarkan ProdukID
$sqlStatement = "DELETE FROM produk WHERE ProdukID='$produkID'";

// Eksekusi query
$query = mysqli_query($conn, $sqlStatement);

// Cek apakah query berhasil
if ($query) {
    // Jika berhasil, buat pesan sukses dan alihkan ke halaman lain (misalnya index.php)
    $succesMsg = "Penghapusan produk dengan ID " . $produkID . " berhasil";
    header("location:index.php?successMsg=$succesMsg");
} else {
    // Jika gagal, tampilkan pesan error
    echo "Gagal menghapus produk: " . mysqli_error($conn);
}

// Menutup koneksi database
mysqli_close($conn);
?>
