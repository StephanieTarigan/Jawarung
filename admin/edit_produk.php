<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="../style.css">

<?php
// Memulai sesi
session_start();

// Memeriksa apakah pengguna sudah login dan apakah perannya adalah admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: /login.php"); // Arahkan ke halaman login jika bukan admin
    exit();
}
include "../dbconfig.php"; // Pastikan koneksi database sudah benar

// Jika tombol Simpan ditekan
if (isset($_POST['btnSimpan'])) {
    $produkID = $_POST["ProdukID"];
    $namaProduk = $_POST["NamaProduk"];
    $deskripsiProduk = $_POST["DeskripsiProduk"];
    $harga = $_POST["Harga"];
    $warungID = $_POST["WarungID"];

    // Query untuk memperbarui data produk
    $sqlStatement = "UPDATE produk SET NamaProduk='$namaProduk', DeskripsiProduk='$deskripsiProduk', Harga='$harga', WarungID='$warungID' WHERE ProdukID='$produkID'";
    $query = mysqli_query($conn, $sqlStatement);

    // Jika query berhasil
    if ($query) {
        $succesMsg = "Pengubahan data produk dengan ID " . $produkID . " berhasil";
        header("location:index.php?successMsg=$succesMsg"); // Arahkan ke halaman index.php dengan pesan sukses
    } else {
        $errMsg = "Pengubahan data produk dengan ID " . $produkID . " GAGAL !" . mysqli_error($conn); // Menampilkan pesan error
    }
}

/** Cari produk */
$produkID = $_GET['ProdukID'];
$sqlStatement = "SELECT * FROM produk WHERE ProdukID='$produkID'";
$query = mysqli_query($conn, $sqlStatement);
$row = mysqli_fetch_assoc($query);

// Ambil data warung untuk dropdown
$sqlStatement = "SELECT * FROM warung";
$query = mysqli_query($conn, $sqlStatement);
$dtwarung = mysqli_fetch_all($query, MYSQLI_ASSOC);

include "../template/main_layout.php";
?>
<div class="row mt-3 mb-4">
    <div class="col-md-6">
        <h4>Update Produk Data</h4>
    </div>
</div>

<?php
// Jika ada error, tampilkan pesan error
if (isset($errMsg)) {
?>
    <div class="alert alert-danger" role="alert">
        <?= $errMsg ?>
    </div>
<?php
}
?>

<form method="post">
    <!-- ProdukID -->
    <div class="mb-1 row">
        <div class="col-2">
            <label for="ProdukID" class="col-form-label">Produk ID</label>
        </div>
        <div class="col-auto">
            <input type="text" class="form-control" id="ProdukID" name="ProdukID" size="10" disabled value="<?= $row["ProdukID"] ?>" required>
        </div>
    </div>

    <!-- Nama Produk -->
    <div class="mb-1 row">
        <div class="col-2">
            <label for="NamaProduk" class="col-form-label">Nama Produk</label>
        </div>
        <div class="col-auto">
            <input type="text" class="form-control" id="NamaProduk" name="NamaProduk" value="<?= $row["NamaProduk"] ?>" required>
        </div>
    </div>

    <!-- Deskripsi Produk -->
    <div class="mb-1 row">
        <div class="col-2">
            <label for="DeskripsiProduk" class="col-form-label">Deskripsi Produk</label>
        </div>
        <div class="col-auto">
            <textarea class="form-control" id="DeskripsiProduk" name="DeskripsiProduk"><?= $row["DeskripsiProduk"] ?></textarea>
        </div>
    </div>

    <!-- Harga -->
    <div class="mb-1 row">
        <div class="col-2">
            <label for="Harga" class="col-form-label">Harga</label>
        </div>
        <div class="col-auto">
            <input type="number" class="form-control" id="Harga" name="Harga" value="<?= $row["Harga"] ?>" required>
        </div>
    </div>

    <!-- Warung ID -->
    <div class="mb-1 row">
        <div class="col-2">
            <label for="WarungID" class="col-form-label">Warung</label>
        </div>
        <div class="col-auto">
            <select class="form-select" aria-label="Warung Select" name="WarungID">
                <option selected><?= $row["WarungID"] ?></option>
                <?php
                foreach ($dtwarung as $key => $warung) {
                ?>
                    <option value="<?= $warung["WarungID"] ?>"><?= $warung["NamaWarung"] ?></option>
                <?php } ?>
            </select>
        </div>
    </div>

    <!-- Tombol Simpan dan Reset -->
    <div class="mt-4 row">
        <div class="col-auto">
            <input type="hidden" name="ProdukID" value="<?= $row["ProdukID"] ?>">
            <input type="submit" class="btn btn-success" name="btnSimpan" value="Simpan">
            <input type="reset" class="btn btn-danger" value="Ulangi">
            <a href="<?= HOST . "/produk/" ?>" class="btn btn-secondary">Kembali</a>
        </div>
    </div>
</form>

<?php
include "../template/main_footer.php"; // Menutup footer template
// Menutup koneksi database
mysqli_close($conn);
?>

