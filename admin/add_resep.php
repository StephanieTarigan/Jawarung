<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="../style.css">
<?php
session_start();
include "../dbconfig.php";
include "../template/main_layout.php";

// Mengecek apakah pengguna sudah login dan apakah perannya adalah admin
if (!isset($_SESSION['valid']) || $_SESSION['role'] !== 'admin') {
    // Jika tidak login atau bukan admin, arahkan kembali ke login
    header("Location: /login.php");
    exit;
}

// Variabel untuk pesan error atau sukses
$errMsg = '';
$successMsg = '';

// Memeriksa apakah tombol Simpan diklik
if (isset($_POST['btnSimpan'])) {
    // Mengambil data dari form
    $namaResep = $_POST['namaResep'];
    $deskripsiResep = $_POST['deskripsiResep'];
    $kategoriResepID = $_POST['kategoriResepID'];
    $waktuMemasakID = $_POST['waktuMemasakID'];
    $perkiraanHargaID = $_POST['perkiraanHargaID'];
    $jenisHidanganID = $_POST['jenisHidanganID'];
    $asalMasakanID = $_POST['asalMasakanID'];
    $linkVideoTutorial = $_POST['linkVideoTutorial'];

    // Menangani upload foto resep
    $fotoPath = '';
    if (isset($_FILES['fotoResep']) && $_FILES['fotoResep']['error'] == 0) {
        $fotoNama = $_FILES['fotoResep']['name'];
        $fotoTmp = $_FILES['fotoResep']['tmp_name'];
        $fotoExt = pathinfo($fotoNama, PATHINFO_EXTENSION);

        // Membuat nama file foto unik
        $fotoBaru = "foto_resep_" . time() . "." . $fotoExt;
        $fotoPath = "uploads/foto_resep/" . $fotoBaru;

        // Memindahkan file ke folder uploads
        if (!move_uploaded_file($fotoTmp, "../" . $fotoPath)) {
            $errMsg = "Gagal mengunggah foto resep!";
        }
    }

    // Jika tidak ada error, simpan resep ke database
    if (empty($errMsg)) {
        // Query untuk menyimpan data resep ke dalam database
        $sqlStatement = "INSERT INTO resep (NamaResep, DeskripsiResep, KategoriResepID, WaktuMemasakID, PerkiraanHargaID, JenisHidanganID, AsalMasakanID, LinkVideoTutorial)
                         VALUES ('$namaResep', '$deskripsiResep', '$kategoriResepID', '$waktuMemasakID', '$perkiraanHargaID', '$jenisHidanganID', '$asalMasakanID', '$linkVideoTutorial')";
        $query = mysqli_query($conn, $sqlStatement);

        if ($query) {
            // Mengambil ResepID yang baru dimasukkan
            $resepID = mysqli_insert_id($conn);

            // Menyimpan foto ke tabel fotoresep jika ada
            if ($fotoPath) {
                $sqlFotoResep = "INSERT INTO fotoresep (ResepID, FotoPath) VALUES ('$resepID', '$fotoPath')";
                $queryFoto = mysqli_query($conn, $sqlFotoResep);
                
                if (!$queryFoto) {
                    $errMsg = "Gagal menyimpan foto ke database!";
                }
            }

            // Menyimpan langkah-langkah resep
            if (isset($_POST['langkahText'])) {
                $langkahTexts = $_POST['langkahText'];
                foreach ($langkahTexts as $urutan => $langkahText) {
                    $urutanLangkah = $urutan + 1;  // Urutan langkah dimulai dari 1
                    $sqlLangkah = "INSERT INTO resep_langkah (ResepID, LangkahText, Urutan) VALUES ('$resepID', '$langkahText', '$urutanLangkah')";
                    $queryLangkah = mysqli_query($conn, $sqlLangkah);
                    if (!$queryLangkah) {
                        $errMsg = "Gagal menyimpan langkah-langkah resep!";
                        break;
                    }
                }
            }

            // Menyimpan bahan-bahan resep
            if (isset($_POST['bahanBakuID']) && isset($_POST['jumlahBahan'])) {
                $bahanBakuIDs = $_POST['bahanBakuID'];
                $jumlahBahan = $_POST['jumlahBahan'];
                foreach ($bahanBakuIDs as $index => $bahanBakuID) {
                    $jumlah = $jumlahBahan[$index];
                    $sqlBahan = "INSERT INTO resep_bahan (ResepID, BahanBakuID, JumlahBahan) VALUES ('$resepID', '$bahanBakuID', '$jumlah')";
                    $queryBahan = mysqli_query($conn, $sqlBahan);
                    if (!$queryBahan) {
                        $errMsg = "Gagal menyimpan bahan-bahan resep!";
                        break;
                    }
                }
            }

            // Jika semuanya berhasil
            if (empty($errMsg)) {
                $successMsg = "Resep berhasil ditambahkan!";
                header("Location: index.php?successMsg=$successMsg");
                exit;
            }
        } else {
            // Jika gagal menyimpan resep
            $errMsg = "Gagal menambahkan resep! " . mysqli_error($conn);
        }
    }

    // Menutup koneksi database
    mysqli_close($conn);
}

