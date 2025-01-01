<?php
include "../dbconfig.php"; // Pastikan koneksi database sudah benar

// Ambil ResepID dari URL
$resepID = $_GET["ResepID"];

// Mulai transaksi
mysqli_begin_transaction($conn);

try {
    // Hapus data terkait di tabel langkahMemasak
    $sqlDeleteLangkah = "DELETE FROM langkahMemasak WHERE ResepID = '$resepID'";
    mysqli_query($conn, $sqlDeleteLangkah);

    // Hapus data terkait di tabel resepBahan
    $sqlDeleteBahan = "DELETE FROM resepBahan WHERE ResepID = '$resepID'";
    mysqli_query($conn, $sqlDeleteBahan);

    // Hapus data terkait di tabel fotoResep
    $sqlDeleteFoto = "DELETE FROM fotoResep WHERE ResepID = '$resepID'";
    mysqli_query($conn, $sqlDeleteFoto);

    // Hapus data terkait di tabel kategoriResep
    $sqlDeleteKategori = "DELETE FROM kategoriResep WHERE ResepID = '$resepID'";
    mysqli_query($conn, $sqlDeleteKategori);

    // Hapus data utama di tabel resep
    $sqlDeleteResep = "DELETE FROM resep WHERE ResepID = '$resepID'";
    mysqli_query($conn, $sqlDeleteResep);

    // Commit transaksi
    mysqli_commit($conn);

    // Jika berhasil, buat pesan sukses dan alihkan ke halaman lain (misalnya indexResep.php)
    $successMsg = "Penghapusan resep dengan ID " . $resepID . " berhasil";
    header("location:indexResep.php?successMsg=$successMsg");
} catch (Exception $e) {
    // Rollback jika ada kesalahan
    mysqli_rollback($conn);

    // Jika gagal, tampilkan pesan error
    echo "Gagal menghapus resep: " . $e->getMessage();
}

// Menutup koneksi database
mysqli_close($conn);
?>
