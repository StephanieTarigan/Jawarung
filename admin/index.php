<?php
session_start();
include "../dbconfig.php";
include "../template/main_layout.php";

// Mengecek apakah pengguna sudah login dan apakah perannya adalah admin
if (!isset($_SESSION['valid']) || $_SESSION['role'] !== 'admin') {
    header("Location: /login.php");
    exit;
}

// Query untuk mendapatkan data produk
$queryProduk = "
    SELECT produk.ProdukID, produk.NamaProduk, produk.DeskripsiProduk, produk.Harga, 
           fotoproduk.FotoPath, warung.NamaWarung 
    FROM produk 
    LEFT JOIN fotoproduk ON produk.ProdukID = fotoproduk.ProdukID
    LEFT JOIN warung ON produk.WarungID = warung.WarungID
    GROUP BY produk.ProdukID
";
$resultProduk = $conn->query($queryProduk);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Produk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h2 class="mb-4 text-center">Manajemen Produk</h2>
        <div class="mb-3 text-end">
            <a href="add_produk.php" class="btn btn-success">Tambah Produk</a>
        </div>
        <table class="table table-bordered text-center align-middle">
            <thead>
                <tr>
                    <th>Foto</th>
                    <th>Nama Produk</th>
                    <th>Deskripsi</th>
                    <th>Harga</th>
                    <th>Warung</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $resultProduk->fetch_assoc()): ?>
                    <tr>
                        <td>
                            <img src="<?= !empty($row['FotoPath']) ? htmlspecialchars($row['FotoPath']) : 'uploads/default.jpg'; ?>" 
                                 alt="Foto Produk" 
                                 class="img-thumbnail" 
                                 style="width: 100px; height: 100px; object-fit: cover;">
                        </td>
                        <td><?= htmlspecialchars($row['NamaProduk']) ?></td>
                        <td><?= htmlspecialchars($row['DeskripsiProduk']) ?></td>
                        <td><?= "Rp " . number_format($row['Harga'], 0, ',', '.'); ?></td>
                        <td><?= htmlspecialchars($row['NamaWarung']) ?></td>
                        <td>
                            <a href="edit_produk.php?ProdukID=<?= $row['ProdukID'] ?>" class="btn btn-warning btn-sm">Edit</a>
                            <a href="delete_produk.php?ProdukID=<?= $row['ProdukID'] ?>" 
                               class="btn btn-danger btn-sm" 
                               onclick="return confirm('Yakin ingin menghapus produk ini?')">Hapus</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
