<?php
$servername = "localhost";
$username = "root";  
$password = ""; 
$dbname = "toko_online";

// Membuat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);

// Memeriksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Mendapatkan data dari form
$nama_pembeli = $_POST['nama_pembeli'];
$alamat_pengiriman = $_POST['alamat_pengiriman'];
$no_telepon = $_POST['no_telepon'];
$email = $_POST['email'];
$metode_pembayaran = $_POST['metode_pembayaran'];
$total_harga = $_POST['total_harga'];

// Menyiapkan query SQL
$sql = "INSERT INTO transaksi (nama_pembeli, alamat_pengiriman, no_telepon, email, metode_pembayaran, total_harga)
        VALUES ('$nama_pembeli', '$alamat_pengiriman', '$no_telepon', '$email', '$metode_pembayaran', '$total_harga')";

// Mengeksekusi query
if ($conn->query($sql) === TRUE) {
    echo "Transaksi berhasil dicatat!<br>";
    echo "<a href='index.html'>Kembali ke halaman utama</a>";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
