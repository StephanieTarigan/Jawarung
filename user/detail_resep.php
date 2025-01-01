<?php
session_start();
include "../dbconfig.php";
include "../template/main_layout.php";

// Memeriksa apakah pengguna sudah login
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'pelanggan') {
    header("Location: /login.php");
    exit();
}

// Mendapatkan ResepID dari parameter URL
if (!isset($_GET['ResepID'])) {
    header("Location: indexResep.php");
    exit();
}
$resepID = (int)$_GET['ResepID'];

// Query untuk mendapatkan detail resep
$queryResep = "
    SELECT 
        r.NamaResep, 
        r.DeskripsiResep, 
        r.Porsi, 
        r.LinkVideoTutorial, 
        f.FotoPath, 
        wm.waktuMemasak AS TotalWaktu
    FROM resep r
    LEFT JOIN fotoresep f ON r.ResepID = f.ResepID
    LEFT JOIN kategoriresep kr ON r.ResepID = kr.ResepID
    LEFT JOIN waktumemasak wm ON kr.WaktuMemasakID = wm.WaktuMemasakID
    WHERE r.ResepID = $resepID";

$resultResep = $conn->query($queryResep);
if ($resultResep->num_rows === 0) {
    die("Resep tidak ditemukan.");
}
$resep = $resultResep->fetch_assoc();

// Query untuk mendapatkan bahan-bahan
$queryBahan = "
    SELECT 
        b.NamaBahanBaku, 
        rb.JumlahBahan, 
        s.NamaSatuan, 
        b.Harga, 
        b.BahanBakuID
    FROM resepbahan rb
    JOIN bahanbaku b ON rb.ProdukID = b.BahanBakuID
    JOIN satuan s ON b.SatuanID = s.SatuanID
    WHERE rb.ResepID = $resepID";
$resultBahan = $conn->query($queryBahan);

// Query untuk mendapatkan langkah memasak
$queryLangkah = "
    SELECT NomorLangkah, DeskripsiLangkah
    FROM langkahmemasak
    WHERE ResepID = $resepID
    ORDER BY NomorLangkah ASC";
$resultLangkah = $conn->query($queryLangkah);

// Query untuk mendapatkan ulasan
$queryUlasan = "
    SELECT u.Username AS NamaPengguna, r.KomentarText, r.FotoKomentar, r.Rating
    FROM komentar r
    JOIN users u ON r.UserID = u.UserID
    WHERE r.ResepID = $resepID";
$resultUlasan = $conn->query($queryUlasan);

// Query untuk mendapatkan rata-rata rating
$queryRating = "
    SELECT 
        IFNULL(AVG(Rating), 0) AS AvgRating, 
        COUNT(Rating) AS TotalRatings
    FROM komentar
    WHERE ResepID = $resepID AND Rating > 0";
$resultRating = $conn->query($queryRating);
$ratingData = $resultRating->fetch_assoc();

$avgRating = round($ratingData['AvgRating'], 1); // Rata-rata rating, default 0
$totalRatings = $ratingData['TotalRatings'];    // Jumlah total rating, default 0
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['KomentarText'])) {
    // Ambil data dari form
    $komentarText = $conn->real_escape_string($_POST['KomentarText']);
    $rating = (int)$_POST['Rating'];
    $userID = $_SESSION['UserID']; // Pastikan sesi menyimpan UserID
    $fotoKomentar = null;

    // Upload foto jika ada
    if (!empty($_FILES['FotoKomentar']['name'])) {
        $targetDir = "../uploads/ulasan/";
        if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);
        $fileName = uniqid() . '.' . strtolower(pathinfo($_FILES['FotoKomentar']['name'], PATHINFO_EXTENSION));
        $targetFile = $targetDir . $fileName;

        if (move_uploaded_file($_FILES['FotoKomentar']['tmp_name'], $targetFile)) {
            $fotoKomentar = $fileName;
        }
    }

    // Simpan komentar ke database
    $queryInsertKomentar = "
        INSERT INTO komentar (ResepID, UserID, KomentarText, FotoKomentar, Rating)
        VALUES ($resepID, $userID, '$komentarText', '$fotoKomentar', $rating)";

    if ($conn->query($queryInsertKomentar)) {
        // Refresh halaman untuk melihat komentar baru
        header("Location: detail_resep.php?ResepID=$resepID");
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
}


?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($resep['NamaResep']); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
</head>

