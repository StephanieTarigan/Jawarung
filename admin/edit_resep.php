<?php

session_start();
include "../dbconfig.php";
include "../template/main_layout.php";

// Mengecek apakah pengguna sudah login dan apakah perannya adalah admin
if (!isset($_SESSION['valid']) || $_SESSION['role'] !== 'admin') {
    header("Location: /login.php");
    exit;
}

// Mendapatkan ResepID dari parameter URL
if (!isset($_GET['ResepID'])) {
    header("Location: indexResep.php");
    exit();
}
$resepID = (int)$_GET['ResepID'];

// Handle form submission
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
        $conn->query("UPDATE resep 
                      SET NamaResep = '$namaResep', DeskripsiResep = '$deskripsiResep', LinkVideoTutorial = '$linkVideo' 
                      WHERE ResepID = $resepID");

        // Update kategori
        $conn->query("UPDATE kategoriResep 
                      SET AsalMasakanID = $asalMasakanID, JenisHidanganID = $jenisHidanganID, WaktuMemasakID = $waktuMemasakID 
                      WHERE ResepID = $resepID");

        // Update bahan
        $conn->query("DELETE FROM resepBahan WHERE ResepID = $resepID");
        if (isset($_POST['bahanID'])) {
            foreach ($_POST['bahanID'] as $index => $bahanID) {
                $bahanID = (int)$bahanID;
                $kuantitas = (float)$_POST['kuantitas'][$index];
                $conn->query("INSERT INTO resepBahan (ResepID, ProdukID, JumlahBahan) VALUES ($resepID, $bahanID, $kuantitas)");
            }
        }

        // Update langkah memasak
        $conn->query("DELETE FROM langkahMemasak WHERE ResepID = $resepID");
        if (isset($_POST['langkahDeskripsi'])) {
            foreach ($_POST['langkahDeskripsi'] as $index => $langkah) {
                $langkah = $conn->real_escape_string($langkah);
                $nomorLangkah = $index + 1;
                $conn->query("INSERT INTO langkahMemasak (ResepID, NomorLangkah, DeskripsiLangkah) VALUES ($resepID, $nomorLangkah, '$langkah')");
            }
        }

        // Update foto
        if (isset($_FILES['FotoResep']['name']) && !empty($_FILES['FotoResep']['name'][0])) {
            $targetDir = "uploads/foto_resep/";
            if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);

            foreach ($_FILES['FotoResep']['name'] as $key => $fileName) {
                $fileTmpName = $_FILES['FotoResep']['tmp_name'][$key];
                $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];

                if (in_array($fileExt, $allowedExtensions)) {
                    $newFileName = uniqid() . '.' . $fileExt;
                    $targetFilePath = $targetDir . $newFileName;

                    if (move_uploaded_file($fileTmpName, $targetFilePath)) {
                        $conn->query("INSERT INTO fotoResep (ResepID, FotoPath) VALUES ($resepID, '$targetFilePath')");
                    }
                }
            }
        }

        $conn->commit();
        header("Location: indexResep.php");
        exit();
    } catch (Exception $e) {
        $conn->rollback();
        die("Gagal memperbarui data: " . $e->getMessage());
    }
}

// Ambil data utama untuk form (sama seperti sebelumnya)
$queryResep = "SELECT * FROM resep WHERE ResepID = $resepID";
$resultResep = $conn->query($queryResep);
if ($resultResep->num_rows === 0) {
    die("Resep tidak ditemukan.");
}
$resep = $resultResep->fetch_assoc();

$queryKategoriResep = "SELECT * FROM kategoriResep WHERE ResepID = $resepID";
$resultKategoriResep = $conn->query($queryKategoriResep);
$kategoriResep = $resultKategoriResep->fetch_assoc();

$queryLangkah = "SELECT * FROM langkahMemasak WHERE ResepID = $resepID ORDER BY NomorLangkah ASC";
$resultLangkah = $conn->query($queryLangkah);

$queryBahan = "
    SELECT rb.ResepBahanID, b.NamaProduk, rb.JumlahBahan, rb.ProdukID, b.Harga
    FROM resepBahan rb
    JOIN produk b ON rb.ProdukID = b.ProdukID
    WHERE rb.ResepID = $resepID
