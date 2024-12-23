<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="../style.css">

<?php
include "../dbconfig.php";
include "../template/main_layout.php";
?>

<body>
    <h3 style="text-align: center">Data Resep</h3>
    <p><a href="add_resep.php" class="link-underline-primary">Tambah Data Resep</a></p>
    <table class="table table-bordered" align="center">
        <thead>
            <th>No.</th>
            <th>Nama Resep</th>
            <th>Deskripsi Resep</th>
            <th>Bahan-Bahan</th>
            <th>Foto Resep</th>
            <th>Aksi</th>
        </thead>
        <tbody>
            <?php
            $sqlStatement = "SELECT * FROM resep";
            $query = mysqli_query($conn, $sqlStatement);

            $data = mysqli_fetch_all($query, MYSQLI_ASSOC);
            foreach ($data as $key => $dtresep) {
            ?>
                <tr>
                    <td><?php echo ++$key ?></td>
                    <td><?php echo htmlspecialchars($dtresep["NamaResep"]) ?></td>
                    <td><?php echo htmlspecialchars($dtresep["DeskripsiResep"]) ?></td>
                    <td><?php echo htmlspecialchars($dtresep["BahanBahan"]) ?></td>
                    <td>
                        <?php if (!empty($dtresep["FotoResep"])): ?>
                            <img src="../uploads/<?php echo htmlspecialchars($dtresep["FotoResep"]) ?>" alt="Foto Resep" style="width: 100px; height: 100px; object-fit: cover;">
                        <?php else: ?>
                            <p>Tidak ada foto</p>
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="edit_resep.php?id=<?php echo $dtresep['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                        <a href="delete_resep.php?id=<?php echo $dtresep['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus data ini?');">Hapus</a>
                    </td>
                </tr>
            <?php
            }
            ?>
        </tbody>
    </table>

    <?php
    include "../template/main_footer.php";
    ?>
</body>

</html>
