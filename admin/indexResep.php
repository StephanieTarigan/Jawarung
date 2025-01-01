<?php
session_start();
include "../dbconfig.php";
include "../template/main_layout.php";
// Mengecek apakah pengguna sudah login dan apakah perannya adalah admin
if (!isset($_SESSION['valid']) || $_SESSION['role'] !== 'admin') {
    header("Location: /login.php");
    exit;
}

// Query untuk mendapatkan data resep
$query = "
    SELECT r.ResepID, r.NamaResep, r.DeskripsiResep, r.LinkVideoTutorial, fr.FotoPath 
    FROM resep r
    LEFT JOIN fotoresep fr ON r.ResepID = fr.ResepID
";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Daftar Resep</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container my-4">
        <h2>Manajemen Resep</h2>
        <a href="add_resep.php" class="btn btn-success mb-3">Tambah Resep</a>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Foto</th>
                    <th>Nama Resep</th>
                    <th>Deskripsi</th>
                    <th>Link Video</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td>
                            <?php if (!empty($row['FotoPath'])): ?>
                                <img src="../uploads/foto_resep/<?php echo $row['FotoPath']; ?>" 
                                    alt="Foto Resep" style="width: 80px; height: 80px; object-fit: cover;">
                            <?php else: ?>
                                <img src="../assets/no-image.jpg" alt="No Image" 
                                    style="width: 80px; height: 80px; object-fit: cover;">
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($row['NamaResep']) ?></td>
                        <td><?= htmlspecialchars($row['DeskripsiResep']) ?></td>
                        <td>
                            <a href="<?= htmlspecialchars($row['LinkVideoTutorial']) ?>" target="_blank">Tonton Video</a>
                        </td>
                        <td>
                            <a href="edit_resep.php?ResepID=<?= $row['ResepID'] ?>" class="btn btn-warning btn-sm">Edit</a>
                            <a href="delete_resep.php?ResepID=<?= $row['ResepID'] ?>" 
                                class="btn btn-danger btn-sm" 
                                onclick="return confirm('Yakin ingin menghapus resep ini?')">Hapus</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
