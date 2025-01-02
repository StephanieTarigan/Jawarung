<?php
// Konfirmasi pembayaran
$UserID = $_GET['UserID'];

// Update status pembayaran menjadi "Dibayar" setelah konfirmasi
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "highdsd";
$conn = new mysqli($servername, $username, $password, $dbname);

// Memperbarui status pembayaran menjadi "Dibayar"
$StatusPembayaran = "Dibayar";
$stmt = $conn->prepare("UPDATE pembayaran SET StatusPembayaran = ? WHERE UserID = ?");
$stmt->bind_param("si", $StatusPembayaran, $UserID);

if ($stmt->execute()) {
    echo "
    <script>
        alert('Pembayaran Anda telah dikonfirmasi!');
        window.location.href = 'index.php';
    </script>";
} else {
    echo "
    <script>
        alert('Terjadi kesalahan: " . $conn->error . "');
        window.history.back();
    </script>";
}

$conn->close();
?>
