<?php
include 'config.php';
$id = $_GET['id'];
$query = "SELECT * FROM resep WHERE id=$id";
$result = $conn->query($query);
$resep = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $resep['nama_resep'] ?></title>
</head>
<body>
    <h1><?= $resep['nama_resep'] ?></h1>
    <p><strong>Kategori:</strong> <?= $resep['kategori'] ?></p>
    <p><strong>Waktu Total:</strong> <?= $resep['waktu_total'] ?></p>
    <p><strong>Waktu Persiapan:</strong> <?= $resep['waktu_persiapan'] ?></p>
    <p><strong>Waktu Memasak:</strong> <?= $resep['waktu_memasak'] ?></p>
    <h2>Bahan</h2>
    <p><?= nl2br($resep['bahan']) ?></p>
    <h2>Cara Membuat</h2>
    <p><?= nl2br($resep['cara_membuat']) ?></p>
    <p><strong>Rating:</strong> <?= $resep['rating'] ?></p>
    <p><strong>Ulasan:</strong> <?= nl2br($resep['ulasan']) ?></p>
    <a href="index.php">Kembali</a>
</body>
</html>
