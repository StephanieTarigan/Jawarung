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
// Menghubungkan ke file konfigurasi database
include "../dbconfig.php";

// Memeriksa apakah tombol simpan diklik
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btnSimpan'])) {
    // Mengambil data dari form dan melakukan sanitasi
    $namaWarung = mysqli_real_escape_string($conn, $_POST["NamaWarung"]);
    $alamatWarung = mysqli_real_escape_string($conn, $_POST["AlamatWarung"]);

    // Menyimpan data warung ke database
    $sqlStatement = "INSERT INTO warung (NamaWarung, AlamatWarung) 
                     VALUES ('$namaWarung', '$alamatWarung')";
    $query = mysqli_query($conn, $sqlStatement);

    if ($query) {
        header("Location: indexWarung.php?successMsg=Warung berhasil ditambahkan.");
        exit;
    } else {
        $errMsg = "Gagal menyimpan data warung! " . mysqli_error($conn);
    }
}



// Memasukkan template header
include "../template/main_layout.php";
?>

<!-- Bagian tampilan HTML untuk form -->
<div class="container">
    <h4 class="my-4">Tambah Warung Baru</h4>
    <?php if (isset($errMsg)): ?>
        <div class="alert alert-danger"><?= $errMsg ?></div>
    <?php endif; ?>
    <form method="post">
        <div class="mb-3">
            <label for="NamaWarung" class="form-label">Nama Warung</label>
            <input type="text" class="form-control" id="NamaWarung" name="NamaWarung" required>
        </div>
        <div class="mb-3">
            <label for="AlamatWarung" class="form-label">Alamat Warung</label>
            <textarea class="form-control" id="AlamatWarung" name="AlamatWarung" required></textarea>
        </div>
        <button type="submit" class="btn btn-success" name="btnSimpan">Simpan</button>
    </form>
</div>

<?php include "../template/main_footer.php"; ?>
<?php
// Menutup koneksi database
mysqli_close($conn);
?>