<?php
include 'config.php';
$id = $_GET['id'];
$query = "SELECT * FROM resep WHERE id=$id";
$result = $conn->query($query);
$resep = $result->fetch_assoc();

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

    $query = "UPDATE resep SET 
                nama_resep='$nama_resep', 
                kategori='$kategori', 
                waktu_total='$waktu_total',
                waktu_persiapan='$waktu_persiapan',
                waktu_memasak='$waktu_memasak',
                bahan='$bahan',
                cara_membuat='$cara_membuat',
                rating='$rating',
                ulasan='$ulasan'
              WHERE id=$id";

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
    <title>Edit Resep</title>
</head>
<body>
    <h1>Edit Resep</h1>
    <form method="POST">
        <label>Nama Resep: <input type="text" name="nama_resep" value="<?= $resep['nama_resep'] ?>" required></label><br>
        <label>Kategori: <input type="text" name="kategori" value="<?= $resep['kategori'] ?>"></label><br>
        <label>Waktu Total: <input type="text" name="waktu_total" value="<?= $resep['waktu_total'] ?>"></label><br>
        <label>Waktu Persiapan: <input type="text" name="waktu_persiapan" value="<?= $resep['waktu_persiapan'] ?>"></label><br>
        <label>Waktu Memasak: <input type="text" name="waktu_memasak" value="<?= $resep['waktu_memasak'] ?>"></label><br>
        <label>Bahan: <textarea name="bahan"><?= $resep['bahan'] ?></textarea></label><br>
        <label>Cara Membuat: <textarea name="cara_membuat"><?= $resep['cara_membuat'] ?></textarea></label><br>
        <label>Rating: <input type="number" step="0.1" name="rating" value="<?= $resep['rating'] ?>"></label><br>
        <label>Ulasan: <textarea name="ulasan"><?= $resep['ulasan'] ?></textarea></label><br>
        <button type="submit">Update</button>
    </form>
</body>
</html>
