<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
<?php
// Memulai sesi
session_start();

// Memeriksa apakah pengguna sudah login dan apakah perannya adalah admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: /login.php"); // Arahkan ke halaman login jika bukan admin
    exit();
}

// Menghubungkan ke file konfigurasi database
include "../dbconfig.php";

// Memeriksa apakah tombol simpan diklik
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btnSimpan'])) {
    // Mengambil data dari form dan melakukan sanitasi
    $namaProduk = mysqli_real_escape_string($conn, $_POST["namaProduk"]);
    $deskripsiProduk = mysqli_real_escape_string($conn, $_POST["deskripsiProduk"]);
    $harga = (int)$_POST["harga"];
    $WarungID = (int)$_POST["WarungID"];
    $SatuanID = (int)$_POST["SatuanID"];
    $stock = (int)$_POST["stock"];


    // Validasi input
    if (empty($WarungID) || empty($SatuanID)) {
        $errMsg = "Pilih warung dan satuan yang valid.";
    } else {
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
                $fileSize = $_FILES['fotoProduk']['size'][$i];
                $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];

                if (in_array($fileExt, $allowedExtensions)) {
                    if ($fileSize <= 2 * 1024 * 1024) { // Batas ukuran 2MB
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
                        $errMsg = "Ukuran file terlalu besar. Maksimum 2MB.";
                        break;
                    }
                } else {
                    $errMsg = "Ekstensi file tidak valid. Hanya JPG, JPEG, PNG yang diperbolehkan.";
                    break;
                }
            }
        }

        // Menyimpan produk ke database jika tidak ada error pada upload foto
        // Menyimpan produk ke database jika tidak ada error pada upload foto
if (!isset($errMsg)) {
    // Tambahkan kolom stock dalam daftar kolom INSERT
    $sqlStatement = "INSERT INTO produk (NamaProduk, DeskripsiProduk, Harga, WarungID, SatuanID, stock) 
                     VALUES ('$namaProduk', '$deskripsiProduk', '$harga', '$WarungID', '$SatuanID', '$stock')";
    $query = mysqli_query($conn, $sqlStatement);

    if ($query) {
        $produkID = mysqli_insert_id($conn);

        foreach ($fotoPaths as $fotoPath) {
            $sqlFoto = "INSERT INTO fotoproduk (ProdukID, FotoPath) 
                        VALUES ('$produkID', '$fotoPath')";
            if (!mysqli_query($conn, $sqlFoto)) {
                $errMsg = "Gagal menyimpan foto produk! " . mysqli_error($conn);
                break;
            }
        }

        if (!isset($errMsg)) {
            header("Location: index.php?successMsg=Produk berhasil ditambahkan.");
            exit;
        }
    } else {
        $errMsg = "Gagal menyimpan data produk! " . mysqli_error($conn);
    }
}

    }
}

// Query untuk mendapatkan data warung
$warungQuery = "SELECT WarungID, NamaWarung FROM warung";
$warungResult = mysqli_query($conn, $warungQuery);
if (!$warungResult) {
    die("Query gagal: " . mysqli_error($conn));
}

// Query untuk mendapatkan data satuan
$satuanQuery = "SELECT SatuanID, NamaSatuan FROM satuan";
$satuanResult = mysqli_query($conn, $satuanQuery);
if (!$satuanResult) {
    die("Query gagal: " . mysqli_error($conn));
}

// Memasukkan template header
include("../template/main_layout.php");
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
            <label for="SatuanID" class="form-label">Satuan</label>
            <select class="form-control" id="SatuanID" name="SatuanID" required>
                <option value="">Pilih Satuan</option>
                <?php while ($satuan = mysqli_fetch_assoc($satuanResult)): ?>
                    <option value="<?= htmlspecialchars($satuan['SatuanID']) ?>">
                        <?= htmlspecialchars($satuan['NamaSatuan']) ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="stock" class="form-label">Stok Produk</label>
            <input type="number" class="form-control" id="stock" name="stock" value="100" min="0" required>
        </div>
        <div class="mb-3">
            <label for="fotoProduk" class="form-label">Foto Produk</label>
            <input type="file" class="form-control" id="fotoProduk" name="fotoProduk[]" multiple required>
        </div>
        <button type="submit" class="btn btn-success" name="btnSimpan">Simpan</button>
    </form>
</div>


<?php include "../template/main_footer.php"; ?>
<?php
// Menutup koneksi database
mysqli_close($conn);
?>
