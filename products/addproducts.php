<?php
// Menghubungkan ke file konfigurasi database
include "../dbconfig.php";

// Memeriksa apakah tombol simpan diklik
if (isset($_POST['btnSimpan'])) {
    // Mengambil data dari form
    $ProdukID = $_POST["ProdukID"];
    $NamaProduk = $_POST["NamaProduk"];
    $DeskripsiProduk = $_POST["DeskripsiProduk"];
    $Harga = $_POST["Harga"];
    $WarungID = $_POST["WarungID"];

    // Memeriksa apakah NamaProduk valid
    if (!empty($NamaProduk)) {
        // Menyiapkan query SQL untuk menyimpan data produk ke database
        $sqlStatement = "INSERT INTO produk (ProdukID, NamaProduk, DeskripsiProduk, Harga, WarungID)
                         VALUES ('$ProdukID', '$NamaProduk', '$DeskripsiProduk', '$Harga', '$WarungID')";

        $query = mysqli_query($conn, $sqlStatement); // Menjalankan query

        // Memeriksa hasil query
        if ($query) {
            // Jika berhasil, buat pesan sukses dan redirect ke halaman index
            $successMsg = "Produk '" . htmlspecialchars($NamaProduk) . "' berhasil ditambahkan.";
            header("location:index.php?successMsg=" . urlencode($successMsg));
            exit;
        } else {
            // Jika gagal, buat pesan error dengan keterangan
            $errMsg = "Gagal menambahkan produk: " . mysqli_error($conn);
        }
    } else {
        // Jika NamaProduk kosong, buat pesan error
        $errMsg = "Nama produk tidak boleh kosong.";
    }

    // Menutup koneksi database
    mysqli_close($conn);
}

// Memasukkan template header
include "../template/main_header.php";
?>

<!-- Bagian Tampilan HTML untuk Form Tambah Produk -->
<div class="container mt-3">
    <div class="row">
        <div class="col-md-6">
            <h4>Tambah Produk Baru</h4>
        </div>
    </div>

    <!-- Menampilkan pesan error jika ada -->
    <?php if (isset($errMsg)): ?>
        <div class="alert alert-danger" role="alert">
            <?= htmlspecialchars($errMsg) ?>
        </div>
    <?php endif; ?>

    <!-- Form input data produk -->
    <link rel="stylesheet" href="../template/style.css">
    <form method="post">
        <!-- Input ProdukID -->
        <div class="mb-3">
            <label for="ProdukID" class="form-label">ID Produk</label>
            <input type="text" class="form-control" id="ProdukID" name="ProdukID" required placeholder="Masukkan ID Produk">
        </div>

        <!-- Input NamaProduk -->
        <div class="mb-3">
            <label for="NamaProduk" class="form-label">Nama Produk</label>
            <input type="text" class="form-control" id="NamaProduk" name="NamaProduk" required placeholder="Masukkan Nama Produk">
        </div>

        <!-- Input DeskripsiProduk -->
        <div class="mb-3">
            <label for="DeskripsiProduk" class="form-label">Deskripsi Produk</label>
            <textarea class="form-control" id="DeskripsiProduk" name="DeskripsiProduk" rows="3" placeholder="Deskripsi Produk"></textarea>
        </div>

        <!-- Input Harga -->
        <div class="mb-3">
            <label for="Harga" class="form-label">Harga (Rp)</label>
            <input type="number" class="form-control" id="Harga" name="Harga" required placeholder="Masukkan Harga Produk">
        </div>

        <!-- Input WarungID -->
        <div class="mb-3">
            <label for="WarungID" class="form-label">ID Warung</label>
            <input type="text" class="form-control" id="WarungID" name="WarungID" required placeholder="Masukkan ID Warung">
        </div>

        <!-- Tombol Simpan dan Reset -->
        <button type="submit" name="btnSimpan" class="btn btn-success">Simpan</button>
        <button type="reset" class="btn btn-danger">Reset</button>
        <a href="index.php" class="btn btn-secondary">Kembali</a>
    </form>
</div>

<?php
// Memasukkan template footer
include "../template/main_footer.php";
?>
