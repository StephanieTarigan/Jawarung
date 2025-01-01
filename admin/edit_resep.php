<?php
session_start();
include "../dbconfig.php";
include "../template/main_layout.php";
// Mengecek apakah pengguna sudah login dan apakah perannya adalah admin
if (!isset($_SESSION['valid']) || $_SESSION['role'] !== 'admin') {
    header("Location: /login.php");
    exit;
}

// Periksa apakah ada ResepID yang diberikan
if (!isset($_GET['ResepID'])) {
    header("Location: indexResep.php");
    exit();
}

$resepID = $_GET['ResepID'];

// Ambil data resep utama
$queryResep = "SELECT * FROM resep WHERE ResepID = '$resepID'";
$resultResep = $conn->query($queryResep);
$resep = $resultResep->fetch_assoc();

// Ambil kategori resep
$queryKategori = "SELECT * FROM kategoriResep WHERE ResepID = '$resepID'";
$resultKategori = $conn->query($queryKategori);
$kategori = $resultKategori->fetch_assoc();

// Ambil langkah memasak
$queryLangkah = "SELECT * FROM langkahMemasak WHERE ResepID = '$resepID' ORDER BY NomorLangkah ASC";
$resultLangkah = $conn->query($queryLangkah);

// Ambil bahan dan kuantitas
$queryBahan = "
    SELECT rb.ResepBahanID, b.NamaBahanBaku, rb.JumlahBahan, rb.SatuanID, b.Harga, rb.BahanBakuID 
    FROM resepBahan rb
    JOIN bahanBaku b ON rb.BahanBakuID = b.BahanBakuID
    WHERE rb.ResepID = '$resepID'
";
$resultBahan = $conn->query($queryBahan);

// Ambil daftar satuan
$querySatuan = "SELECT * FROM satuan";
$resultSatuan = $conn->query($querySatuan);
$satuanOptions = $resultSatuan->fetch_all(MYSQLI_ASSOC);

// Ambil foto resep
$queryFotoResep = "SELECT * FROM fotoResep WHERE ResepID = '$resepID'";
$resultFotoResep = $conn->query($queryFotoResep);

// Ambil pilihan kategori
$queryAsalMasakan = "SELECT * FROM asalMasakan";
$resultAsalMasakan = $conn->query($queryAsalMasakan);

$queryJenisHidangan = "SELECT * FROM jenisHidangan";
$resultJenisHidangan = $conn->query($queryJenisHidangan);

$queryWaktuMemasak = "SELECT * FROM waktuMemasak";
$resultWaktuMemasak = $conn->query($queryWaktuMemasak);

