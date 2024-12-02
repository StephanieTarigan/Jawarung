<?php
// Memanggil file koneksi.php untuk melakukan koneksi ke database
include('../service/koneksi.php'); // Menghubungkan ke database

// Membuat variabel untuk menampung data dari form
$id = $_POST['id'];
$nama = mysqli_real_escape_string($koneksi, $_POST['nama']);  // Perbaiki penamaan menjadi 'nama_produk'
$deskripsi = mysqli_real_escape_string($koneksi, $_POST['deskripsi']);
$waktu = mysqli_real_escape_string($koneksi, $_POST['waktu']);
$porsi = mysqli_real_escape_string($koneksi, $_POST['porsi']);
$gambar_produk = isset($_FILES['gambar']['name']) ? $_FILES['gambar']['name'] : "";

// Cek apakah gambar baru diunggah
if($gambar_produk != "") {
    // Ekstensi file gambar yang diperbolehkan
    $ekstensi_diperbolehkan = array('png', 'jpg');
    $x = explode('.', $gambar_produk); // Memisahkan nama file dengan ekstensi
    $ekstensi = strtolower(end($x));  // Mendapatkan ekstensi file
    $file_tmp = $_FILES['gambar']['tmp_name'];  // Mendapatkan file sementara
    $angka_acak = rand(1,999);  // Menghasilkan angka acak
    $nama_gambar_baru = $angka_acak . '-' . $gambar_produk;  // Menyusun nama gambar baru

    // Memeriksa apakah ekstensi gambar valid
    if(in_array($ekstensi, $ekstensi_diperbolehkan) === true) {
        // Memindahkan file gambar ke folder 'gambar/'
        move_uploaded_file($file_tmp, 'uploads/' . $nama_gambar_baru);

        // Query untuk memperbarui data produk termasuk gambar baru
        $query = "UPDATE resep SET nama = '$nama', deskripsi = '$deskripsi', waktu = '$waktu', porsi = '$porsi', gambar = '$nama_gambar_baru' WHERE id = '$id'";
        $result = mysqli_query($koneksi, $query);

        // Memeriksa apakah query berhasil
        if(!$result) {
            die("Query gagal dijalankan: " . mysqli_errno($koneksi) . " - " . mysqli_error($koneksi));
        } else {
            // Tampilkan alert dan redirect ke halaman index.php
            echo "<script>alert('Data berhasil diubah.');window.location='Dasboard.php';</script>";
        }
    } else {
        // Jika ekstensi gambar tidak sesuai
        echo "<script>alert('Ekstensi gambar yang diperbolehkan hanya jpg atau png.');window.location='update.php?id=$id';</script>";
    }
} else {
    // Jika tidak ada gambar baru, hanya update data tanpa gambar
    $query = "UPDATE resep SET nama = '$nama', deskripsi = '$deskripsi', waktu = '$waktu', porsi = '$porsi' WHERE id = '$id'";
    $result = mysqli_query($koneksi, $query);

    // Memeriksa apakah query berhasil
    if(!$result) {
        die("Query gagal dijalankan: " . mysqli_errno($koneksi) . " - " . mysqli_error($koneksi));
    } else {
        // Tampilkan alert dan redirect ke halaman index.php
        echo "<script>alert('Data berhasil diubah.');window.location='Dasboard.php';</script>";
    }
}
?>
