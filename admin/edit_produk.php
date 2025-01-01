<?php
session_start();
include "../dbconfig.php";

// Memeriksa apakah pengguna sudah login dan admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: /login.php");
    exit();
}

// Mendapatkan data produk berdasarkan ProdukID
if (isset($_GET['ProdukID'])) {
    $produkID = (int)$_GET['ProdukID'];
    $sqlProduk = "SELECT * FROM produk WHERE ProdukID = $produkID";
    $resultProduk = $conn->query($sqlProduk);
    if ($resultProduk->num_rows === 0) {
        die("Produk tidak ditemukan.");
    }
    $produk = $resultProduk->fetch_assoc();
} else {
    die("ProdukID tidak ditemukan.");
}

// Mendapatkan data untuk dropdown
$warungResult = $conn->query("SELECT WarungID, NamaWarung FROM warung");
$satuanResult = $conn->query("SELECT SatuanID, NamaSatuan FROM satuan");

// Proses update produk
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $namaProduk = $conn->real_escape_string($_POST["namaProduk"]);
    $deskripsiProduk = $conn->real_escape_string($_POST["deskripsiProduk"]);
    $harga = (float)$_POST["harga"];
    $WarungID = (int)$_POST["WarungID"];
    $SatuanID = (int)$_POST["SatuanID"];
    $Stock = (int)$_POST["Stock"];

    $sqlUpdate = "UPDATE produk SET NamaProduk='$namaProduk', DeskripsiProduk='$deskripsiProduk', Harga=$harga, WarungID=$WarungID, SatuanID=$SatuanID, Stock=$Stock WHERE ProdukID=$produkID";
    if ($conn->query($sqlUpdate)) {
        header("Location: index.php?successMsg=Produk berhasil diperbarui.");
        exit();
    } else {
        $errMsg = "Gagal memperbarui produk: " . $conn->error;
    }
}
include("../template/main_layout.php");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Produk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h4>Edit Produk</h4>
        <?php if (isset($errMsg)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($errMsg) ?></div>
        <?php endif; ?>
        <form method="POST">
            <div class="mb-3">
                <label for="namaProduk" class="form-label">Nama Produk</label>
                <input type="text" class="form-control" id="namaProduk" name="namaProduk" value="<?= htmlspecialchars($produk['NamaProduk']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="deskripsiProduk" class="form-label">Deskripsi Produk</label>
                <textarea class="form-control" id="deskripsiProduk" name="deskripsiProduk" required><?= htmlspecialchars($produk['DeskripsiProduk']) ?></textarea>
            </div>
            <div class="mb-3">
                <label for="harga" class="form-label">Harga</label>
                <input type="number" class="form-control" id="harga" name="harga" value="<?= htmlspecialchars($produk['Harga']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="WarungID" class="form-label">Warung</label>
                <select class="form-select" id="WarungID" name="WarungID" required>
                    <?php while ($warung = $warungResult->fetch_assoc()): ?>
                        <option value="<?= $warung['WarungID'] ?>" <?= $produk['WarungID'] == $warung['WarungID'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($warung['NamaWarung']) ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="SatuanID" class="form-label">Satuan</label>
                <select class="form-select" id="SatuanID" name="SatuanID" required>
                    <?php while ($satuan = $satuanResult->fetch_assoc()): ?>
                        <option value="<?= $satuan['SatuanID'] ?>" <?= $produk['SatuanID'] == $satuan['SatuanID'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($satuan['NamaSatuan']) ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="mb-3">
    <label for="Stock" class="form-label">Stok Produk</label>
    <input type="number" class="form-control" id="Stock" name="Stock" 
           value="<?= htmlspecialchars($produk['Stock'] ?? 0) ?>" 
           min="0" required>
</div>

            <button type="submit" class="btn btn-success">Simpan</button>
            <a href="index.php" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
</body>
</html>
