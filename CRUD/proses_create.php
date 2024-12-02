<?php
include('../service/koneksi.php');

// Mengambil data dari form
$nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
$deskripsi = mysqli_real_escape_string($koneksi, $_POST['deskripsi']);
$waktu = mysqli_real_escape_string($koneksi, $_POST['waktu']);
$porsi = mysqli_real_escape_string($koneksi, $_POST['porsi']);
$gambar_produk = isset($_FILES['gambar']['name']) ? $_FILES['gambar']['name'] : "";

// Jika gambar ada
if ($gambar_produk != "") {
    $ekstensi_diperbolehkan = array('png', 'jpg'); // ekstensi file gambar yang diperbolehkan
    $x = explode('.', $gambar_produk); // memisahkan nama file dengan ekstensi
    $ekstensi = strtolower(end($x));
    $file_tmp = $_FILES['gambar']['tmp_name'];  // Correct the array key here
    $angka_acak = rand(1, 999); // membuat angka acak untuk nama gambar
    $nama_gambar_baru = $angka_acak . '-' . $gambar_produk; // menggabungkan angka acak dan nama gambar

    if (in_array($ekstensi, $ekstensi_diperbolehkan)) {
        move_uploaded_file($file_tmp, 'uploads/' . $nama_gambar_baru); // memindahkan gambar ke folder 'gambar'

        // Insert data ke dalam database, termasuk gambar
        $query = "INSERT INTO resep (nama, deskripsi, waktu, porsi, gambar) VALUES ('$nama', '$deskripsi', '$waktu', '$porsi', '$nama_gambar_baru')";
        $result = mysqli_query($koneksi, $query);

        if ($result) {
            echo "<script>alert('Data berhasil ditambah.');window.location='Dasboard.php';</script>";
        } else {
            echo "<script>alert('Gagal menambah data.');window.location='Dasboard.php';</script>";
        }
    } else {
        echo "<script>alert('Ekstensi gambar yang boleh hanya jpg atau png.');window.location='create.php';</script>";
    }
} else {
    // Jika gambar tidak ada, insert tanpa gambar
    $query = "INSERT INTO resep (nama, deskripsi, waktu, porsi) VALUES ('$nama', '$deskripsi', '$waktu', '$porsi')";
    $result = mysqli_query($koneksi, $query);

    if ($result) {
        echo "<script>alert('Data berhasil ditambah tanpa gambar.');window.location='Dasboard.php';</script>";
    } else {
        echo "<script>alert('Gagal menambah data.');window.location='Dasboard.php';</script>";
    }
}
?>