// Proses Update Resep
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $namaResep = $conn->real_escape_string($_POST['NamaResep']);
    $deskripsiResep = $conn->real_escape_string($_POST['DeskripsiResep']);
    $linkVideo = $conn->real_escape_string($_POST['LinkVideoTutorial']);
    $asalMasakanID = $_POST['AsalMasakanID'];
    $jenisHidanganID = $_POST['JenisHidanganID'];
    $waktuMemasakID = $_POST['WaktuMemasakID'];

    // Update data utama resep
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
            WaktuMemasakID = '$waktuMemasakID'
        WHERE ResepID = '$resepID'
    ";
    $conn->query($queryUpdateKategori);

    

    // Update langkah memasak
    if (isset($_POST['langkah'])) {
        foreach ($_POST['langkah'] as $langkahID => $langkahDeskripsi) {
            $langkahDeskripsi = $conn->real_escape_string($langkahDeskripsi);

            $queryUpdateLangkah = "
                UPDATE langkahMemasak 
                SET DeskripsiLangkah = '$langkahDeskripsi'
                WHERE LangkahID = '$langkahID'
            ";
            $conn->query($queryUpdateLangkah);
        }
    }

    // Tambah langkah baru
    if (!empty($_POST['newLangkah'])) {
        $maxNomorLangkah = $conn->query("SELECT MAX(NomorLangkah) AS MaxLangkah FROM langkahMemasak WHERE ResepID = '$resepID'")->fetch_assoc()['MaxLangkah'] ?? 0;
        foreach ($_POST['newLangkah'] as $langkahBaru) {
            $maxNomorLangkah++;
            $langkahBaru = $conn->real_escape_string($langkahBaru);

            $queryTambahLangkah = "
                INSERT INTO langkahMemasak (ResepID, NomorLangkah, DeskripsiLangkah) 
                VALUES ('$resepID', '$maxNomorLangkah', '$langkahBaru')
            ";
            $conn->query($queryTambahLangkah);
        }
    }

    // Update bahan
    if (isset($_POST['BahanBakuID'])) {
        foreach ($_POST['BahanBakuID'] as $BahanBakuID => $bahanNama) {
            $kuantitas = $_POST['kuantitas'][$BahanBakuID];
            $harga = $_POST['harga'][$BahanBakuID];
            $satuanID = $_POST['satuan'][$BahanBakuID];

            $queryUpdateBahan = "
                UPDATE bahanBaku 
                SET NamaBahanBaku = '$bahanNama', Harga = '$harga'
                WHERE BahanBakuID = '$BahanBakuID'
            ";
            $conn->query($queryUpdateBahan);

            $queryUpdateResepBahan = "
                UPDATE resepBahan 
                SET JumlahBahan = '$kuantitas', SatuanID = '$satuanID'
                WHERE ResepID = '$resepID' AND BahanBakuID = '$BahanBakuID'
            ";
            $conn->query($queryUpdateResepBahan);
        }
    }

    // Tambah bahan baru
    if (!empty($_POST['newBahan'])) {
        foreach ($_POST['newBahan'] as $index => $bahanBaru) {
            $kuantitasBaru = $_POST['newKuantitas'][$index];
            $hargaBaru = $_POST['newHarga'][$index];
            $satuanIDBaru = $_POST['newSatuan'][$index];

            $queryTambahBahan = "
                INSERT INTO bahanBaku (NamaBahanBaku, Harga) 
                VALUES ('$bahanBaru', '$hargaBaru')
            ";
            $conn->query($queryTambahBahan);
            $bahanBaruID = $conn->insert_id;

            $queryTambahResepBahan = "
                INSERT INTO resepBahan (ResepID, BahanBakuID, JumlahBahan, SatuanID) 
                VALUES ('$resepID', '$bahanBaruID', '$kuantitasBaru', '$satuanIDBaru')
            ";
            $conn->query($queryTambahResepBahan);
        }
    }

    // Proses foto resep
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
                        VALUES ('$resepID', '$newFileName')
                    ";
                    $conn->query($queryInsertFoto);
                }
            }
        }
    }

    header("Location: indexResep.php");
    exit();
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
        <form method="POST">
            <!-- Nama Resep -->
            <div class="mb-3">
                <label for="NamaResep" class="form-label">Nama Resep</label>
                <input type="text" class="form-control" id="NamaResep" name="NamaResep" value="<?= htmlspecialchars($resep['NamaResep']) ?>" required>
            </div>

            <!-- Deskripsi Resep -->
            <div class="mb-3">
                <label for="DeskripsiResep" class="form-label">Deskripsi Resep</label>
                <textarea class="form-control" id="DeskripsiResep" name="DeskripsiResep" rows="3" required><?= htmlspecialchars($resep['DeskripsiResep']) ?></textarea>
            </div>

            <!-- Link Video -->
            <div class="mb-3">
                <label for="LinkVideoTutorial" class="form-label">Link Video Tutorial</label>
                <input type="url" class="form-control" id="LinkVideoTutorial" name="LinkVideoTutorial" value="<?= htmlspecialchars($resep['LinkVideoTutorial']) ?>">
            </div>

            <!-- Foto Resep -->
            <h5>Foto Resep</h5>
            <div class="mb-3">
                <label for="FotoResep" class="form-label">Tambah Foto</label>
                <input type="file" class="form-control" id="FotoResep" name="FotoResep[]" multiple>
            </div>
            <div class="row g-3">
                <?php while ($foto = $resultFotoResep->fetch_assoc()): ?>
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

            <!-- Langkah Memasak -->
            <h5>Langkah Memasak</h5>
            <div id="langkah-container">
                <?php while ($langkah = $resultLangkah->fetch_assoc()): ?>
                    <div class="mb-3">
                        <textarea class="form-control" name="langkah[<?= $langkah['LangkahID'] ?>]" rows="2"><?= htmlspecialchars($langkah['DeskripsiLangkah']) ?></textarea>
                    </div>
                <?php endwhile; ?>
            </div>
            <button type="button" id="add-step" class="btn btn-secondary mb-3">Tambah Langkah</button>

            <!-- Bahan -->
            <h5>Bahan</h5>
            <div id="bahan-container">
                <?php while ($bahan = $resultBahan->fetch_assoc()): ?>
                    <div class="mb-3 d-flex">
                        <input type="hidden" name="BahanBakuID[<?= $bahan['BahanBakuID'] ?>]" value="<?= $bahan['BahanBakuID'] ?>">
                        <input type="text" class="form-control me-2" name="bahanNama[<?= $bahan['BahanBakuID'] ?>]" value="<?= htmlspecialchars($bahan['NamaBahanBaku']) ?>" placeholder="Nama Bahan" required>
                        <input type="number" class="form-control me-2" name="kuantitas[<?= $bahan['BahanBakuID'] ?>]" value="<?= $bahan['JumlahBahan'] ?>" placeholder="Jumlah" required>
                        <select class="form-control me-2" name="satuan[<?= $bahan['BahanBakuID'] ?>]">
                            <?php foreach ($satuanOptions as $satuan): ?>
                                <option value="<?= $satuan['SatuanID'] ?>" <?= $satuan['SatuanID'] == $bahan['SatuanID'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($satuan['NamaSatuan']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <input type="number" class="form-control" name="harga[<?= $bahan['BahanBakuID'] ?>]" value="<?= $bahan['Harga'] ?>" placeholder="Harga" required>
                    </div>
                <?php endwhile; ?>
            </div>
            <button type="button" id="add-bahan" class="btn btn-secondary mb-3">Tambah Bahan</button>

            <!-- Asal Masakan -->
            <div class="mb-3">
                <label for="AsalMasakanID" class="form-label">Asal Masakan</label>
                <select class="form-select" id="AsalMasakanID" name="AsalMasakanID" required>
                    <?php while ($asal = $resultAsalMasakan->fetch_assoc()): ?>
                        <option value="<?= $asal['AsalMasakanID'] ?>" <?= $kategori['AsalMasakanID'] == $asal['AsalMasakanID'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($asal['asalMasakan']) ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <!-- Jenis Hidangan -->
            <div class="mb-3">
                <label for="JenisHidanganID" class="form-label">Jenis Hidangan</label>
                <select class="form-select" id="JenisHidanganID" name="JenisHidanganID" required>
                    <?php while ($jenis = $resultJenisHidangan->fetch_assoc()): ?>
                        <option value="<?= $jenis['JenisHidanganID'] ?>" <?= $kategori['JenisHidanganID'] == $jenis['JenisHidanganID'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($jenis['jenisHidangan']) ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <!-- Waktu Memasak -->
            <div class="mb-3">
                <label for="WaktuMemasakID" class="form-label">Waktu Memasak</label>
                <select class="form-select" id="WaktuMemasakID" name="WaktuMemasakID" required>
                    <?php while ($waktu = $resultWaktuMemasak->fetch_assoc()): ?>
                        <option value="<?= $waktu['WaktuMemasakID'] ?>" <?= $kategori['WaktuMemasakID'] == $waktu['WaktuMemasakID'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($waktu['waktuMemasak']) ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>


            <!-- Submit -->
            <button type="submit" class="btn btn-success">Simpan</button>
        </form>
    </div>

    <script>
        // Tambah Langkah
        document.getElementById('add-step').addEventListener('click', () => {
            const langkahContainer = document.getElementById('langkah-container');
            const newLangkah = `
            <div class="mb-3">
                <textarea class="form-control" name="newLangkah[]" rows="2" placeholder="Langkah Memasak"></textarea>
            </div>`;
            langkahContainer.insertAdjacentHTML('beforeend', newLangkah);
        });

        // Tambah Bahan
        document.getElementById('add-bahan').addEventListener('click', () => {
            const bahanContainer = document.getElementById('bahan-container');
            const newBahan = `
            <div class="mb-3 d-flex">
                <input type="text" class="form-control me-2" name="newBahan[]" placeholder="Nama Bahan" required>
                <input type="number" class="form-control me-2" name="newKuantitas[]" placeholder="Jumlah" required>
                <select class="form-control me-2" name="newSatuan[]">
                    <?php foreach ($satuanOptions as $satuan): ?>
                        <option value="<?= $satuan['SatuanID'] ?>"><?= htmlspecialchars($satuan['NamaSatuan']) ?></option>
                    <?php endforeach; ?>
                </select>
                <input type="number" class="form-control" name="newHarga[]" placeholder="Harga" required>
            </div>`;
            bahanContainer.insertAdjacentHTML('beforeend', newBahan);
        });
    </script>
</body>

</html>