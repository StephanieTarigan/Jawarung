
<?php
session_start();
include "../dbconfig.php";
include "../template/main_layout.php";

// Mengecek apakah pengguna sudah login dan apakah perannya adalah admin
if (!isset($_SESSION['valid']) || $_SESSION['role'] !== 'admin') {
    header("Location: /login.php");
    exit;
}

// Ambil daftar satuan
$querySatuan = "SELECT * FROM satuan";
$resultSatuan = $conn->query($querySatuan);
$satuanOptions = $resultSatuan->fetch_all(MYSQLI_ASSOC);

// Ambil pilihan kategori
$queryAsalMasakan = "SELECT * FROM asalMasakan";
$resultAsalMasakan = $conn->query($queryAsalMasakan);

$queryJenisHidangan = "SELECT * FROM jenisHidangan";
$resultJenisHidangan = $conn->query($queryJenisHidangan);

$queryWaktuMemasak = "SELECT * FROM waktuMemasak";
$resultWaktuMemasak = $conn->query($queryWaktuMemasak);

// Proses Simpan Resep Baru
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $namaResep = $conn->real_escape_string($_POST['NamaResep']);
    $deskripsiResep = $conn->real_escape_string($_POST['DeskripsiResep']);
    $linkVideo = $conn->real_escape_string($_POST['LinkVideoTutorial']);
    $asalMasakanID = $_POST['AsalMasakanID'];
    $jenisHidanganID = $_POST['JenisHidanganID'];
    $waktuMemasakID = $_POST['WaktuMemasakID'];

    // Simpan data utama resep
    $queryInsertResep = "
        INSERT INTO resep (NamaResep, DeskripsiResep, LinkVideoTutorial) 
        VALUES ('$namaResep', '$deskripsiResep', '$linkVideo')
    ";
    if ($conn->query($queryInsertResep)) {
        $resepID = $conn->insert_id;

        // Simpan kategori resep
        $queryInsertKategori = "
            INSERT INTO kategoriResep (ResepID, AsalMasakanID, JenisHidanganID, WaktuMemasakID) 
            VALUES ('$resepID', '$asalMasakanID', '$jenisHidanganID', '$waktuMemasakID')
        ";
        $conn->query($queryInsertKategori);

        // Simpan langkah memasak
        if (!empty($_POST['langkah'])) {
            $nomorLangkah = 0;
            foreach ($_POST['langkah'] as $langkahDeskripsi) {
                $nomorLangkah++;
                $langkahDeskripsi = $conn->real_escape_string($langkahDeskripsi);

                $queryInsertLangkah = "
                    INSERT INTO langkahMemasak (ResepID, NomorLangkah, DeskripsiLangkah) 
                    VALUES ('$resepID', '$nomorLangkah', '$langkahDeskripsi')
                ";
                $conn->query($queryInsertLangkah);
            }
        }

        // Simpan bahan dan kuantitas
        if (!empty($_POST['bahanNama'])) {
            foreach ($_POST['bahanNama'] as $index => $bahanNama) {
                $kuantitas = $_POST['kuantitas'][$index];
                $harga = $_POST['harga'][$index];
                $satuanID = $_POST['satuan'][$index];

                $bahanNama = $conn->real_escape_string($bahanNama);

                $queryInsertBahan = "
                    INSERT INTO bahanBaku (NamaBahanBaku, Harga) 
                    VALUES ('$bahanNama', '$harga')
                ";
                $conn->query($queryInsertBahan);
                $bahanBakuID = $conn->insert_id;

                $queryInsertResepBahan = "
                    INSERT INTO resepBahan (ResepID, BahanBakuID, JumlahBahan, SatuanID) 
                    VALUES ('$resepID', '$bahanBakuID', '$kuantitas', '$satuanID')
                ";
                $conn->query($queryInsertResepBahan);
            }
        }

        // Simpan foto resep
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
    } else {
        echo "Gagal menyimpan resep: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Resep</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-4">
        <h1>Tambah Resep</h1>
        <form method="POST" enctype="multipart/form-data">
            <!-- Nama Resep -->
            <div class="mb-3">
                <label for="NamaResep" class="form-label">Nama Resep</label>
                <input type="text" class="form-control" id="NamaResep" name="NamaResep" required>
            </div>

            <!-- Deskripsi Resep -->
            <div class="mb-3">
                <label for="DeskripsiResep" class="form-label">Deskripsi Resep</label>
                <textarea class="form-control" id="DeskripsiResep" name="DeskripsiResep" rows="3" required></textarea>
            </div>

            <!-- Link Video -->
            <div class="mb-3">
                <label for="LinkVideoTutorial" class="form-label">Link Video Tutorial</label>
                <input type="url" class="form-control" id="LinkVideoTutorial" name="LinkVideoTutorial">
            </div>

            <!-- Foto Resep -->
            <div class="mb-3">
                <label for="FotoResep" class="form-label">Tambah Foto</label>
                <input type="file" class="form-control" id="FotoResep" name="FotoResep[]" multiple>
            </div>

            <!-- Langkah Memasak -->
            <h5>Langkah Memasak</h5>
            <div id="langkah-container">
                <div class="mb-3">
                    <textarea class="form-control" name="langkah[]" rows="2" placeholder="Langkah Memasak"></textarea>
                </div>
            </div>
            <button type="button" id="add-step" class="btn btn-secondary mb-3">Tambah Langkah</button>

            <!-- Bahan -->
            <h5>Bahan</h5>
            <div id="bahan-container">
                <div class="mb-3 d-flex">
                    <input type="text" class="form-control me-2" name="bahanNama[]" placeholder="Nama Bahan" required>
                    <input type="number" class="form-control me-2" name="kuantitas[]" placeholder="Jumlah" required>
                    <select class="form-control me-2" name="satuan[]">
                        <?php foreach ($satuanOptions as $satuan): ?>
                            <option value="<?= $satuan['SatuanID'] ?>"><?= htmlspecialchars($satuan['NamaSatuan']) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <input type="number" class="form-control" name="harga[]" placeholder="Harga" required>
                </div>
            </div>
            <button type="button" id="add-bahan" class="btn btn-secondary mb-3">Tambah Bahan</button>

            <!-- Asal Masakan -->
            <div class="mb-3">
                <label for="AsalMasakanID" class="form-label">Asal Masakan</label>
                <select class="form-select" id="AsalMasakanID" name="AsalMasakanID" required>
                    <?php while ($asal = $resultAsalMasakan->fetch_assoc()): ?>
                        <option value="<?= $asal['AsalMasakanID'] ?>"><?= htmlspecialchars($asal['asalMasakan']) ?></option>
                    <?php endwhile; ?>
                </select>
            </div>

            <!-- Jenis Hidangan -->
            <div class="mb-3">
                <label for="JenisHidanganID" class="form-label">Jenis Hidangan</label>
                <select class="form-select" id="JenisHidanganID" name="JenisHidanganID" required>
                    <?php while ($jenis = $resultJenisHidangan->fetch_assoc()): ?>
                        <option value="<?= $jenis['JenisHidanganID'] ?>"><?= htmlspecialchars($jenis['jenisHidangan']) ?></option>
                    <?php endwhile; ?>
                </select>
            </div>

            <!-- Waktu Memasak -->
            <div class="mb-3">
                <label for="WaktuMemasakID" class="form-label">Waktu Memasak</label>
                <select class="form-select" id="WaktuMemasakID" name="WaktuMemasakID" required>
                    <?php while ($waktu = $resultWaktuMemasak->fetch_assoc()): ?>
                        <option value="<?= $waktu['WaktuMemasakID'] ?>"><?= htmlspecialchars($waktu['waktuMemasak']) ?></option>
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
                <textarea class="form-control" name="langkah[]" rows="2" placeholder="Langkah Memasak"></textarea>
            </div>`;
            langkahContainer.insertAdjacentHTML('beforeend', newLangkah);
        });

        // Tambah Bahan
        document.getElementById('add-bahan').addEventListener('click', () => {
            const bahanContainer = document.getElementById('bahan-container');
            const newBahan = `
            <div class="mb-3 d-flex">
                <input type="text" class="form-control me-2" name="bahanNama[]" placeholder="Nama Bahan" required>
                <input type="number" class="form-control me-2" name="kuantitas[]" placeholder="Jumlah" required>
                <select class="form-control me-2" name="satuan[]">
                    <?php foreach ($satuanOptions as $satuan): ?>
                        <option value="<?= $satuan['SatuanID'] ?>"><?= htmlspecialchars($satuan['NamaSatuan']) ?></option>
                    <?php endforeach; ?>
                </select>
                <input type="number" class="form-control" name="harga[]" placeholder="Harga" required>
            </div>`;
            bahanContainer.insertAdjacentHTML('beforeend', newBahan);
        });
    </script>
</body>

</html>
