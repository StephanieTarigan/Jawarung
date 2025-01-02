<?php
// Menangkap data dari URL
//$UserID = $_GET['UserID'];
$TotalBayar = $_GET['TotalBayar'];

// Kode virtual account yang harus disertakan dalam instruksi pembayaran
$VA_BCA = "1234567890"; // Ganti dengan kode VA sebenarnya jika ada integrasi dengan API

// Menampilkan halaman pembayaran
echo "
    <div style='text-align: center; padding: 50px;'>
        <h1>Pembayaran Virtual Account BCA</h1>
        <p>Total Pembayaran: Rp " . number_format($TotalBayar, 0, ',', '.') . "</p>
        <p>Silakan transfer ke Virtual Account BCA berikut:</p>
        <h2>$VA_BCA</h2>
        <p>Setelah transfer, harap konfirmasi pembayaran Anda di halaman ini.</p>
        <a href='konfirmasi_pembayaran.php?UserID=$UserID' style='padding: 10px; background-color: #34a853; color: white; text-decoration: none; border-radius: 5px;'>Konfirmasi Pembayaran</a>
    </div>
";
?>
