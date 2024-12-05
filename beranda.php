<?php
session_start();
include("dbconfig.php");

// Query untuk mengambil produk dari database
$query = "SELECT produk.ProdukID, produk.NamaProduk, produk.DeskripsiProduk, produk.Harga, fotoproduk.FotoPath 
          FROM produk 
          LEFT JOIN fotoproduk ON produk.ProdukID = fotoproduk.ProdukID 
          GROUP BY produk.ProdukID";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Query Error: " . mysqli_error($conn));
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
        <h1 class="text-center mb-4">Produk Terbaru</h1>
        <div class="row row-cols-1 row-cols-md-3 g-4">
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <div class="col">
                    <div class="card h-100">
                        <!-- Menampilkan foto produk dari database -->
                        <img src="admin/<?php echo $row['FotoPath'] ?: 'uploads/default.jpg'; ?>" class="card-img-top" alt="<?php echo htmlspecialchars($row['NamaProduk']); ?>" style="height: 200px; object-fit: cover;">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($row['NamaProduk']); ?></h5>
                            <p class="card-text"><?php echo substr(htmlspecialchars($row['DeskripsiProduk']), 0, 100); ?>...</p>
                            <p class="text-muted">Rp <?php echo number_format($row['Harga'], 0, ',', '.'); ?></p>
                        </div>
                        <div class="card-footer">
                            <a href="detail_produk.php?id=<?php echo $row['ProdukID']; ?>" class="btn btn-primary w-100">Selengkapnya</a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
    <?php include("template/main_footer.php"); ?>
</body>
</html>
