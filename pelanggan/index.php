<?php
session_start();
include "../dbconfig.php";
include "../template/main_layout.php";

// Mengecek apakah pengguna sudah login dan apakah perannya adalah pelanggan
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'pelanggan') {
    // Jika tidak login atau bukan pelanggan, arahkan kembali ke login
    header("Location: /login.php");
    exit;
}

?>

<body>
    <h3>Selamat datang, Pelanggan!</h3>
    <!-- Konten halaman pelanggan -->
</body>
</html>
