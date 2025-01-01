<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "highdsd";

// Membuat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);

// Memeriksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Mendapatkan data dari form
$Userid = $_POST['Userid'];
$TotalBayar = $_POST['TotalBayar'];
$MetodePembayaran = $_POST['MetodePembayaran'];
$AlamatTujuan = $_POST['AlamatTujuan'];
$Kabupaten = !empty($_POST['Kabupaten']) ? $_POST['Kabupaten'] : NULL;
$Kota = !empty($_POST['Kota']) ? $_POST['Kota'] : NULL;
$Kecamatan = $_POST['Kecamatan'];
$KodePos = $_POST['KodePos'];
$BiayaPengiriman = $_POST['BiayaPengiriman'];

// Tanggal sekarang
$TanggalPembayaran = date("Y-m-d H:i:s");
$TanggalPengiriman = date("Y-m-d H:i:s");

// Default status
$StatusPembayaran = "Pending";
$StatusPengiriman = "Pending";

// Validasi kabupaten atau kota
if (is_null($Kabupaten) && is_null($Kota)) {
    die("Kabupaten atau Kota harus diisi!");
}

// Debug data $_POST
echo "<pre>";
print_r($_POST);
echo "</pre>";

// Query untuk tabel Pembayaran
$sqlPembayaran = "INSERT INTO pembayaran (UserID, TotalBayar, TanggalPembayaran, MetodePembayaran, StatusPembayaran)
                  VALUES ('$Userid', '$TotalBayar', '$TanggalPembayaran', '$MetodePembayaran', '$StatusPembayaran')";

// Query untuk tabel Pengiriman
$sqlPengiriman = "INSERT INTO pengiriman (UserID, AlamatTujuan, Kabupaten, Kota, Kecamatan, KodePos, TanggalPengiriman, StatusPengiriman, BiayaPengiriman)
                  VALUES ('$Userid', '$AlamatTujuan', " . 
                  ($Kabupaten ? "'$Kabupaten'" : "NULL") . ", " . 
                  ($Kota ? "'$Kota'" : "NULL") . ", 
                  '$Kecamatan', '$KodePos', '$TanggalPengiriman', '$StatusPengiriman', '$BiayaPengiriman')";

// Mengeksekusi query
if ($conn->query($sqlPembayaran) === TRUE && $conn->query($sqlPengiriman) === TRUE) {
    echo "
    <script>
        alert('Data pembayaran dan pengiriman berhasil disimpan!');
        window.location.href = 'index.php'; // Redirect ke halaman utama
    </script>";
} else {
    echo "
    <script>
        alert('Terjadi kesalahan: " . $conn->error . "');
        window.history.back(); // Kembali ke halaman sebelumnya
    </script>";
}

$conn->close();
?>