// Mengambil data untuk dropdown
$sqlKategoriResep = "SELECT * FROM kategori_resep";
$resultKategoriResep = mysqli_query($conn, $sqlKategoriResep);

$sqlWaktuMemasak = "SELECT * FROM waktu_memasak";
$resultWaktuMemasak = mysqli_query($conn, $sqlWaktuMemasak);

$sqlPerkiraanHarga = "SELECT * FROM perkiraan_harga";
$resultPerkiraanHarga = mysqli_query($conn, $sqlPerkiraanHarga);

$sqlJenisHidangan = "SELECT * FROM jenis_hidangan";
$resultJenisHidangan = mysqli_query($conn, $sqlJenisHidangan);

$sqlAsalMasakan = "SELECT * FROM asal_masakan";
$resultAsalMasakan = mysqli_query($conn, $sqlAsalMasakan);

$sqlBahanBaku = "SELECT * FROM bahan_baku";
$resultBahanBaku = mysqli_query($conn, $sqlBahanBaku);

// Memasukkan template header
include "../template/main_header.php";
?>

<!-- Bagian tampilan HTML untuk form -->
<div class="row mt-3 mb-4">
    <div class="col-md-6">
        <h4>Tambah Resep Baru</h4>
    </div>
</div>

<?php if ($errMsg): ?>
    <div class="alert alert-danger" role="alert">
        <?= $errMsg ?>
    </div>
<?php elseif ($successMsg): ?>
    <div class="alert alert-success" role="alert">
        <?= $successMsg ?>
    </div>
<?php endif; ?>

