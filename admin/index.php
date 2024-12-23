<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="../style.css">

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

<body>
    <h3 style="text-align: center">Data Produk</h3>
    <p><a href="add_produk.php" class="link-underline-primary">Tambah Data Produk</a></p>
    <table class="table table-bordered" align="center">
        <thead>
            <th>No.</th>
            <th>Produk ID</th>
            <th>Nama Produk</th>
            <th>Deskripsi Produk</th>
            <th>Harga</th>
            <th>Warung</th>
            <th>Foto Produk</th>
            <th>Aksi</th>
        </thead>
        <tbody>
            <?php
            // Query untuk mengambil data produk dan nama warung
            $sqlStatement = "SELECT p.*, w.NamaWarung FROM produk p JOIN warung w ON p.WarungID = w.WarungID";
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
                    <td><?php echo $dtproduk["NamaWarung"]; ?></td>
                    <td>
                        <?php if (!empty($dtproduk["FotoProduk"])): ?>
                            <img src="uploads/foto_produk/';<?php echo htmlspecialchars($dtproduk["FotoProduk"]); ?>" alt="Foto Produk" style="width: 100px; height: 100px; object-fit: cover;">
                        <?php else: ?>
                            <p>Tidak ada foto</p>
                        <?php endif; ?>
                    </td>
                    <td>
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
// Menutup koneksi database
mysqli_close($conn);
?>
