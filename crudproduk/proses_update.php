<?php
// Memanggil file koneksi.php untuk melakukan koneksi ke database
include('../service/koneksi.php'); // Menghubungkan ke database

// Membuat variabel untuk menampung data dari form
$id = $_POST['id'];
$nama_produk = mysqli_real_escape_string($koneksi, $_POST['nama_produk']);
$harga = mysqli_real_escape_string($koneksi, $_POST['harga']);
$deskripsi = mysqli_real_escape_string($koneksi, $_POST['deskripsi']);
$stok = mysqli_real_escape_string($koneksi, $_POST['stok']);
$foto_produk = isset($_FILES['foto_produk']['name']) ? $_FILES['foto_produk']['name'] : "";

// Cek apakah gambar baru diunggah
if ($foto_produk != "") {
    // Ekstensi file gambar yang diperbolehkan
    $ekstensi_diperbolehkan = array('png', 'jpg', 'jpeg');
    $x = explode('.', $foto_produk); // Memisahkan nama file dengan ekstensi
    $ekstensi = strtolower(end($x));  // Mendapatkan ekstensi file
    $file_tmp = $_FILES['foto_produk']['tmp_name'];  // Mendapatkan file sementara
    $angka_acak = rand(1, 999);  // Menghasilkan angka acak
    $nama_gambar_baru = $angka_acak . '-' . $foto_produk;  // Menyusun nama gambar baru

    // Memeriksa apakah ekstensi gambar valid
    if (in_array($ekstensi, $ekstensi_diperbolehkan) === true) {
        // Memindahkan file gambar ke folder 'uploads/'
        move_uploaded_file($file_tmp, 'uploads/' . $nama_gambar_baru);

        // Query untuk memperbarui data produk termasuk gambar baru
        $query = "UPDATE produk SET nama_produk = '$nama_produk', harga = '$harga', deskripsi = '$deskripsi', stok = '$stok', foto_produk = '$nama_gambar_baru' WHERE id = '$id'";
        $result = mysqli_query($koneksi, $query);

        // Memeriksa apakah query berhasil
        if (!$result) {
            die("Query gagal dijalankan: " . mysqli_errno($koneksi) . " - " . mysqli_error($koneksi));
        } else {
            // Tampilkan alert dan redirect ke halaman Dasboard.php
            echo "<script>alert('Data berhasil diubah.');window.location='Dasboard.php';</script>";
        }
    } else {
        // Jika ekstensi gambar tidak sesuai
        echo "<script>alert('Ekstensi gambar yang diperbolehkan hanya jpg, jpeg, atau png.');window.location='update.php?id=$id';</script>";
    }
} else {
    // Jika tidak ada gambar baru, hanya update data tanpa gambar
    $query = "UPDATE produk SET nama_produk = '$nama_produk', harga = '$harga', deskripsi = '$deskripsi', stok = '$stok' WHERE id = '$id'";
    $result = mysqli_query($koneksi, $query);

    // Memeriksa apakah query berhasil
    if (!$result) {
        die("Query gagal dijalankan: " . mysqli_errno($koneksi) . " - " . mysqli_error($koneksi));
    } else {
        // Tampilkan alert dan redirect ke halaman Dasboard.php
        echo "<script>alert('Data berhasil diubah.');window.location='Dasboard.php';</script>";
    }
}
?>
