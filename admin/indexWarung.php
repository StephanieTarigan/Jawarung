<?php
session_start();
include "../dbconfig.php";
include "../template/main_layout.php";

// Mengecek apakah pengguna sudah login dan apakah perannya adalah admin
if (!isset($_SESSION['valid']) || $_SESSION['role'] !== 'admin') {
    header("Location: /login.php");
    exit;
}

// Query untuk mendapatkan data warung
$query = "SELECT * FROM warung";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Warung</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h2 class="mb-4 text-center">Manajemen Warung</h2>
        <div class="mb-3 text-end">
            <a href="add_warung.php" class="btn btn-success">Tambah Warung</a>
        </div>
        <table class="table table-bordered text-center align-middle">
            <thead>
                <tr>
                    <th>ID Warung</th>
                    <th>Nama Warung</th>
                    <th>Alamat Warung</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['WarungID']) ?></td>
                        <td><?= htmlspecialchars($row['NamaWarung']) ?></td>
                        <td><?= htmlspecialchars($row['AlamatWarung']) ?></td>
                        <td>
                            <a href="edit_warung.php?WarungID=<?= $row['WarungID'] ?>" class="btn btn-warning btn-sm">Edit</a>
                            <a href="delete_warung.php?WarungID=<?= $row['WarungID'] ?>" 
                               class="btn btn-danger btn-sm" 
                               onclick="return confirm('Yakin ingin menghapus warung ini?')">Hapus</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