";
$resultBahan = $conn->query($queryBahan);

$queryFotoResep = "SELECT * FROM fotoResep WHERE ResepID = $resepID";
$resultFoto = $conn->query($queryFotoResep);

$asalMasakanOptions = $conn->query("SELECT * FROM asalMasakan")->fetch_all(MYSQLI_ASSOC);
$jenisHidanganOptions = $conn->query("SELECT * FROM jenisHidangan")->fetch_all(MYSQLI_ASSOC);
$waktuMemasakOptions = $conn->query("SELECT * FROM waktuMemasak")->fetch_all(MYSQLI_ASSOC);
$produkOptions = $conn->query("SELECT ProdukID, NamaProduk, Harga FROM produk")->fetch_all(MYSQLI_ASSOC);

$totalHarga = 0;
$bahanData = [];
if ($resultBahan->num_rows > 0) {
    while ($bahan = $resultBahan->fetch_assoc()) {
        $subtotal = $bahan['JumlahBahan'] * $bahan['Harga'];
        $totalHarga += $subtotal;
        $bahanData[] = $bahan;
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
        <h1 class="text-center mb-4">Edit Resep</h1>
        <form method="POST" enctype="multipart/form-data">
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
                <label for="FotoResep" class="form-label">Tambah Foto Baru</label>
                <input type="file" class="form-control" id="FotoResep" name="FotoResep[]" multiple>
            </div>
            <div class="row g-3">
                <?php while ($foto = $resultFoto->fetch_assoc()): ?>
                    <div class="col-md-3">
                        <div class="card">
                            <img src="uploads/foto_resep/<?= htmlspecialchars($foto['FotoPath']) ?>" class="card-img-top" alt="Foto Resep" style="height: 150px; object-fit: cover;">
                            <div class="card-body text-center">
                                <a href="deleteFotoResep.php?FotoID=<?= $foto['FotoID'] ?>&ResepID=<?= $resepID ?>" class="btn btn-danger btn-sm">Hapus</a>
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
                        <textarea class="form-control" name="langkahDeskripsi[<?= $langkah['LangkahID'] ?>]" rows="2"><?= htmlspecialchars($langkah['DeskripsiLangkah']) ?></textarea>
                    </div>
                <?php endwhile; ?>
            </div>
            <button type="button" id="add-step" class="btn btn-secondary mb-3">Tambah Langkah</button>

            <!-- Bahan -->
            <h5>Bahan</h5>
            <div id="bahan-container">
                <?php foreach ($bahanData as $bahan): ?>
                    <div class="mb-3 d-flex align-items-center bahan-item">
                        <select class="form-control me-2 produk-select" name="bahanID[]">
                            <?php foreach ($produkOptions as $produk): ?>
                                <option value="<?= $produk['ProdukID'] ?>" <?= $bahan['ProdukID'] == $produk['ProdukID'] ? 'selected' : '' ?> data-harga="<?= $produk['Harga'] ?>">
                                    <?= htmlspecialchars($produk['NamaProduk']) ?> (Rp<?= number_format($produk['Harga'], 0, ',', '.') ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <input type="number" class="form-control me-2 jumlah-bahan" name="kuantitas[]" value="<?= $bahan['JumlahBahan'] ?>" placeholder="Jumlah" min="1" step="0.01" required>
                        <span class="form-text me-2">Subtotal: Rp<span class="subtotal-bahan"><?= number_format($bahan['JumlahBahan'] * $bahan['Harga'], 0, ',', '.') ?></span></span>
                        <button type="button" class="btn btn-danger remove-bahan">Hapus</button>
                    </div>
                <?php endforeach; ?>
            </div>
            <button type="button" id="add-bahan" class="btn btn-secondary mb-3">Tambah Bahan</button>

            <!-- Kategori -->
            <div class="mb-3">
                <label for="AsalMasakanID" class="form-label">Asal Masakan</label>
                <select class="form-select" id="AsalMasakanID" name="AsalMasakanID" required>
                    <?php foreach ($asalMasakanOptions as $asal): ?>
                        <option value="<?= $asal['AsalMasakanID'] ?>" <?= $kategoriResep['AsalMasakanID'] == $asal['AsalMasakanID'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($asal['asalMasakan']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="JenisHidanganID" class="form-label">Jenis Hidangan</label>
                <select class="form-select" id="JenisHidanganID" name="JenisHidanganID" required>
                    <?php foreach ($jenisHidanganOptions as $jenis): ?>
                        <option value="<?= $jenis['JenisHidanganID'] ?>" <?= $kategoriResep['JenisHidanganID'] == $jenis['JenisHidanganID'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($jenis['jenisHidangan']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="WaktuMemasakID" class="form-label">Waktu Memasak</label>
                <select class="form-select" id="WaktuMemasakID" name="WaktuMemasakID" required>
                    <?php foreach ($waktuMemasakOptions as $waktu): ?>
                        <option value="<?= $waktu['WaktuMemasakID'] ?>" <?= $kategoriResep['WaktuMemasakID'] == $waktu['WaktuMemasakID'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($waktu['waktuMemasak']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <h5>Total Harga: Rp<span id="total-harga"><?= number_format($totalHarga, 0, ',', '.') ?></span></h5>


            <!-- Submit -->
            <button type="submit" class="btn btn-success">Simpan</button>
            <a href="indexResep.php" class="btn btn-secondary">Kembali</a>
        </form>
    </div>

    <script>
        // Tambah Langkah
        document.getElementById('add-step').addEventListener('click', () => {
            const langkahContainer = document.getElementById('langkah-container');
            const newLangkah = `
        <div class="mb-3">
            <textarea class="form-control" name="newLangkah[]" rows="2" placeholder="Langkah Memasak" required></textarea>
        </div>`;
            langkahContainer.insertAdjacentHTML('beforeend', newLangkah);
        });

        // Tambah Bahan
        document.getElementById('add-bahan').addEventListener('click', () => {
            const bahanContainer = document.getElementById('bahan-container');
            const newBahan = `
        <div class="mb-3 d-flex align-items-center bahan-item">
            <select class="form-control me-2 produk-select" name="newBahan[]" required>
                <option value="">Pilih Bahan</option>
                <?php foreach ($produkOptions as $produk): ?>
                    <option value="<?= $produk['ProdukID'] ?>" data-harga="<?= $produk['Harga'] ?>">
                        <?= htmlspecialchars($produk['NamaProduk']) ?> (Rp<?= number_format($produk['Harga'], 0, ',', '.') ?>)
                    </option>
                <?php endforeach; ?>
            </select>
            <input type="number" class="form-control me-2 jumlah-bahan" name="newKuantitas[]" placeholder="Jumlah" min="1" step="0.01" required>
            <span class="form-text me-2">Subtotal: Rp<span class="subtotal-bahan">0</span></span>
            <button type="button" class="btn btn-danger remove-bahan">Hapus</button>
        </div>`;
            bahanContainer.insertAdjacentHTML('beforeend', newBahan);
            updateTotalHarga();
        });

        // Hapus Bahan
        document.getElementById('bahan-container').addEventListener('click', (event) => {
            if (event.target.classList.contains('remove-bahan')) {
                event.target.closest('.bahan-item').remove();
                updateTotalHarga();
            }
        });

        // Hitung Subtotal dan Total Harga
        document.getElementById('bahan-container').addEventListener('input', (event) => {
            if (event.target.classList.contains('jumlah-bahan') || event.target.classList.contains('produk-select')) {
                const bahanItem = event.target.closest('.bahan-item');
                const select = bahanItem.querySelector('.produk-select');
                const harga = parseFloat(select.options[select.selectedIndex].getAttribute('data-harga') || 0);
                const jumlah = parseFloat(bahanItem.querySelector('.jumlah-bahan').value || 0);
                const subtotal = harga * jumlah;

                bahanItem.querySelector('.subtotal-bahan').textContent = subtotal.toLocaleString('id-ID');
                updateTotalHarga();
            }
        });

        // Update Total Harga
        function updateTotalHarga() {
            let totalHarga = 0;
            document.querySelectorAll('.bahan-item').forEach((bahanItem) => {
                const subtotal = parseInt(bahanItem.querySelector('.subtotal-bahan').textContent.replace(/[^0-9.-]+/g, "") || 0);
                totalHarga += subtotal;
            });
            document.getElementById('total-harga').textContent = totalHarga.toLocaleString('id-ID');
        }
    </script>
</body>

</html>