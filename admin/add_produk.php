<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="../style.css">

<?php
// Menghubungkan ke file konfigurasi database
include "../dbconfig.php";

// Memeriksa apakah tombol simpan diklik
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btnSimpan'])) {
    // Mengambil data dari form dan melakukan sanitasi
    $namaProduk = mysqli_real_escape_string($conn, $_POST["namaProduk"]);
    $deskripsiProduk = mysqli_real_escape_string($conn, $_POST["deskripsiProduk"]);
    $harga = (int)$_POST["harga"];
    $WarungID = (int)$_POST["WarungID"];

    // Validasi jika ada foto yang diupload
    $fotoPaths = [];
    if (isset($_FILES['fotoProduk']['name']) && !empty($_FILES['fotoProduk']['name'][0])) {
        $totalFiles = count($_FILES['fotoProduk']['name']);
        $uploadDir = 'uploads/foto_produk/';
        
        // Pastikan folder uploads ada
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        for ($i = 0; $i < $totalFiles; $i++) {
            $fileName = $_FILES['fotoProduk']['name'][$i];
            $fileTmpName = $_FILES['fotoProduk']['tmp_name'][$i];
            $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];

            if (in_array($fileExt, $allowedExtensions)) {
                // Generate nama file unik
                $newFileName = uniqid() . '.' . $fileExt;
                $uploadFile = $uploadDir . $newFileName;

                if (move_uploaded_file($fileTmpName, $uploadFile)) {
                    $fotoPaths[] = $uploadFile;
                } else {
                    $errMsg = "Gagal mengupload foto produk.";
                    break;
                }
            } else {
                $errMsg = "Ekstensi file tidak valid. Hanya JPG, JPEG, PNG yang diperbolehkan.";
                break;
            }
        }
    }

    // Menyimpan produk ke database jika tidak ada error pada upload foto
    if (!isset($errMsg)) {
        $sqlStatement = "INSERT INTO produk (NamaProduk, DeskripsiProduk, Harga, WarungID) 
                         VALUES ('$namaProduk', '$deskripsiProduk', '$harga', '$WarungID')";
        $query = mysqli_query($conn, $sqlStatement);

        if ($query) {
            $produkID = mysqli_insert_id($conn);

            foreach ($fotoPaths as $fotoPath) {
                $sqlFoto = "INSERT INTO fotoproduk (ProdukID, FotoPath) 
                            VALUES ('$produkID', '$fotoPath')";
                mysqli_query($conn, $sqlFoto);
            }

            header("Location: index.php?successMsg=Produk berhasil ditambahkan.");
            exit;
        } else {
            $errMsg = "Gagal menyimpan data produk! " . mysqli_error($conn);
        }
    }
}

$warungQuery = "SELECT WarungID, NamaWarung FROM warung";
$warungResult = mysqli_query($conn, $warungQuery);
if (!$warungResult) {
    die("Query gagal: " . mysqli_error($conn));
}

// Menutup koneksi database
mysqli_close($conn);

// Memasukkan template header
include "../template/main_layout.php";
?>

<!-- Bagian tampilan HTML untuk form -->
<div class="container">
    <h4 class="my-4">Tambah Produk Baru</h4>
    <?php if (isset($errMsg)): ?>
        <div class="alert alert-danger"><?= $errMsg ?></div>
    <?php endif; ?>
    <form method="post" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="namaProduk" class="form-label">Nama Produk</label>
            <input type="text" class="form-control" id="namaProduk" name="namaProduk" required>
        </div>
        <div class="mb-3">
            <label for="deskripsiProduk" class="form-label">Deskripsi Produk</label>
            <textarea class="form-control" id="deskripsiProduk" name="deskripsiProduk" required></textarea>
        </div>
        <div class="mb-3">
            <label for="harga" class="form-label">Harga</label>
            <input type="number" class="form-control" id="harga" name="harga" required>
        </div>
        <div class="mb-3">
            <label for="WarungID" class="form-label">ID Warung</label>
            <select class="form-control" id="WarungID" name="WarungID" required>
                <option value="">Pilih Warung</option>
                <?php while ($warung = mysqli_fetch_assoc($warungResult)): ?>
                    <option value="<?= htmlspecialchars($warung['WarungID']) ?>">
                        <?= htmlspecialchars($warung['WarungID']) . ' - ' . htmlspecialchars($warung['NamaWarung']) ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="fotoProduk" class="form-label">Foto Produk</label>
            <input type="file" class="form-control" id="fotoProduk" name="fotoProduk[]" multiple required>
        </div>
        <button type="submit" class="btn btn-success" name="btnSimpan">Simpan</button>
    </form>
</div>

<?php include "../template/main_footer.php"; ?>