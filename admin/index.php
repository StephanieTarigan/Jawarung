<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Produk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        h3 {
            color: #4a7345;
            margin-bottom: 20px;
        }
        .btn-success {
            background-color: #4a7345;
            border-color: #4a7345;
        }
        .btn-success:hover {
            background-color: #3a5c36;
            border-color: #3a5c36;
        }
        .table-wrapper {
            overflow-x: auto;
        }
        .table thead {
            background-color: #4a7345;
            color: white;
        }
        .table img {
            max-width: 100px;
            max-height: 100px;
            object-fit: cover;
        }
    </style>
</head>
<body>
    <?php
    session_start();
    include "../dbconfig.php";
    include "../template/main_layout.php";

    // Mengecek apakah pengguna sudah login dan apakah perannya adalah admin
    if (!isset($_SESSION['valid']) || $_SESSION['role'] !== 'admin') {
        header("Location: /login.php");
        exit;
    }
    ?>

    <div class="container py-4">
        <div class="row">
            <div class="col">
                <h3 class="text-center">Data Produk</h3>
                <div class="text-end mb-3">
                    <a href="add_produk.php" class="btn btn-success">Tambah Data Produk</a>
                </div>
                <div class="table-wrapper">
                    <table class="table table-bordered table-hover align-middle text-center">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Produk ID</th>
                                <th>Nama Produk</th>
                                <th>Deskripsi Produk</th>
                                <th>Harga</th>
                                <th>Warung</th>
                                <th>Foto Produk</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Query untuk mengambil produk dari database
                            $queryProduk = "
                                SELECT produk.ProdukID, produk.NamaProduk, produk.DeskripsiProduk, produk.Harga, 
                                       fotoproduk.FotoPath, warung.NamaWarung 
                                FROM produk 
                                LEFT JOIN fotoproduk ON produk.ProdukID = fotoproduk.ProdukID
                                LEFT JOIN warung ON produk.WarungID = warung.WarungID
                                GROUP BY produk.ProdukID
                            ";

                            $resultProduk = mysqli_query($conn, $queryProduk);

                            // Menampilkan data produk dalam tabel
                            if ($resultProduk && mysqli_num_rows($resultProduk) > 0) {
                                $no = 1;
                                while ($dtproduk = mysqli_fetch_assoc($resultProduk)) {
                            ?>
                                    <tr>
                                        <td><?php echo $no++; ?></td>
                                        <td><?php echo htmlspecialchars($dtproduk["ProdukID"]); ?></td>
                                        <td><?php echo htmlspecialchars($dtproduk["NamaProduk"]); ?></td>
                                        <td><?php echo htmlspecialchars(substr($dtproduk["DeskripsiProduk"], 0, 50)) . '...'; ?></td>
                                        <td><?php echo "Rp. " . number_format($dtproduk["Harga"], 0, ',', '.'); ?></td>
                                        <td><?php echo htmlspecialchars($dtproduk["NamaWarung"]); ?></td>
                                        <td>
                                            <img src="<?php echo !empty($dtproduk['FotoPath']) 
                                                ? htmlspecialchars($dtproduk['FotoPath']) 
                                                : 'uploads/default.jpg'; ?>" 
                                                alt="Foto Produk" class="img-thumbnail">
                                        </td>
                                        <td>
                                            <a href="edit_produk.php?ProdukID=<?php echo $dtproduk['ProdukID']; ?>" 
                                               class="btn btn-sm btn-primary">Edit</a>
                                            <a href="delete_produk.php?ProdukID=<?php echo $dtproduk['ProdukID']; ?>" 
                                               class="btn btn-sm btn-danger" onclick="return confirm('Yakin akan menghapus?')">Delete</a>
                                        </td>
                                    </tr>
                            <?php
                                }
                            } else {
                                echo '<tr><td colspan="8" class="text-center">Tidak ada data produk.</td></tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <?php
    include "../template/main_footer.php";
    mysqli_close($conn);
    ?>
</body>
</html>
