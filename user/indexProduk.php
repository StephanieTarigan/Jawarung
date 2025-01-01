<?php
session_start();
include "../dbconfig.php";
include "../template/main_layout.php";

// Memeriksa apakah pengguna sudah login
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'pelanggan') {
    header("Location: /login.php");
    exit();
}

// Query untuk mendapatkan semua produk
$queryProduk = "SELECT r.ProdukID, r.NamaProduk, r.DeskripsiProduk, f.FotoPath 
               FROM produk r
               LEFT JOIN fotoproduk f ON r.ProdukID = f.ProdukID";
$resultProduk = $conn->query($queryProduk);

if (!$resultProduk) {
    die("Query Error: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Index Produk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
</head>

<body>
    <div class="container mt-4">
        <h2 class="text-center mb-4">Daftar Produk</h2>
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
                                <a href="detail_produk.php?ProdukID=<?= $rowProduk['ProdukID']; ?>" class="btn btn-success btn-sm">Lihat Produk</a>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>

<?php
// Menutup koneksi database
$conn->close();
?>