<form method="POST" action="add_resep.php" enctype="multipart/form-data">
    <!-- Input Nama Resep -->
    <div class="mb-1 row">
        <div class="col-2">
            <label for="namaResep" class="col-form-label">Nama Resep</label>
        </div>
        <div class="col-auto">
            <input type="text" class="form-control" id="namaResep" name="namaResep" placeholder="Nama Resep" required>
        </div>
    </div>
    
    <!-- Input Deskripsi Resep -->
    <div class="mb-1 row">
        <div class="col-2">
            <label for="deskripsiResep" class="col-form-label">Deskripsi Resep</label>
        </div>
        <div class="col-auto">
            <textarea class="form-control" id="deskripsiResep" name="deskripsiResep" placeholder="Deskripsi Resep" required></textarea>
        </div>
    </div>

    <!-- Input Langkah-Langkah -->
    <div id="langkahContainer">
        <div class="mb-1 row" id="langkah1">
            <div class="col-2">
                <label for="langkahText" class="col-form-label">Langkah 1</label>
            </div>
            <div class="col-auto">
                <textarea class="form-control" name="langkahText[]" placeholder="Deskripsi Langkah" required></textarea>
            </div>
        </div>
    </div>
    <button type="button" class="btn btn-sm btn-info" onclick="addLangkah()">Tambah Langkah</button>

    <!-- Input Kategori Resep -->
    <div class="mb-1 row">
        <div class="col-2">
            <label for="kategoriResepID" class="col-form-label">Kategori Resep</label>
        </div>
        <div class="col-auto">
            <select class="form-control" name="kategoriResepID" required>
                <option value="" disabled selected>Pilih Kategori Resep</option>
                <?php while ($row = mysqli_fetch_assoc($resultKategoriResep)): ?>
                    <option value="<?= $row['KategoriResepID'] ?>"><?= $row['NamaKategori'] ?></option>
                <?php endwhile; ?>
            </select>
        </div>
    </div>

    <!-- Input Waktu Memasak -->
    <div class="mb-1 row">
        <div class="col-2">
            <label for="waktuMemasakID" class="col-form-label">Waktu Memasak</label>
        </div>
        <div class="col-auto">
            <select class="form-control" name="waktuMemasakID" required>
                <option value="" disabled selected>Pilih Waktu Memasak</option>
                <?php while ($row = mysqli_fetch_assoc($resultWaktuMemasak)): ?>
                    <option value="<?= $row['WaktuMemasakID'] ?>"><?= $row['NamaWaktuMemasak'] ?></option>
                <?php endwhile; ?>
            </select>
        </div>
    </div>

    <!-- Input Perkiraan Harga -->
    <div class="mb-1 row">
        <div class="col-2">
            <label for="perkiraanHargaID" class="col-form-label">Perkiraan Harga</label>
        </div>
        <div class="col-auto">
            <select class="form-control" name="perkiraanHargaID" required>
                <option value="" disabled selected>Pilih Perkiraan Harga</option>
                <?php while ($row = mysqli_fetch_assoc($resultPerkiraanHarga)): ?>
                    <option value="<?= $row['PerkiraanHargaID'] ?>"><?= $row['NamaHarga'] ?></option>
                <?php endwhile; ?>
            </select>
        </div>
    </div>

    <!-- Input Jenis Hidangan -->
    <div class="mb-1 row">
        <div class="col-2">
            <label for="jenisHidanganID" class="col-form-label">Jenis Hidangan</label>
        </div>
        <div class="col-auto">
            <select class="form-control" name="jenisHidanganID" required>
                <option value="" disabled selected>Pilih Jenis Hidangan</option>
                <?php while ($row = mysqli_fetch_assoc($resultJenisHidangan)): ?>
                    <option value="<?= $row['JenisHidanganID'] ?>"><?= $row['NamaJenisHidangan'] ?></option>
                <?php endwhile; ?>
            </select>
        </div>
    </div>

    <!-- Input Asal Masakan -->
    <div class="mb-1 row">
        <div class="col-2">
            <label for="asalMasakanID" class="col-form-label">Asal Masakan</label>
        </div>
        <div class="col-auto">
            <select class="form-control" name="asalMasakanID" required>
                <option value="" disabled selected>Pilih Asal Masakan</option>
                <?php while ($row = mysqli_fetch_assoc($resultAsalMasakan)): ?>
                    <option value="<?= $row['AsalMasakanID'] ?>"><?= $row['NamaAsalMasakan'] ?></option>
                <?php endwhile; ?>
            </select>
        </div>
    </div>

    <!-- Input Link Video Tutorial -->
    <div class="mb-1 row">
        <div class="col-2">
            <label for="linkVideoTutorial" class="col-form-label">Link Video Tutorial</label>
        </div>
        <div class="col-auto">
            <input type="url" class="form-control" id="linkVideoTutorial" name="linkVideoTutorial" placeholder="Link Video Tutorial">
        </div>
    </div>

    <!-- Input Foto Resep -->
    <div class="mb-1 row">
        <div class="col-2">
            <label for="fotoResep" class="col-form-label">Foto Resep</label>
        </div>
        <div class="col-auto">
            <input type="file" class="form-control" id="fotoResep" name="fotoResep">
        </div>
    </div>

    <!-- Input Bahan-Bahan -->
    <div class="mb-3">
        <h5>Bahan-Bahan</h5>
        <div id="bahanContainer">
            <div class="row mb-1" id="bahan1">
                <div class="col-auto">
                    <select class="form-control" name="bahanBakuID[]" required>
                        <option value="" disabled selected>Pilih Bahan</option>
                        <?php while ($row = mysqli_fetch_assoc($resultBahanBaku)): ?>
                            <option value="<?= $row['BahanBakuID'] ?>"><?= $row['NamaBahan'] ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="col-auto">
                    <input type="text" class="form-control" name="jumlahBahan[]" placeholder="Jumlah Bahan" required>
                </div>
            </div>
        </div>
        <button type="button" class="btn btn-sm btn-info" onclick="addBahan()">Tambah Bahan</button>
    </div>

    <!-- Tombol Simpan -->
    <div class="mb-3">
        <button type="submit" name="btnSimpan" class="btn btn-primary">Simpan Resep</button>
    </div>
</form>

<script>
// Fungsi untuk menambah langkah
function addLangkah() {
    var langkahCount = document.querySelectorAll('#langkahContainer .row').length + 1;
    var newLangkah = `
        <div class="mb-1 row" id="langkah${langkahCount}">
            <div class="col-2">
                <label for="langkahText" class="col-form-label">Langkah ${langkahCount}</label>
            </div>
            <div class="col-auto">
                <textarea class="form-control" name="langkahText[]" placeholder="Deskripsi Langkah" required></textarea>
            </div>
        </div>
    `;
    document.getElementById('langkahContainer').insertAdjacentHTML('beforeend', newLangkah);
}

// Fungsi untuk menambah bahan
function addBahan() {
    var bahanCount = document.querySelectorAll('#bahanContainer .row').length + 1;
    var newBahan = `
        <div class="row mb-1" id="bahan${bahanCount}">
            <div class="col-auto">
                <select class="form-control" name="bahanBakuID[]" required>
                    <option value="" disabled selected>Pilih Bahan</option>
                    <?php while ($row = mysqli_fetch_assoc($resultBahanBaku)): ?>
                        <option value="<?= $row['BahanBakuID'] ?>"><?= $row['NamaBahan'] ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="col-auto">
                <input type="text" class="form-control" name="jumlahBahan[]" placeholder="Jumlah Bahan" required>
            </div>
        </div>
    `;
    document.getElementById('bahanContainer').insertAdjacentHTML('beforeend', newBahan);
}
</script>

<?php
// Menyisipkan template footer
include "../template/main_footer.php";
?>
