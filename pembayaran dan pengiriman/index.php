<?php
session_start();

// Contoh: Set user_id dalam session untuk simulasi
if (!isset($_SESSION['user_id'])) {
    $_SESSION['user_id'] = 1; // Asumsi user ID = 1
}

// Halaman index menampilkan menu utama
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>eCommerce App</title>
    <style>
    body {
        font-family: Arial, sans-serif;
        line-height: 1.6;
    }
    nav {
        background-color: #d4edda; 
        padding: 1em;
    }
    nav a {
        margin: 0 10px;
        text-decoration: none;
        color: #155724;
    }
    nav a:hover {
        text-decoration: underline;
        color: #0c3e13; 
    }
</style>

</head>
<body>
    <header>
        <h1>JAWARUNG</h1>
    </header>
    <nav>
        <a href="pembayaran/checkout.php">Checkout</a>
        <a href="pembayaran/validate_pembayaran.php?payment_id=1">Validate Pembayaran</a>
        <a href="pembayaran/update_pembayaran.php">Update Pembayaran</a>
        <a href="pengiriman/create_pengiriman.php">Create Pengiriman</a>
        <a href="pengiriman/update_pengiriman.php">Update Pengiriman</a>
    </nav>
</body>
</html>
