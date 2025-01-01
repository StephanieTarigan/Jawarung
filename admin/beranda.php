<?php
session_start();

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['role'])) {
    header("Location: login.php");
    exit();
}

// Hubungkan ke database
include "../dbconfig.php";
include "../template/main_layout.php";

// Query untuk menghitung pendapatan total
$queryPendapatanTotal = "SELECT SUM(TotalPesanan) AS total_pendapatan FROM pesanan";
$resultPendapatanTotal = mysqli_query($conn, $queryPendapatanTotal);
$pendapatanTotal = 0;
if ($resultPendapatanTotal) {
    $row = mysqli_fetch_assoc($resultPendapatanTotal);
    $pendapatanTotal = $row['total_pendapatan'] ?? 0;
}

// Query untuk menghitung jumlah pesanan baru
$queryPesananBaru = "SELECT COUNT(*) AS total_pesanan_baru FROM pesanan WHERE StatusPesanan = 'Diproses'";
$resultPesananBaru = mysqli_query($conn, $queryPesananBaru);
$pesananBaru = 0;
if ($resultPesananBaru) {
    $row = mysqli_fetch_assoc($resultPesananBaru);
    $pesananBaru = $row['total_pesanan_baru'] ?? 0;
}

// Query untuk menghitung rata-rata pendapatan
$queryRataPendapatan = "SELECT AVG(TotalPesanan) AS rata_rata_pendapatan FROM pesanan";
$resultRataPendapatan = mysqli_query($conn, $queryRataPendapatan);
$rataRataPendapatan = 0;
if ($resultRataPendapatan) {
    $row = mysqli_fetch_assoc($resultRataPendapatan);
    $rataRataPendapatan = $row['rata_rata_pendapatan'] ?? 0;
}

// Query untuk mengambil produk dari database
$queryProduk = "SELECT produk.ProdukID, produk.NamaProduk, produk.DeskripsiProduk, produk.Harga, fotoproduk.FotoPath 
                FROM produk 
                LEFT JOIN fotoproduk ON produk.ProdukID = fotoproduk.ProdukID 
                GROUP BY produk.ProdukID";
$resultProduk = mysqli_query($conn, $queryProduk);

if (!$resultProduk) {
    die("Query Error Produk: " . mysqli_error($conn));
}

// Query untuk mengambil resep dari database
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
                                    <h2 style="color: #4a7345;">Rp<?= number_format($pendapatanTotal, 0, ',', '.'); ?></h2>
                                    <p><i class="bi bi-arrow-up text-success"></i> Performa bisnis terlihat stabil</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header text-white" style="background-color: #4a7345;">Pesanan Baru</div>
                                <div class="card-body">
                                    <h2 class="text-warning"><?= $pesananBaru; ?></h2>
                                    <p><i class="bi bi-arrow-up text-success"></i> Pesanan terbaru yang sedang diproses</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header text-white" style="background-color: #4a7345;">Rata-rata Pendapatan</div>
                                <div class="card-body">
                                    <h2 style="color: #4a7345;">Rp<?= number_format($rataRataPendapatan, 0, ',', '.'); ?></h2>
                                    <p><i class="bi bi-arrow-up text-success"></i> Kinerja keuangan meningkat</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Produk -->
                    <div class="mt-5">
                        <h2>Semua Produk</h2>
                        <div class="row g-3">
                            <?php while ($rowProduk = mysqli_fetch_assoc($resultProduk)): ?>
                                <div class="col-md-4">
                                    <div class="card h-100">
                                        <img src="<?= !empty($rowProduk['FotoPath']) ? htmlspecialchars($rowProduk['FotoPath']) : 'uploads/default.jpg'; ?>"
                                            class="card-img-top" alt="<?= htmlspecialchars($rowProduk['NamaProduk']); ?>"
                                            style="object-fit: cover; height: 200px;">
                                        <div class="card-body">
                                            <h5 class="card-title"><?= htmlspecialchars($rowProduk['NamaProduk']); ?></h5>
                                            <p class="card-text"><?= htmlspecialchars($rowProduk['DeskripsiProduk']); ?></p>
                                        </div>
                                    </div>
                                </div>
                            <?php endwhile; ?>
                        </div>
                    </div>

                    <!-- Resep -->
                    <div class="mt-5">
                        <h2>Semua Resep</h2>
                        <div class="row g-3">
                            <?php while ($rowResep = mysqli_fetch_assoc($resultResep)): ?>
                                <div class="col-md-4">
                                    <div class="card h-100">
                                        <img src="<?= !empty($rowResep['FotoPath']) ? htmlspecialchars($rowResep['FotoPath']) : 'uploads/no-image.jpg'; ?>"
                                            class="card-img-top" alt="<?= htmlspecialchars($rowResep['NamaResep']); ?>"
                                            style="object-fit: cover; height: 200px;">
                                        <div class="card-body">
                                            <h5 class="card-title"><?= htmlspecialchars($rowResep['NamaResep']); ?></h5>
                                            <p class="card-text"><?= htmlspecialchars($rowResep['DeskripsiResep']); ?></p>
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