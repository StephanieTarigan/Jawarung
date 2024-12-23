<?php
// Memulai sesi
session_start();

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['role'])) {
    header("Location: login.php");
    exit();
}

// Hubungkan ke database
include "../dbconfig.php";
include "../template/main_layout.php";

// Query untuk Produk
$queryProduk = "
    SELECT 
        p.NamaProduk, 
        p.DeskripsiProduk, 
        fp.FotoPath 
    FROM produk p
    LEFT JOIN fotoProduk fp ON p.ProdukID = fp.ProdukID
";
$resultProduk = mysqli_query($conn, $queryProduk);

// Query untuk Resep
$queryResep = "
    SELECT 
        r.NamaResep, 
        r.DeskripsiResep, 
        fr.FotoPath 
    FROM resep r
    LEFT JOIN fotoresep fr ON r.ResepID = fr.ResepID
";
$resultResep = mysqli_query($conn, $queryResep);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beranda</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Main Content -->
            <div class="col py-3">
                <div class="container-fluid">
                    <h1 class="mb-4">Dashboard</h1>
                    <!-- Statistik Dashboard -->
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header text-white" style="background-color: #4a7345;">Pendapatan Total</div>
                                <div class="card-body">
                                    <h2 style="color: #4a7345;">Rp90.239.000</h2>
                                    <p><i class="bi bi-arrow-up text-success"></i> 15% dibanding minggu lalu</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header text-white" style="background-color: #4a7345;">Pesanan Baru</div>
                                <div class="card-body">
                                    <h2 class="text-warning">320</h2>
                                    <p><i class="bi bi-arrow-down text-danger"></i> 4% dibanding minggu lalu</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header text-white" style="background-color: #4a7345;">Rata-rata Pendapatan</div>
                                <div class="card-body">
                                    <h2 style="color: #4a7345;">Rp1.080.000</h2>
                                    <p><i class="bi bi-arrow-up text-success"></i> 8% dibanding minggu lalu</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Produk dan Resep -->
                    <div class="mt-5">
                        <h2>Semua Produk</h2>
                        <div class="row g-3">
                            <?php while ($rowProduk = mysqli_fetch_assoc($resultProduk)): ?>
                                <div class="col-md-4">
                                    <div class="card h-100">
                                        <?php if (!empty($rowProduk['FotoPath'])): ?>
                                            <img src="uploads/foto_produk/<?php echo htmlspecialchars($rowProduk['FotoPath']); ?>" 
                                                 class="card-img-top" alt="Foto Produk" style="object-fit: cover; height: 200px;">
                                        <?php else: ?>
                                            <img src="assets/no-image.jpg" class="card-img-top" alt="No Image" style="object-fit: cover; height: 200px;">
                                        <?php endif; ?>
                                        <div class="card-body">
                                            <h5 class="card-title"><?php echo htmlspecialchars($rowProduk['NamaProduk']); ?></h5>
                                            <p class="card-text"><?php echo htmlspecialchars($rowProduk['DeskripsiProduk']); ?></p>
                                        </div>
                                    </div>
                                </div>
                            <?php endwhile; ?>
                        </div>
                    </div>

                    <div class="mt-5">
                        <h2>Semua Resep</h2>
                        <div class="row g-3">
                            <?php while ($rowResep = mysqli_fetch_assoc($resultResep)): ?>
                                <div class="col-md-4">
                                    <div class="card h-100">
                                        <?php if (!empty($rowResep['FotoPath'])): ?>
                                            <img src="uploads/foto_resep/<?php echo htmlspecialchars($rowResep['FotoPath']); ?>" 
                                                 class="card-img-top" alt="Foto Resep" style="object-fit: cover; height: 200px;">
                                        <?php else: ?>
                                            <img src="assets/no-image.jpg" class="card-img-top" alt="No Image" style="object-fit: cover; height: 200px;">
                                        <?php endif; ?>
                                        <div class="card-body">
                                            <h5 class="card-title"><?php echo htmlspecialchars($rowResep['NamaResep']); ?></h5>
                                            <p class="card-text"><?php echo htmlspecialchars($rowResep['DeskripsiResep']); ?></p>
                                        </div>
                                    </div>
                                </div>
                            <?php endwhile; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
<?php
// Menutup koneksi database
mysqli_close($conn);
?>
