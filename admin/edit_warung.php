<?php
include "../dbconfig.php";

$id = $_GET['id'];
$sqlFetch = "SELECT * FROM warung WHERE WarungID = $id";
$result = mysqli_query($conn, $sqlFetch);
$data = mysqli_fetch_assoc($result);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btnUpdate'])) {
    $namaWarung = mysqli_real_escape_string($conn, $_POST["NamaWarung"]);
    $alamatWarung = mysqli_real_escape_string($conn, $_POST["AlamatWarung"]);

    $sqlUpdate = "UPDATE warung SET NamaWarung = '$namaWarung', AlamatWarung = '$alamatWarung' WHERE WarungID = $id";
    $query = mysqli_query($conn, $sqlUpdate);

    if ($query) {
        header("Location: index.php?successMsg=Warung berhasil diupdate.");
        exit;
    } else {
        $errMsg = "Gagal mengupdate data warung! " . mysqli_error($conn);
    }
}

mysqli_close($conn);
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
            <input type="text" class="form-control" id="NamaWarung" name="NamaWarung" value="<?= $data['NamaWarung'] ?>" required>
        </div>
        <div class="mb-3">
            <label for="AlamatWarung" class="form-label">Alamat Warung</label>
            <textarea class="form-control" id="AlamatWarung" name="AlamatWarung" required><?= $data['AlamatWarung'] ?></textarea>
        </div>
        <button type="submit" class="btn btn-primary" name="btnUpdate">Update</button>
    </form>
</div>

<?php include "../template/main_footer.php"; 
// Menutup koneksi database
mysqli_close($conn);
?>
