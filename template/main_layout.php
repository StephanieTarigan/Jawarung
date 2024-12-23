<?php
// Cek apakah pengguna sudah login
if (isset($_SESSION['valid'])) {
    $UserID = $_SESSION['UserID'];  // Mengambil UserID dari session


    // Query untuk mengambil foto profil
    $query = "SELECT ProfilePicture FROM users WHERE UserID = '$UserID'";
    $result = mysqli_query($conn, $query);

    if ($result) {
        $row = mysqli_fetch_assoc($result);
        // Ambil path foto profil, jika tidak ada gunakan gambar default
        $profile_picture = $row['ProfilePicture'] ? $row['ProfilePicture'] : 'uploads/profile_pics/default.jpg';
    } else {
        // Jika foto profil tidak ditemukan, gunakan foto default
        $profile_picture = 'uploads/profile_pics/default.jpg';
    }
} else {
    // Jika belum login, gunakan foto default
    $profile_picture = 'uploads/profile_pics/default.jpg';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jawarung</title>
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body>
    <header class="header">
        <div class="logo">JA <span>Warung</span></div>
        <!-- Navbar -->
        <nav class="navbar">
            <a href="beranda.php">Beranda</a>
            <a href="add_produk.php">Produk</a>
            <a href="add_resep.php">Resep</a>
            <a href="add_warung.php">Warung</a>
            <a href="add_pengingat">Pengingat</a>
        </nav>
        <input type="text" placeholder="Cari Bahan Masakan/Resep" class="search-bar">
        <div class="cart cart-badge">
            <i class="bi bi-cart"></i>
        </div>
        <div class="profile-section">
            <a href="profil.php" class="profile-link">
                <img src="<?php echo $profile_picture; ?>" alt="Profil" class="profile-image">
            </a>
        </div>
    </header>
</body>
</html>
