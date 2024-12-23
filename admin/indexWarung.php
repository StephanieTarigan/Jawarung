<?php
session_start();
include "../dbconfig.php";

$sqlStatement = "SELECT * FROM warung";
$query = mysqli_query($conn, $sqlStatement);
$data = mysqli_fetch_all($query, MYSQLI_ASSOC);

include "../template/main_layout.php";
?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center">
        <h4>Data Warung</h4>
        <a href="add_warung.php" class="btn btn-success">Tambah Warung</a>
    </div>
    <hr>

    <?php if (isset($_GET['successMsg'])): ?>
        <div class="alert alert-success" role="alert">
            <?= $_GET['successMsg'] ?>
        </div>
    <?php endif; ?>

    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover align-middle">
            <thead class="table-dark text-center">
                <tr>
                    <th>ID Warung</th>
                    <th>Nama Warung</th>
                    <th>Alamat Warung</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($data)): ?>
                    <?php foreach ($data as $warung): ?>
                        <tr>
                            <td class="text-center"><?= $warung["WarungID"] ?></td>
                            <td><?= $warung["NamaWarung"] ?></td>
                            <td><?= $warung["AlamatWarung"] ?></td>
                            <td class="text-center">
                                <a href="edit_warung.php?id=<?= $warung['WarungID'] ?>" class="btn btn-primary btn-sm">Edit</a>
                                <a href="delete_warung.php?id=<?= $warung['WarungID'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus?')">Hapus</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" class="text-center">Tidak ada data warung.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include "../template/main_footer.php"; 
// Menutup koneksi database
mysqli_close($conn);?>
