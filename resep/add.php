<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_resep = $_POST['nama_resep'];
    $kategori = $_POST['kategori'];
    $waktu_total = $_POST['waktu_total'];
    $waktu_persiapan = $_POST['waktu_persiapan'];
    $waktu_memasak = $_POST['waktu_memasak'];
    $bahan = $_POST['bahan'];
    $cara_membuat = $_POST['cara_membuat'];
    $rating = $_POST['rating'];
    $ulasan = $_POST['ulasan'];

    $query = "INSERT INTO resep (nama_resep, kategori, waktu_total, waktu_persiapan, waktu_memasak, bahan, cara_membuat, rating, ulasan)
              VALUES ('$nama_resep', '$kategori', '$waktu_total', '$waktu_persiapan', '$waktu_memasak', '$bahan', '$cara_membuat', '$rating', '$ulasan')";

    if ($conn->query($query) === TRUE) {
        header("Location: index.php");
    } else {
        echo "Error: " . $query . "<br>" . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Resep</title>
</head>
<body>
    <h1>Tambah Resep</h1>
    <form method="POST">
        <label>Nama Resep: <input type="text" name="nama_resep" required></label><br>
        <label>Kategori: <input type="text" name="kategori"></label><br>
        <label>Waktu Total: <input type="text" name="waktu_total"></label><br>
        <label>Waktu Persiapan: <input type="text" name="waktu_persiapan"></label><br>
        <label>Waktu Memasak: <input type="text" name="waktu_memasak"></label><br>
        <label>Bahan: <textarea name="bahan" required></textarea></label><br>
        <label>Cara Membuat: <textarea name="cara_membuat" required></textarea></label><br>
        <label>Rating: <input type="number" step="0.1" name="rating"></label><br>
        <label>Ulasan: <textarea name="ulasan"></textarea></label><br>
        <button type="submit">Simpan</button>
    </form>
</body>
</html>
