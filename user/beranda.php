<?php
session_start();
include "../dbconfig.php";
include "../template/main_layout.php";

// Memeriksa apakah pengguna sudah login
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'pelanggan') {
    header("Location: /login.php");
    exit();
}

// Query untuk mengambil produk
$queryProduk = "SELECT produk.ProdukID, produk.NamaProduk, produk.DeskripsiProduk, produk.Harga, fotoproduk.FotoPath 
                FROM produk 
                LEFT JOIN fotoproduk ON produk.ProdukID = fotoproduk.ProdukID 
                GROUP BY produk.ProdukID";
$resultProduk = mysqli_query($conn, $queryProduk);

if (!$resultProduk) {
    die("Query Error Produk: " . mysqli_error($conn));
}

// Query untuk mengambil resep
$queryResep = "SELECT resep.ResepID, resep.NamaResep, resep.DeskripsiResep, fotoresep.FotoPath 
               FROM resep 
               LEFT JOIN fotoresep ON resep.ResepID = fotoresep.ResepID 
               GROUP BY resep.ResepID";
$resultResep = mysqli_query($conn, $queryResep);

if (!$resultResep) {
    die("Query Error Resep: " . mysqli_error($conn));
}

?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beranda Pelanggan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
</head>

<body>
    <div class="container mt-4">
        <h2 class="text-center">Selamat Datang di Dashboard Pelanggan</h2>

        <!-- Produk -->
        <div class="mt-5">
            <h3>Produk Tersedia</h3>
            <div class="row g-3">
                <?php while ($rowProduk = mysqli_fetch_assoc($resultProduk)): ?>
                    <div class="col-md-4">
                        <div class="card h-100">
                            <img src="<?= !empty($rowProduk['FotoPath']) && file_exists("../admin/" . $rowProduk['FotoPath']) 
                                         ? "../admin/" . htmlspecialchars($rowProduk['FotoPath']) 
                                         : "../admin/uploads/default.jpg"; ?>" 
                                 class="card-img-top" 
                                 alt="Foto Produk" 
                                 style="object-fit: cover; height: 200px;">
                            <div class="card-body">
                                <h5 class="card-title"><?= htmlspecialchars($rowProduk['NamaProduk']); ?></h5>
                                <p class="card-text"><?= htmlspecialchars($rowProduk['DeskripsiProduk']); ?></p>
                                <p class="text-success">Harga: Rp<?= number_format($rowProduk['Harga'], 0, ',', '.'); ?></p>
                                <a href="detail_produk.php?id=<?= $rowProduk['ProdukID']; ?>" class="btn btn-success btn-sm">Detail</a>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>

        <!-- Resep -->
        <div class="mt-5">
            <h3>Resep Masakan</h3>
            <div class="row g-3">
                <?php while ($rowResep = mysqli_fetch_assoc($resultResep)): ?>
                    <div class="col-md-4">
                        <div class="card h-100">
                            <img src="<?= !empty($rowResep['FotoPath']) && file_exists("../admin/" . $rowResep['FotoPath']) 
                                         ? "../admin/" . htmlspecialchars($rowResep['FotoPath']) 
                                         : "../admin/uploads/default.jpg"; ?>" 
                                 class="card-img-top" 
                                 alt="Foto Resep" 
                                 style="object-fit: cover; height: 200px;">
                            <div class="card-body">
                                <h5 class="card-title"><?= htmlspecialchars($rowResep['NamaResep']); ?></h5>
                                <p class="card-text"><?= htmlspecialchars($rowResep['DeskripsiResep']); ?></p>
                                <a href="detail_resep.php?id=<?= $rowResep['ResepID']; ?>" class="btn btn-success btn-sm">Lihat Resep</a>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>

<?php
// Menutup koneksi database
mysqli_close($conn);
?>
