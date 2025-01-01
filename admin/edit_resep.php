<?php
session_start();
include "../dbconfig.php";
include "../template/main_layout.php";

// Mengecek apakah pengguna sudah login dan apakah perannya adalah admin
if (!isset($_SESSION['valid']) || $_SESSION['role'] !== 'admin') {
    header("Location: /login.php");
    exit;
}

// Ambil data Resep berdasarkan ResepID
if (!isset($_GET['ResepID'])) {
    header("Location: indexResep.php");
    exit;
}

$resepID = (int)$_GET['ResepID'];

// Ambil data utama resep
$queryResep = "SELECT * FROM resep WHERE ResepID = $resepID";
$resultResep = $conn->query($queryResep);
$resep = $resultResep->fetch_assoc();

if (!$resep) {
    die("Resep tidak ditemukan.");
}

// Ambil data kategori, bahan, langkah, dan foto
$asalMasakanOptions = $conn->query("SELECT * FROM asalMasakan")->fetch_all(MYSQLI_ASSOC);
$jenisHidanganOptions = $conn->query("SELECT * FROM jenisHidangan")->fetch_all(MYSQLI_ASSOC);
$waktuMemasakOptions = $conn->query("SELECT * FROM waktuMemasak")->fetch_all(MYSQLI_ASSOC);
$produkOptions = $conn->query("SELECT ProdukID, NamaProduk, Harga FROM produk")->fetch_all(MYSQLI_ASSOC);
$queryBahan = "
    SELECT rb.ResepBahanID, rb.ProdukID, rb.JumlahBahan, p.NamaProduk, p.Harga 
    FROM resepBahan rb 
    JOIN produk p ON rb.ProdukID = p.ProdukID 
    WHERE rb.ResepID = $resepID";
$resultBahan = $conn->query($queryBahan);
$queryLangkah = "SELECT * FROM langkahMemasak WHERE ResepID = $resepID ORDER BY NomorLangkah ASC";
$resultLangkah = $conn->query($queryLangkah);
$queryFoto = "SELECT * FROM fotoResep WHERE ResepID = $resepID";
$resultFoto = $conn->query($queryFoto);

