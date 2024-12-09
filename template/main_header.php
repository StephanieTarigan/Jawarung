<?php
define('HOST', "http://localhost/webPro2024/jawarung")
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jawarung</title>
    <link rel="stylesheet" href="../style.css">
    <link href="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.css" rel="stylesheet" />
</head>

<body>
    <header class="header">
        <div class="logo">JA <span>Warung</span></div>
        <nav class="navbar">
            <a href="beranda.php">Beranda</a>
            <a href="produk.php">Produk</a>
            <a href="resep.php">Resep</a>
            <a href="warung.php">Warung</a>
            <a href="pengingat.php">Pengingat</a>
        </nav>
        <input type="text" placeholder="Cari Bahan Masakan/Resep" class="search-bar">

        <!-- Show login/register buttons if not logged in -->
        <?php if (isset($_SESSION['UserID'])): ?>
            <div class="user-profile">
                <img src="../images/profil/<?php echo $_SESSION['foto_profil']; ?>" class="profil-img">
                <span><?php echo $_SESSION['username']; ?></span>
                <a href="../logout.php" class="logout-button">Logout</a>
            </div>
        <?php else: ?>
            <a href="login.php" class="login-button">Masuk</a>
            <a href="register.php" class="register-button">Daftar</a>

        <?php endif; ?>
    </header>