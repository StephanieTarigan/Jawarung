<?php
include 'config.php';
$query = "SELECT * FROM resep";
$result = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Resep</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Daftar Resep</h1>
    <a href="add.php" class="btn">Tambah Resep</a>
    <table>
        <thead>
            <tr>
                <th>Nama Resep</th>
                <th>Kategori</th>
                <th>Rating</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['nama_resep'] ?></td>
                    <td><?= $row['kategori'] ?></td>
                    <td><?= $row['rating'] ?></td>
                    <td>
                        <a href="view.php?id=<?= $row['id'] ?>" class="btn">Lihat</a>
                        <a href="edit.php?id=<?= $row['id'] ?>" class="btn">Edit</a>
                        <a href="delete.php?id=<?= $row['id'] ?>" onclick="return confirm('Hapus resep ini?')" class="btn">Hapus</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>
