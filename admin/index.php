<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="../style.css">
<?php
session_start();
include "../dbconfig.php";
include "../template/main_layout.php";

// Mengecek apakah pengguna sudah login dan apakah perannya adalah admin
if (!isset($_SESSION['valid']) || $_SESSION['role'] !== 'admin') {
    // Jika tidak login atau bukan admin, arahkan kembali ke login
    header("Location: /login.php");
    exit;
}


?>

<body>
    <h3 style="text-align: center">Data Produk</h3>
    <p><a href="add_produk.php" class="link-underline-primary">Add Data Produk</a></p>
    <table class="table table-bordered" align="center">
        <thead>
            <th>No.</th>
            <th>ProdukID</th>
            <th>Nama Produk</th>
            <th>Deskripsi Produk</th>
            <th>Harga</th>
            <th>WarungID</th>
            <th>Aksi</th>
        </thead>
        <tbody>
            <?php
            // Query untuk mengambil data produk
            $sqlStatement = "SELECT * FROM produk"; // Ganti 'produk' dengan nama tabel produk yang sesuai di database Anda
            $query = mysqli_query($conn, $sqlStatement);

            // Ambil data hasil query
            $data = mysqli_fetch_all($query, MYSQLI_ASSOC);

            // Menampilkan data produk dalam tabel
            foreach ($data as $key => $dtproduk) {
            ?>
                <tr>
                    <td><?php echo ++$key; ?></td>
                    <td><?php echo $dtproduk["ProdukID"]; ?></td>
                    <td><?php echo $dtproduk["NamaProduk"]; ?></td>
                    <td><?php echo $dtproduk["DeskripsiProduk"]; ?></td>
                    <td><?php echo "Rp. " . number_format($dtproduk["Harga"], 0, ',', '.'); ?></td>
                    <td><?php echo $dtproduk["WarungID"]; ?></td>
                    <td>
                        <!-- Tombol untuk edit dan delete produk -->
                        <a href="edit_produk.php?ProdukID=<?php echo $dtproduk['ProdukID']; ?>" class="btn btn-sm btn-primary">Edit</a>
                        <a href="delete_produk.php?ProdukID=<?php echo $dtproduk['ProdukID']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin akan menghapus?')">Delete</a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</body>
</html>
<?php
include "../template/main_footer.php";
?>
