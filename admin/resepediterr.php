<?php
include "../dbconfig.php";

// Periksa apakah ada ResepID yang diberikan untuk edit
$isEdit = isset($_GET['ResepID']);
if ($isEdit) {
    $resepID = $_GET['ResepID'];

    // Ambil data resep
    $queryResep = "SELECT * FROM resep WHERE ResepID = '$resepID'";
    $resultResep = $conn->query($queryResep);
    $resep = $resultResep->fetch_assoc();

    // Ambil data kategori resep
    $queryKategori = "SELECT * FROM kategoriResep WHERE ResepID = '$resepID'";
    $resultKategori = $conn->query($queryKategori);
    $kategori = $resultKategori->fetch_assoc();
}

// Ambil data pilihan untuk kategori
$queryAsalMasakan = "SELECT * FROM asalMasakan";
$resultAsalMasakan = $conn->query($queryAsalMasakan);

$queryJenisHidangan = "SELECT * FROM jenisHidangan";
$resultJenisHidangan = $conn->query($queryJenisHidangan);

$queryWaktuMemasak = "SELECT * FROM waktuMemasak";
$resultWaktuMemasak = $conn->query($queryWaktuMemasak);

$queryPerkiraanHarga = "SELECT * FROM perkiraanHarga";
$resultPerkiraanHarga = $conn->query($queryPerkiraanHarga);

// Proses form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $namaResep = $conn->real_escape_string($_POST['NamaResep']);
    $deskripsiResep = $conn->real_escape_string($_POST['DeskripsiResep']);
    $linkVideo = $conn->real_escape_string($_POST['LinkVideoTutorial']);

    // Kategori
    $asalMasakanID = $_POST['AsalMasakanID'];
    $jenisHidanganID = $_POST['JenisHidanganID'];
    $waktuMemasakID = $_POST['WaktuMemasakID'];
    $perkiraanHargaID = $_POST['PerkiraanHargaID'];

    if ($isEdit) {
        // Update resep
        $queryUpdateResep = "
            UPDATE resep 
            SET NamaResep = '$namaResep', DeskripsiResep = '$deskripsiResep', LinkVideoTutorial = '$linkVideo'
            WHERE ResepID = '$resepID'
        ";
        $conn->query($queryUpdateResep);

        // Update kategori resep
        $queryUpdateKategori = "
            UPDATE kategoriResep 
            SET AsalMasakanID = '$asalMasakanID', JenisHidanganID = '$jenisHidanganID', 
                WaktuMemasakID = '$waktuMemasakID', PerkiraanHargaID = '$perkiraanHargaID'
            WHERE ResepID = '$resepID'
        ";
        $conn->query($queryUpdateKategori);
    } else {
        // Tambah resep
        $queryInsertResep = "
            INSERT INTO resep (NamaResep, DeskripsiResep, LinkVideoTutorial) 
            VALUES ('$namaResep', '$deskripsiResep', '$linkVideo')
        ";
        $conn->query($queryInsertResep);
        $resepID = $conn->insert_id;

        // Tambah kategori resep
        $queryInsertKategori = "
            INSERT INTO kategoriResep (ResepID, AsalMasakanID, JenisHidanganID, WaktuMemasakID, PerkiraanHargaID)
            VALUES ('$resepID', '$asalMasakanID', '$jenisHidanganID', '$waktuMemasakID', '$perkiraanHargaID')
        ";
        $conn->query($queryInsertKategori);
    }

    header("Location: resep.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $isEdit ? 'Edit Resep' : 'Tambah Resep' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h1><?= $isEdit ? 'Edit Resep' : 'Tambah Resep' ?></h1>
    <form method="POST">
        <!-- Nama Resep -->
        <div class="mb-3">
            <label for="NamaResep" class="form-label">Nama Resep</label>
            <input type="text" class="form-control" id="NamaResep" name="NamaResep" 
                   value="<?= $isEdit ? htmlspecialchars($resep['NamaResep']) : '' ?>" required>
        </div>

        <!-- Deskripsi Resep -->
        <div class="mb-3">
            <label for="DeskripsiResep" class="form-label">Deskripsi Resep</label>
            <textarea class="form-control" id="DeskripsiResep" name="DeskripsiResep" rows="3" required>
                <?= $isEdit ? htmlspecialchars($resep['DeskripsiResep']) : '' ?>
            </textarea>
        </div>

        <!-- Link Video -->
        <div class="mb-3">
            <label for="LinkVideoTutorial" class="form-label">Link Video Tutorial</label>
            <input type="url" class="form-control" id="LinkVideoTutorial" name="LinkVideoTutorial" 
                   value="<?= $isEdit ? htmlspecialchars($resep['LinkVideoTutorial']) : '' ?>">
        </div>

        <!-- Kategori: Asal Masakan -->
        <div class="mb-3">
            <label for="AsalMasakanID" class="form-label">Asal Masakan</label>
            <select class="form-select" id="AsalMasakanID" name="AsalMasakanID" required>
                <option value="">Pilih Asal Masakan</option>
                <?php while ($asalMasakan = $resultAsalMasakan->fetch_assoc()): ?>
                    <option value="<?= $asalMasakan['AsalMasakanID'] ?>" 
                        <?= $isEdit && $kategori['AsalMasakanID'] == $asalMasakan['AsalMasakanID'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($asalMasakan['asalMasakan']) ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>

        <!-- Kategori: Jenis Hidangan -->
        <div class="mb-3">
            <label for="JenisHidanganID" class="form-label">Jenis Hidangan</label>
            <select class="form-select" id="JenisHidanganID" name="JenisHidanganID" required>
                <option value="">Pilih Jenis Hidangan</option>
                <?php while ($jenisHidangan = $resultJenisHidangan->fetch_assoc()): ?>
                    <option value="<?= $jenisHidangan['JenisHidanganID'] ?>" 
                        <?= $isEdit && $kategori['JenisHidanganID'] == $jenisHidangan['JenisHidanganID'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($jenisHidangan['jenisHidangan']) ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>

        <!-- Kategori: Waktu Memasak -->
        <div class="mb-3">
            <label for="WaktuMemasakID" class="form-label">Waktu Memasak</label>
            <select class="form-select" id="WaktuMemasakID" name="WaktuMemasakID" required>
                <option value="">Pilih Waktu Memasak</option>
                <?php while ($waktuMemasak = $resultWaktuMemasak->fetch_assoc()): ?>
                    <option value="<?= $waktuMemasak['WaktuMemasakID'] ?>" 
                        <?= $isEdit && $kategori['WaktuMemasakID'] == $waktuMemasak['WaktuMemasakID'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($waktuMemasak['waktuMemasak']) ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>

        <!-- Kategori: Perkiraan Harga -->
        <div class="mb-3">
            <label for="PerkiraanHargaID" class="form-label">Perkiraan Harga</label>
            <select class="form-select" id="PerkiraanHargaID" name="PerkiraanHargaID" required>
                <option value="">Pilih Perkiraan Harga</option>
                <?php while ($perkiraanHarga = $resultPerkiraanHarga->fetch_assoc()): ?>
                    <option value="<?= $perkiraanHarga['PerkiraanHargaID'] ?>" 
                        <?= $isEdit && $kategori['PerkiraanHargaID'] == $perkiraanHarga['PerkiraanHargaID'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($perkiraanHarga['perkiraanHarga']) ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>

        <!-- Submit -->
        <button type="submit" class="btn btn-success">Simpan</button>
    </form>
</div>
</body>
</html>
