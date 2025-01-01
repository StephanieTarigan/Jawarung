<?php
session_start();
include "../dbconfig.php";
include "../template/main_layout.php";

// Mengecek apakah pengguna sudah login dan apakah perannya adalah admin
if (!isset($_SESSION['valid']) || $_SESSION['role'] !== 'admin') {
    header("Location: /login.php");
    exit;
}

// Ambil data kategori dan produk
$satuanOptions = $conn->query("SELECT * FROM satuan")->fetch_all(MYSQLI_ASSOC);
$produkOptions = $conn->query("SELECT ProdukID, NamaProduk, Harga FROM produk")->fetch_all(MYSQLI_ASSOC);
$asalMasakanOptions = $conn->query("SELECT * FROM asalMasakan")->fetch_all(MYSQLI_ASSOC);
$jenisHidanganOptions = $conn->query("SELECT * FROM jenisHidangan")->fetch_all(MYSQLI_ASSOC);
$waktuMemasakOptions = $conn->query("SELECT * FROM waktuMemasak")->fetch_all(MYSQLI_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $namaResep = $conn->real_escape_string($_POST['NamaResep']);
    $deskripsiResep = $conn->real_escape_string($_POST['DeskripsiResep']);
    $linkVideo = $conn->real_escape_string($_POST['LinkVideoTutorial']);
    $asalMasakanID = (int)$_POST['AsalMasakanID'];
    $jenisHidanganID = (int)$_POST['JenisHidanganID'];
    $waktuMemasakID = (int)$_POST['WaktuMemasakID'];
    $totalHargaResep = 0;

    $conn->begin_transaction();
    try {
        // Simpan data utama resep
        $stmt = $conn->prepare("INSERT INTO resep (NamaResep, DeskripsiResep, LinkVideoTutorial) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $namaResep, $deskripsiResep, $linkVideo);
        $stmt->execute();
        $resepID = $stmt->insert_id;

        // Hitung Perkiraan Harga
        $hargaMin = PHP_INT_MAX; // Inisialisasi nilai minimum
        $hargaMax = PHP_INT_MIN; // Inisialisasi nilai maksimum

        // Simpan bahan
        if (!empty($_POST['bahanID'])) {
            foreach ($_POST['bahanID'] as $index => $bahanID) {
                $bahanID = (int)$bahanID;
                $kuantitas = (float)$_POST['kuantitas'][$index];

                // Ambil harga dari tabel produk
                $stmt = $conn->prepare("SELECT Harga FROM produk WHERE ProdukID = ?");
                $stmt->bind_param("i", $bahanID);
                $stmt->execute();
                $result = $stmt->get_result();
                $row = $result->fetch_assoc();
                $harga = $row['Harga'];

                // Hitung subtotal
                $subtotal = $kuantitas * $harga;
                $totalHargaResep += $subtotal;

                // Perbarui nilai perkiraan harga minimum dan maksimum
                $hargaMin = min($hargaMin, $subtotal);
                $hargaMax = max($hargaMax, $subtotal);

                // Simpan relasi bahan ke resep
                $stmt = $conn->prepare("INSERT INTO resepBahan (ResepID, ProdukID, JumlahBahan) VALUES (?, ?, ?)");
                $stmt->bind_param("iid", $resepID, $bahanID, $kuantitas);
                $stmt->execute();
            }
        }


        // Simpan langkah memasak
        if (!empty($_POST['langkah'])) {
            foreach ($_POST['langkah'] as $nomor => $deskripsiLangkah) {
                $deskripsiLangkah = $conn->real_escape_string($deskripsiLangkah);
                $nomorLangkah = $nomor + 1; // Nomor langkah dimulai dari 1

                $stmt = $conn->prepare("INSERT INTO langkahMemasak (ResepID, NomorLangkah, DeskripsiLangkah) VALUES (?, ?, ?)");
                $stmt->bind_param("iis", $resepID, $nomorLangkah, $deskripsiLangkah);
                $stmt->execute();
            }
        }


        // Simpan Perkiraan Harga
        if ($hargaMin === PHP_INT_MAX) $hargaMin = 0; // Jika tidak ada bahan
        if ($hargaMax === PHP_INT_MIN) $hargaMax = 0;

        $stmt = $conn->prepare("INSERT INTO perkiraanHarga (hargaMin, hargaMax) VALUES (?, ?)");
        $stmt->bind_param("dd", $hargaMin, $hargaMax);
        $stmt->execute();
        $perkiraanHargaID = $stmt->insert_id;

        // Simpan kategori resep
        $stmt = $conn->prepare("INSERT INTO kategoriResep (ResepID, AsalMasakanID, JenisHidanganID, WaktuMemasakID, PerkiraanHargaID) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("iiiii", $resepID, $asalMasakanID, $jenisHidanganID, $waktuMemasakID, $perkiraanHargaID);
        $stmt->execute();

        // Simpan langkah memasak
        if (!empty($_POST['langkah'])) {
            foreach ($_POST['langkah'] as $index => $deskripsiLangkah) {
                $deskripsiLangkah = $conn->real_escape_string($deskripsiLangkah);
                $nomorLangkah = $index + 1;

                $stmt = $conn->prepare("INSERT INTO langkahmemasak (ResepID, NomorLangkah, DeskripsiLangkah) VALUES (?, ?, ?)");
                $stmt->bind_param("iis", $resepID, $nomorLangkah, $deskripsiLangkah);
                $stmt->execute();
            }
        }


        // Simpan foto resep
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
                        $stmt = $conn->prepare("INSERT INTO fotoResep (ResepID, FotoPath) VALUES (?, ?)");
                        $stmt->bind_param("is", $resepID, $targetFilePath);
                        $stmt->execute();
                    }
                }
            }
        }

        $conn->commit();
        header("Location: indexResep.php");
        exit();
    } catch (Exception $e) {
        $conn->rollback();
        echo "Gagal menyimpan resep: " . $e->getMessage();
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

<body class="bg-light">
    <div class="container mt-4">
        <h1 class="mb-4 text-center">Tambah Resep</h1>
        <form method="POST" enctype="multipart/form-data">
            <!-- Nama Resep -->
            <div class="mb-3">
                <label for="NamaResep" class="form-label">Nama Resep</label>
                <input type="text" class="form-control" id="NamaResep" name="NamaResep" maxlength="100" required>
            </div>

            <!-- Deskripsi Resep -->
            <div class="mb-3">
                <label for="DeskripsiResep" class="form-label">Deskripsi Resep</label>
                <textarea class="form-control" id="DeskripsiResep" name="DeskripsiResep" rows="3" maxlength="255" required></textarea>
            </div>

            <!-- Link Video -->
            <div class="mb-3">
                <label for="LinkVideoTutorial" class="form-label">Link Video Tutorial</label>
                <input type="url" class="form-control" id="LinkVideoTutorial" name="LinkVideoTutorial">
            </div>

            <!-- Foto Resep -->
            <div id="photo-container">
                <div class="mb-3 d-flex align-items-center photo-item">
                    <input type="file" class="form-control me-2 photo-input" name="FotoResep[]" accept="image/*" required>
                    <button type="button" class="btn btn-danger remove-photo">Hapus</button>
                </div>
            </div>

            <div id="photo-preview" class="mt-3"></div>
            <button type="button" id="add-photo" class="btn btn-secondary mb-3">Tambah Foto</button>



            <!-- Langkah Memasak -->
            <h5>Langkah Memasak</h5>
            <div id="langkah-container">
                <div class="mb-3">
                    <textarea class="form-control" name="langkah[]" rows="2" placeholder="Langkah Memasak" required></textarea>
                </div>
            </div>
            <button type="button" id="add-step" class="btn btn-secondary mb-3">Tambah Langkah</button>

            <!-- Bahan -->
            <h5>Bahan</h5>
            <div id="bahan-container">
                <div class="mb-3 d-flex align-items-center bahan-item">
                    <select class="form-control me-2 produk-select" name="bahanID[]" required>
                        <option value="">Pilih Bahan</option>
                        <?php foreach ($produkOptions as $produk): ?>
                            <option value="<?= $produk['ProdukID'] ?>" data-harga="<?= $produk['Harga'] ?>">
                                <?= htmlspecialchars($produk['NamaProduk']) ?> (Rp<?= number_format($produk['Harga'], 0, ',', '.') ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <input type="number" class="form-control me-2 jumlah-bahan" name="kuantitas[]" placeholder="Jumlah" min="1" step="0.01" required>
                    <span class="form-text me-2">Subtotal: Rp<span class="subtotal-bahan">0</span></span>
                    <button type="button" class="btn btn-danger remove-bahan">Hapus</button>
                </div>
            </div>
            <button type="button" id="add-bahan" class="btn btn-secondary mb-3">Tambah Bahan</button>

            <!-- Kategori -->
            <div class="mb-3">
                <label for="AsalMasakanID" class="form-label">Asal Masakan</label>
                <select class="form-select" id="AsalMasakanID" name="AsalMasakanID" required>
                    <option value="">Pilih Asal Masakan</option>
                    <?php foreach ($asalMasakanOptions as $asal): ?>
                        <option value="<?= $asal['AsalMasakanID'] ?>"><?= htmlspecialchars($asal['asalMasakan']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="JenisHidanganID" class="form-label">Jenis Hidangan</label>
                <select class="form-select" id="JenisHidanganID" name="JenisHidanganID" required>
                    <option value="">Pilih Jenis Hidangan</option>
                    <?php foreach ($jenisHidanganOptions as $jenis): ?>
                        <option value="<?= $jenis['JenisHidanganID'] ?>"><?= htmlspecialchars($jenis['jenisHidangan']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="WaktuMemasakID" class="form-label">Waktu Memasak</label>
                <select class="form-select" id="WaktuMemasakID" name="WaktuMemasakID" required>
                    <option value="">Pilih Waktu Memasak</option>
                    <?php foreach ($waktuMemasakOptions as $waktu): ?>
                        <option value="<?= $waktu['WaktuMemasakID'] ?>"><?= htmlspecialchars($waktu['waktuMemasak']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <h5>Total Harga: Rp<span id="total-harga">0</span></h5>
            <button type="submit" class="btn btn-success">Simpan</button>
        </form>
    </div>

    <script>
        // Tambah Foto
        document.getElementById('add-photo').addEventListener('click', () => {
            const photoContainer = document.getElementById('photo-container');
            const newPhoto = `
        <div class="mb-3 d-flex align-items-center photo-item">
            <input type="file" class="form-control me-2 photo-input" name="FotoResep[]" accept="image/*" required>
            <button type="button" class="btn btn-danger remove-photo">Hapus</button>
        </div>`;
            photoContainer.insertAdjacentHTML('beforeend', newPhoto);
        });

        // Hapus Foto
        document.getElementById('photo-container').addEventListener('click', (event) => {
            if (event.target.classList.contains('remove-photo')) {
                event.target.closest('.photo-item').remove();
                updatePhotoPreview();
            }
        });

        // Pratinjau Foto
        document.getElementById('photo-container').addEventListener('change', (event) => {
            if (event.target.classList.contains('photo-input')) {
                updatePhotoPreview();
            }
        });

        // Fungsi untuk Memperbarui Pratinjau Foto
        function updatePhotoPreview() {
            const previewContainer = document.getElementById('photo-preview');
            previewContainer.innerHTML = ''; // Kosongkan pratinjau sebelumnya

            document.querySelectorAll('.photo-input').forEach((input) => {
                const file = input.files[0];
                if (file && file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        const imgElement = document.createElement('img');
                        imgElement.src = e.target.result;
                        imgElement.alt = file.name;
                        imgElement.className = 'img-thumbnail me-2 mt-2';
                        imgElement.style.height = '100px';
                        previewContainer.appendChild(imgElement);
                    };
                    reader.readAsDataURL(file);
                }
            });
        }


        // Tambah Langkah
        document.getElementById('add-step').addEventListener('click', () => {
            const langkahContainer = document.getElementById('langkah-container');
            const newLangkah = `
                <div class="mb-3">
                    <textarea class="form-control" name="langkah[]" rows="2" placeholder="Langkah Memasak" required></textarea>
                </div>`;
            langkahContainer.insertAdjacentHTML('beforeend', newLangkah);
        });

        // Tambah Bahan
        document.getElementById('add-bahan').addEventListener('click', () => {
            const bahanContainer = document.getElementById('bahan-container');
            const newBahan = `
                <div class="mb-3 d-flex align-items-center bahan-item">
                    <select class="form-control me-2 produk-select" name="bahanID[]" required>
                        <option value="">Pilih Bahan</option>
                        <?php foreach ($produkOptions as $produk): ?>
                            <option value="<?= $produk['ProdukID'] ?>" data-harga="<?= $produk['Harga'] ?>">
                                <?= htmlspecialchars($produk['NamaProduk']) ?> (Rp<?= number_format($produk['Harga'], 0, ',', '.') ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <input type="number" class="form-control me-2 jumlah-bahan" name="kuantitas[]" placeholder="Jumlah" min="1" step="0.01" required>
                    <span class="form-text me-2">Subtotal: Rp<span class="subtotal-bahan">0</span></span>
                    <button type="button" class="btn btn-danger remove-bahan">Hapus</button>
                </div>`;
            bahanContainer.insertAdjacentHTML('beforeend', newBahan);
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
            if (event.target.classList.contains('jumlah-bahan')) {
                const bahanItem = event.target.closest('.bahan-item');
                const select = bahanItem.querySelector('.produk-select');
                const harga = parseFloat(select.options[select.selectedIndex].getAttribute('data-harga') || 0);
                const jumlah = parseFloat(event.target.value || 0);
                const subtotal = harga * jumlah;

                // Update subtotal
                bahanItem.querySelector('.subtotal-bahan').textContent = subtotal.toLocaleString('id-ID');
                updateTotalHarga();
            }
        });

        // Update Total Harga
        function updateTotalHarga() {
            let totalHarga = 0;
            document.querySelectorAll('.bahan-item').forEach((bahanItem) => {
                const subtotal = parseFloat(bahanItem.querySelector('.subtotal-bahan').textContent.replace(/[^0-9.-]+/g, "") || 0);
                totalHarga += subtotal;
            });
            document.getElementById('total-harga').textContent = totalHarga.toLocaleString('id-ID');
        }
    </script>
</body>

</html>