// Proses Update Resep
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $namaResep = $conn->real_escape_string($_POST['NamaResep']);
    $deskripsiResep = $conn->real_escape_string($_POST['DeskripsiResep']);
    $linkVideo = $conn->real_escape_string($_POST['LinkVideoTutorial']);
    $asalMasakanID = (int)$_POST['AsalMasakanID'];
    $jenisHidanganID = (int)$_POST['JenisHidanganID'];
    $waktuMemasakID = (int)$_POST['WaktuMemasakID'];

    $conn->begin_transaction();
    try {
        // Update data utama resep
        $queryUpdateResep = "
            UPDATE resep 
            SET NamaResep = '$namaResep', DeskripsiResep = '$deskripsiResep', LinkVideoTutorial = '$linkVideo'
            WHERE ResepID = $resepID";
        $conn->query($queryUpdateResep);

        // Update kategori resep
        $queryUpdateKategori = "
            UPDATE kategoriResep 
            SET AsalMasakanID = $asalMasakanID, JenisHidanganID = $jenisHidanganID, WaktuMemasakID = $waktuMemasakID
            WHERE ResepID = $resepID";
        $conn->query($queryUpdateKategori);

        // Update bahan
        foreach ($_POST['bahanID'] as $index => $bahanID) {
            $bahanID = (int)$bahanID;
            $jumlahBahan = (float)$_POST['kuantitas'][$index];

            $queryUpdateBahan = "
                UPDATE resepBahan 
                SET JumlahBahan = $jumlahBahan
                WHERE ResepID = $resepID AND ProdukID = $bahanID";
            $conn->query($queryUpdateBahan);
        }

        // Tambah bahan baru
        if (!empty($_POST['newBahanID'])) {
            foreach ($_POST['newBahanID'] as $index => $newBahanID) {
                $newBahanID = (int)$newBahanID;
                $newKuantitas = (float)$_POST['newKuantitas'][$index];

                $queryTambahBahan = "
                    INSERT INTO resepBahan (ResepID, ProdukID, JumlahBahan) 
                    VALUES ($resepID, $newBahanID, $newKuantitas)";
                $conn->query($queryTambahBahan);
            }
        }

        // Update langkah memasak
        foreach ($_POST['langkahID'] as $index => $langkahID) {
            $deskripsiLangkah = $conn->real_escape_string($_POST['langkahDeskripsi'][$index]);
            $queryUpdateLangkah = "
                UPDATE langkahMemasak 
                SET DeskripsiLangkah = '$deskripsiLangkah'
                WHERE LangkahID = $langkahID";
            $conn->query($queryUpdateLangkah);
        }

        // Tambah langkah baru
        if (!empty($_POST['newLangkahDeskripsi'])) {
            foreach ($_POST['newLangkahDeskripsi'] as $index => $newDeskripsi) {
                $newDeskripsi = $conn->real_escape_string($newDeskripsi);
                $nomorLangkah = $index + 1 + $resultLangkah->num_rows;

                $queryTambahLangkah = "
                    INSERT INTO langkahMemasak (ResepID, NomorLangkah, DeskripsiLangkah) 
                    VALUES ($resepID, $nomorLangkah, '$newDeskripsi')";
                $conn->query($queryTambahLangkah);
            }
        }

        // Tambah foto baru
        if (isset($_FILES['FotoResep']['name']) && !empty($_FILES['FotoResep']['name'][0])) {
            $targetDir = "../uploads/foto_resep/";

            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0777, true);
            }

            foreach ($_FILES['FotoResep']['name'] as $key => $fileName) {
                $fileTmpName = $_FILES['FotoResep']['tmp_name'][$key];
                $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];

                if (in_array($fileExt, $allowedExtensions)) {
                    $newFileName = uniqid() . '.' . $fileExt;
                    $targetFilePath = $targetDir . $newFileName;

                    if (move_uploaded_file($fileTmpName, $targetFilePath)) {
                        $queryInsertFoto = "
                            INSERT INTO fotoResep (ResepID, FotoPath) 
                            VALUES ($resepID, '$newFileName')";
                        $conn->query($queryInsertFoto);
                    }
                }
            }
        }

        $conn->commit();
        header("Location: indexResep.php?successMsg=Resep berhasil diperbarui.");
        exit;
    } catch (Exception $e) {
        $conn->rollback();
        $errMsg = "Gagal memperbarui resep: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Resep</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h1>Edit Resep</h1>
        <form method="POST" enctype="multipart/form-data">
    <!-- Nama Resep -->
    <div class="mb-3">
        <label for="NamaResep" class="form-label">Nama Resep</label>
        <input type="text" class="form-control" id="NamaResep" name="NamaResep" 
               value="<?= htmlspecialchars($resep['NamaResep']) ?>" maxlength="100" required>
    </div>

    <!-- Deskripsi Resep -->
    <div class="mb-3">
        <label for="DeskripsiResep" class="form-label">Deskripsi Resep</label>
        <textarea class="form-control" id="DeskripsiResep" name="DeskripsiResep" rows="3" maxlength="255" required><?= htmlspecialchars($resep['DeskripsiResep']) ?></textarea>
    </div>

    <!-- Link Video -->
    <div class="mb-3">
        <label for="LinkVideoTutorial" class="form-label">Link Video Tutorial</label>
        <input type="url" class="form-control" id="LinkVideoTutorial" name="LinkVideoTutorial" 
               value="<?= htmlspecialchars($resep['LinkVideoTutorial']) ?>">
    </div>

    <!-- Foto Resep -->
    <div class="mb-3">
        <label for="FotoResep" class="form-label">Tambah Foto Baru</label>
        <input type="file" class="form-control" id="FotoResep" name="FotoResep[]" multiple>
        <div class="mt-2">
            <h5>Foto Saat Ini:</h5>
            <div class="row">
                <?php while ($foto = $resultFoto->fetch_assoc()): ?>
                    <div class="col-md-3">
                        <div class="card">
                            <img src="../uploads/foto_resep/<?= htmlspecialchars($foto['FotoPath']) ?>" class="card-img-top" 
                                 alt="Foto Resep" style="height: 150px; object-fit: cover;">
                            <div class="card-body text-center">
                                <a href="deleteFotoResep.php?FotoID=<?= $foto['FotoID'] ?>&ResepID=<?= $resepID ?>" 
                                   class="btn btn-danger btn-sm">Hapus</a>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    </div>

    <!-- Langkah Memasak -->
    <h5>Langkah Memasak</h5>
    <div id="langkah-container">
        <?php while ($langkah = $resultLangkah->fetch_assoc()): ?>
            <div class="mb-3">
                <textarea class="form-control" name="langkahDeskripsi[<?= $langkah['LangkahID'] ?>]" rows="2" required><?= htmlspecialchars($langkah['DeskripsiLangkah']) ?></textarea>
            </div>
        <?php endwhile; ?>
    </div>
    <button type="button" id="add-step" class="btn btn-secondary mb-3">Tambah Langkah</button>

    <!-- Bahan -->
    <h5>Bahan</h5>
    <div id="bahan-container">
        <?php while ($bahan = $resultBahan->fetch_assoc()): ?>
            <div class="mb-3 d-flex align-items-center">
                <select class="form-control me-2 produk-select" name="bahanID[]" required>
                    <option value="">Pilih Bahan</option>
                    <?php foreach ($produkOptions as $produk): ?>
                        <option value="<?= $produk['ProdukID'] ?>" <?= $produk['ProdukID'] == $bahan['ProdukID'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($produk['NamaProduk']) ?> (Rp<?= number_format($produk['Harga'], 0, ',', '.') ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
                <input type="number" class="form-control me-2 jumlah-bahan" name="kuantitas[]" placeholder="Jumlah" min="1" 
                       value="<?= $bahan['JumlahBahan'] ?>" step="0.01" required>
                <button type="button" class="btn btn-danger remove-bahan">Hapus</button>
            </div>
        <?php endwhile; ?>
    </div>
    <button type="button" id="add-bahan" class="btn btn-secondary mb-3">Tambah Bahan</button>

    <!-- Kategori -->
    <div class="mb-3">
        <label for="AsalMasakanID" class="form-label">Asal Masakan</label>
        <select class="form-select" id="AsalMasakanID" name="AsalMasakanID" required>
            <option value="">Pilih Asal Masakan</option>
            <?php foreach ($asalMasakanOptions as $asal): ?>
                <option value="<?= $asal['AsalMasakanID'] ?>" <?= $asal['AsalMasakanID'] == $kategori['AsalMasakanID'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($asal['asalMasakan']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="mb-3">
        <label for="JenisHidanganID" class="form-label">Jenis Hidangan</label>
        <select class="form-select" id="JenisHidanganID" name="JenisHidanganID" required>
            <option value="">Pilih Jenis Hidangan</option>
            <?php foreach ($jenisHidanganOptions as $jenis): ?>
                <option value="<?= $jenis['JenisHidanganID'] ?>" <?= $jenis['JenisHidanganID'] == $kategori['JenisHidanganID'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($jenis['jenisHidangan']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="mb-3">
        <label for="WaktuMemasakID" class="form-label">Waktu Memasak</label>
        <select class="form-select" id="WaktuMemasakID" name="WaktuMemasakID" required>
            <option value="">Pilih Waktu Memasak</option>
            <?php foreach ($waktuMemasakOptions as $waktu): ?>
                <option value="<?= $waktu['WaktuMemasakID'] ?>" <?= $waktu['WaktuMemasakID'] == $kategori['WaktuMemasakID'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($waktu['waktuMemasak']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <h5>Total Harga: Rp<span id="total-harga">0</span></h5>
    <button type="submit" class="btn btn-success">Simpan</button>
</form>

        <!-- Tambahkan bagian form untuk mengedit -->
    </div>
</body>
</html>
