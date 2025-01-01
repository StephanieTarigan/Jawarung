<?php
session_start();
include "../dbconfig.php";
include "../template/main_layout.php";

// Memeriksa apakah pengguna sudah login
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'pelanggan') {
    header("Location: /login.php");
    exit();
}

// Memeriksa apakah ProdukID tersedia
if (!isset($_GET['ProdukID'])) {
    header("Location: indexProduk.php");
    exit();
}
$produkID = (int)$_GET['ProdukID'];

// Query untuk mendapatkan detail produk
$queryProduk = "
    SELECT 
        p.NamaProduk, 
        p.DeskripsiProduk, 
        p.Harga, 
        p.stock, 
        w.NamaWarung, 
        s.NamaSatuan
    FROM produk p
    LEFT JOIN warung w ON p.WarungID = w.WarungID
    LEFT JOIN satuan s ON p.SatuanID = s.SatuanID
    WHERE p.ProdukID = $produkID";
$resultProduk = $conn->query($queryProduk);

if ($resultProduk->num_rows === 0) {
    die("Produk tidak ditemukan.");
}
$produk = $resultProduk->fetch_assoc();

// Query untuk mendapatkan foto produk
$queryFoto = "
    SELECT FotoPath 
    FROM fotoproduk 
    WHERE ProdukID = $produkID";
$resultFoto = $conn->query($queryFoto);

// Query untuk mendapatkan komentar
$queryKomentar = "
    SELECT 
        k.KomentarText, 
        k.Rating, 
        u.Username, 
        k.FotoKomentar
    FROM komentar k
    JOIN users u ON k.UserID = u.UserID
    WHERE k.ProdukID = $produkID";
$resultKomentar = $conn->query($queryKomentar);

// Query untuk mendapatkan rating rata-rata
$queryRating = "
    SELECT 
        IFNULL(AVG(k.Rating), 0) AS AvgRating, 
        COUNT(k.Rating) AS TotalRatings
    FROM komentar k
    WHERE k.ProdukID = $produkID";
$resultRating = $conn->query($queryRating);
$ratingData = $resultRating->fetch_assoc();
$avgRating = round($ratingData['AvgRating'], 1);
$totalRatings = $ratingData['TotalRatings'];

// Menangani form komentar
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['KomentarText'])) {
    $userID = $_SESSION['UserID'];
    $komentarText = $conn->real_escape_string($_POST['KomentarText']);
    $rating = (int)$_POST['Rating'];
    $fotoKomentar = null;

    // Upload foto jika ada
    if (!empty($_FILES['FotoKomentar']['name'])) {
        $uploadDir = '../uploads/komentar/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $fileName = uniqid() . '.' . pathinfo($_FILES['FotoKomentar']['name'], PATHINFO_EXTENSION);
        $targetFile = $uploadDir . $fileName;

        if (move_uploaded_file($_FILES['FotoKomentar']['tmp_name'], $targetFile)) {
            $fotoKomentar = $fileName;
        }
    }

    // Simpan komentar ke database
    $queryInsert = "
        INSERT INTO komentar (ProdukID, UserID, KomentarText, Rating, FotoKomentar)
        VALUES ($produkID, $userID, '$komentarText', $rating, '$fotoKomentar')";
    $conn->query($queryInsert);

    // Refresh halaman
    header("Location: detail_produk.php?ProdukID=$produkID");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($produk['NamaProduk']); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <div class="container bg-white rounded shadow-sm p-4 my-4">
        <div class="row">
            <!-- Kiri: Foto dan Rating -->
            <div class="col-md-4">
                <?php if ($resultFoto->num_rows > 0): ?>
                    <div id="carouselExample" class="carousel slide mb-4" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            <?php $isActive = true; ?>
                            <?php while ($foto = $resultFoto->fetch_assoc()): ?>
                                <div class="carousel-item <?= $isActive ? 'active' : ''; ?>">
                                    <img src="<?= !empty($foto['FotoPath']) ? "../admin/" . htmlspecialchars($foto['FotoPath']) : '../admin/uploads/default.jpg'; ?>" 
                                         class="d-block w-100 rounded" alt="Foto Produk">
                                </div>
                                <?php $isActive = false; ?>
                            <?php endwhile; ?>
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#carouselExample" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    </div>
                <?php else: ?>
                    <p class="text-center">Tidak ada foto untuk produk ini.</p>
                <?php endif; ?>

                <!-- Rating -->
                <div class="text-center">
                    <div class="fs-1 text-success fw-bold"><?= $avgRating; ?> / 5.0</div>
                    <p><?= $totalRatings > 0 ? "$totalRatings ulasan" : "Belum ada ulasan"; ?></p>
                </div>
            </div>

            <!-- Kanan: Detail Produk -->
            <div class="col-md-8">
                <h2><?= htmlspecialchars($produk['NamaProduk']); ?></h2>
                <p><strong>Harga:</strong> 
                    <span class="text-danger fw-bold fs-3">Rp<?= number_format($produk['Harga'], 0, ',', '.'); ?></span>
                </p>
                <p><strong>Stok:</strong> 
                    <span class="badge <?= $produk['stock'] > 0 ? 'bg-success' : 'bg-danger'; ?>">
                        <?= $produk['stock'] > 0 ? 'Tersedia' : 'Habis'; ?>
                    </span>
                </p>
                <p><strong>Satuan:</strong> <?= htmlspecialchars($produk['NamaSatuan']); ?></p>
                <p><strong>Warung:</strong> <?= htmlspecialchars($produk['NamaWarung']); ?></p>
                <button class="btn btn-success">Beli Sekarang</button>
                <hr>
                <h5>Deskripsi Produk</h5>
                <p><?= nl2br(htmlspecialchars($produk['DeskripsiProduk'])); ?></p>

                <hr>

        <!-- Komentar -->
        <h5>Komentar</h5>
        <?php if ($resultKomentar->num_rows > 0): ?>
            <?php while ($komentar = $resultKomentar->fetch_assoc()): ?>
                <div class="d-flex align-items-start mb-3 border-bottom pb-2">
                    <img src="<?= !empty($komentar['FotoKomentar']) ? '../uploads/komentar/' . htmlspecialchars($komentar['FotoKomentar']) : '../assets/default-user.png'; ?>" 
                         alt="Foto Komentar" class="me-3 rounded-circle" style="width: 50px; height: 50px;">
                    <div>
                        <strong><?= htmlspecialchars($komentar['Username']); ?></strong>
                        <div class="text-warning">Rating: <?= htmlspecialchars($komentar['Rating']); ?> / 5</div>
                        <p class="mb-0"><?= htmlspecialchars($komentar['KomentarText']); ?></p>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>Belum ada komentar.</p>
        <?php endif; ?>

        <!-- Form Tambah Komentar -->
        <h5>Tambahkan Komentar Anda</h5>
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
                <label for="KomentarText" class="form-label">Komentar</label>
                <textarea class="form-control" id="KomentarText" name="KomentarText" rows="3" required></textarea>
            </div>
            <div class="mb-3">
                <label for="FotoKomentar" class="form-label">Upload Foto (Opsional)</label>
                <input type="file" class="form-control" id="FotoKomentar" name="FotoKomentar" accept="image/*">
            </div>
            <button type="submit" class="btn btn-success">Kirim Komentar</button>
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
