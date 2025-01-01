<?php
session_start();
include("dbconfig.php");

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

include("template/main_header.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beranda - JA Warung</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container my-4">
        <!-- Bagian Produk -->
        <h1 class="text-center mb-4">Produk Terbaru</h1>
        <div class="row row-cols-1 row-cols-md-3 g-4">
            <?php while ($rowProduk = mysqli_fetch_assoc($resultProduk)): ?>
                <div class="col-md-4">
                    <div class="card h-100">
                        <!-- Menampilkan foto produk dari database -->
                        <img src="admin/<?php echo $rowProduk['FotoPath'] ?: 'uploads/default.jpg'; ?>" 
                             class="card-img-top" alt="<?php echo htmlspecialchars($rowProduk['NamaProduk']); ?>" 
                             style="height: 200px; object-fit: cover;">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($rowProduk['NamaProduk']); ?></h5>
                            <p class="card-text"><?php echo substr(htmlspecialchars($rowProduk['DeskripsiProduk']), 0, 100); ?>...</p>
                            <p class="text-muted">Rp <?php echo number_format($rowProduk['Harga'], 0, ',', '.'); ?></p>
                        </div>
                        <div class="card-footer">
                            <a href="detail_produk.php?id=<?php echo $rowProduk['ProdukID']; ?>" class="btn btn-primary w-100">Selengkapnya</a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>

        <!-- Bagian Resep -->
        <h1 class="text-center my-5">Resep Terbaru</h1>
        <div class="row row-cols-1 row-cols-md-3 g-4">
            <?php while ($rowResep = mysqli_fetch_assoc($resultResep)): ?>
                <div class="col-md-4">
                    <div class="card h-100">
                        <!-- Menampilkan foto resep dari database -->
                        <img src="admin/<?php echo $rowResep['FotoPath'] ?: 'uploads/default_resep.jpg'; ?>" 
                             class="card-img-top" alt="<?php echo htmlspecialchars($rowResep['NamaResep']); ?>" 
                             style="height: 200px; object-fit: cover;">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($rowResep['NamaResep']); ?></h5>
                            <p class="card-text"><?php echo substr(htmlspecialchars($rowResep['DeskripsiResep']), 0, 100); ?>...</p>
                        </div>
                        <div class="card-footer">
                            <a href="detail_resep.php?id=<?php echo $rowResep['ResepID']; ?>" class="btn btn-success w-100">Lihat Resep</a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
    <?php include("template/main_footer.php"); ?>
</body>
</html>