<body class="bg-light">
    <!-- Main Content -->
    <div class="container bg-white rounded shadow-sm p-4 my-4">
        <div class="row">
            <!-- Kiri: Bahan -->
            <div class="col-md-4">
                <img src="<?= !empty($resep['FotoPath']) ? "../admin/" . htmlspecialchars($resep['FotoPath']) : '../admin/uploads/default.jpg'; ?>" 
                     class="img-fluid rounded mb-3" alt="<?= htmlspecialchars($resep['NamaResep']); ?>">
                <h5 class="text-center">Bahan-Bahan</h5>
                <ul class="list-group mb-3">
                    <?php while ($bahan = $resultBahan->fetch_assoc()): ?>
                        <li class="list-group-item d-flex justify-content-between">
                            <?= htmlspecialchars($bahan['NamaBahanBaku']); ?>
                            <span><?= htmlspecialchars($bahan['JumlahBahan'] . " " . $bahan['NamaSatuan']); ?></span>
                        </li>
                    <?php endwhile; ?>
                </ul>
                <button class="btn btn-success w-100" data-bs-toggle="modal" data-bs-target="#detailHargaModal">Lihat Detail Harga</button>
            </div>

            <!-- Kanan: Langkah dan Ulasan -->
            <div class="col-md-8">
                <h2><?= htmlspecialchars($resep['NamaResep']); ?></h2>
                <span class="badge bg-success">Total Waktu: <?= htmlspecialchars($resep['TotalWaktu']); ?></span>
                <p class="mt-3"><?= htmlspecialchars($resep['DeskripsiResep']); ?></p>
                <h5>Cara Membuat</h5>
                <ol>
                    <?php while ($langkah = $resultLangkah->fetch_assoc()): ?>
                        <li><?= htmlspecialchars($langkah['DeskripsiLangkah']); ?></li>
                    <?php endwhile; ?>
                </ol>
                <div class="text-center my-4">
                    <a href="<?= htmlspecialchars($resep['LinkVideoTutorial']); ?>" class="btn btn-success" target="_blank">Tonton Video</a>
                </div>
<!-- Rating -->
<div class="text-center mb-4">
    <div class="fs-1 text-success fw-bold"><?= $avgRating; ?> / 5.0</div>
    <p><?= $totalRatings > 0 ? "$totalRatings ulasan" : "Belum ada ulasan"; ?></p>
</div>


                <!-- Ulasan -->
                <h5>Ulasan Pilihan</h5>
                <?php while ($ulasan = $resultUlasan->fetch_assoc()): ?>
                    <div class="d-flex mb-3 border-bottom pb-2">
                        <img src="<?= !empty($ulasan['FotoKomentar']) ? "../uploads/ulasan/" . htmlspecialchars($ulasan['FotoKomentar']) : '../admin/uploads/default.jpg'; ?>" 
                             alt="Foto Ulasan" class="me-3 rounded" style="width: 60px; height: 60px;">
                        <div>
                            <strong><?= htmlspecialchars($ulasan['NamaPengguna']); ?></strong>
                            <p class="text-warning"><?= $ulasan['Rating']; ?> / 5</p>
                            <p><?= htmlspecialchars($ulasan['KomentarText']); ?></p>
                        </div>
                    </div>
                <?php endwhile; ?>

                <!-- Form Tambah Ulasan -->
                <div class="bg-light p-3 rounded">
    <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="Rating" class="form-label">Rating</label>
            <select class="form-select" id="Rating" name="Rating" required>
                <option value="" disabled selected>Pilih Rating</option>
                <option value="1">1 - Sangat Buruk</option>
                <option value="2">2 - Buruk</option>
                <option value="3">3 - Cukup</option>
                <option value="4">4 - Bagus</option>
                <option value="5">5 - Sangat Bagus</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="KomentarText" class="form-label">Ulasan</label>
            <textarea class="form-control" id="KomentarText" name="KomentarText" rows="3" required></textarea>
        </div>
        <div class="mb-3">
            <label for="FotoKomentar" class="form-label">Upload Foto</label>
            <input type="file" class="form-control" id="FotoKomentar" name="FotoKomentar" accept="image/*">
        </div>
        <button type="submit" class="btn btn-success">Kirim Ulasan</button>
    </form>
</div>

            </div>
        </div>
    </div>

    <!-- Modal Detail Harga -->
    <div class="modal fade" id="detailHargaModal" tabindex="-1" aria-labelledby="detailHargaModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="detailHargaModalLabel">Detail Harga</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="pembayaran.php" method="POST">
                    <div class="modal-body">
                        <?php
                        $resultBahan->data_seek(0); // Reset pointer
                        while ($bahan = $resultBahan->fetch_assoc()): ?>
                            <div class="d-flex justify-content-between align-items-center border-bottom py-2">
                                <label>
                                    <input type="checkbox" name="selectedBahan[]" value="<?= $bahan['BahanBakuID']; ?>"> 
                                    <?= htmlspecialchars($bahan['NamaBahanBaku']); ?> 
                                    <small>(<?= htmlspecialchars($bahan['JumlahBahan'] . " " . $bahan['NamaSatuan']); ?>)</small>
                                </label>
                                <span>Rp<?= number_format($bahan['Harga'], 0, ',', '.'); ?></span>
                            </div>
                        <?php endwhile; ?>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-success">Beli Sekarang</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
<?php
$conn->close();
?>
