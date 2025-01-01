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

// Memeriksa apakah ID Warung tersedia di URL
if (!isset($_GET['WarungID'])) {
    header("Location: indexWarung.php?errorMsg=ID Warung tidak ditemukan!");
    exit();
}

$warungID = (int)$_GET['WarungID'];

// Query untuk mendapatkan data warung berdasarkan ID
$query = "SELECT * FROM warung WHERE WarungID = $warungID";
$result = $conn->query($query);

if ($result->num_rows === 0) {
    header("Location: indexWarung.php?errorMsg=Data warung tidak ditemukan!");
    exit();
}

$warung = $result->fetch_assoc();

// Memproses data jika tombol simpan ditekan
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btnSimpan'])) {
    // Mengambil data dari form dan melakukan sanitasi
    $namaWarung = mysqli_real_escape_string($conn, $_POST["NamaWarung"]);
    $alamatWarung = mysqli_real_escape_string($conn, $_POST["AlamatWarung"]);

    // Query untuk memperbarui data warung
    $updateQuery = "UPDATE warung 
                    SET NamaWarung = '$namaWarung', AlamatWarung = '$alamatWarung' 
                    WHERE WarungID = $warungID";
    $updateResult = $conn->query($updateQuery);

    if ($updateResult) {
        header("Location: indexWarung.php?successMsg=Warung berhasil diperbarui.");
        exit;
    } else {
        $errMsg = "Gagal memperbarui data warung! " . mysqli_error($conn);
    }
}

// Memasukkan template header
include "../template/main_layout.php";
?>

<div class="container">
    <h4 class="my-4">Edit Warung</h4>
    <?php if (isset($errMsg)): ?>
        <div class="alert alert-danger"><?= $errMsg ?></div>
    <?php endif; ?>
    <form method="post">
        <div class="mb-3">
            <label for="NamaWarung" class="form-label">Nama Warung</label>
            <input type="text" class="form-control" id="NamaWarung" name="NamaWarung" value="<?= htmlspecialchars($warung['NamaWarung']) ?>" required>
        </div>
        <div class="mb-3">
            <label for="AlamatWarung" class="form-label">Alamat Warung</label>
            <textarea class="form-control" id="AlamatWarung" name="AlamatWarung" required><?= htmlspecialchars($warung['AlamatWarung']) ?></textarea>
        </div>
        <button type="submit" class="btn btn-primary" name="btnSimpan">Simpan</button>
        <a href="indexWarung.php" class="btn btn-secondary">Kembali</a>
    </form>
</div>

<?php include "../template/main_footer.php"; ?>
<?php
// Menutup koneksi database
mysqli_close($conn);
?>
