<?php
session_start();
include "../dbconfig.php";
include "../template/main_layout.php";

// Memeriksa apakah pengguna sudah login
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'pelanggan') {
    header("Location: /login.php");
    exit();
}

// Query untuk mendapatkan semua resep
$queryResep = "SELECT r.ResepID, r.NamaResep, r.DeskripsiResep, f.FotoPath 
               FROM resep r
               LEFT JOIN fotoresep f ON r.ResepID = f.ResepID";
$resultResep = $conn->query($queryResep);

if (!$resultResep) {
    die("Query Error: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Index Resep</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
</head>

<body>
    <div class="container mt-4">
        <h2 class="text-center mb-4">Daftar Resep</h2>
        <div class="row g-3">
                <?php while ($rowResep = mysqli_fetch_assoc($resultResep)): ?>
                    <div class="col-md-4">
                        <div class="card h-100">
                            <img src="<?= !empty($rowResep['FotoPath']) && file_exists("../admin/" . $rowResep['FotoPath']) 
                                         ? "../admin/" . htmlspecialchars($rowResep['FotoPath']) 
                                         : "../admin/uploads/default.jpg"; ?>" 
                                 class="card-img-top" 
                                 alt="Foto Resep" 
                                 style="object-fit: cover; height: 200px;">
                            <div class="card-body">
                                <h5 class="card-title"><?= htmlspecialchars($rowResep['NamaResep']); ?></h5>
                                <p class="card-text"><?= htmlspecialchars($rowResep['DeskripsiResep']); ?></p>
                                <a href="detail_resep.php?ResepID=<?= $rowResep['ResepID']; ?>" class="btn btn-success btn-sm">Lihat Resep</a>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>

<?php
// Menutup koneksi database
$conn->close();
?>
