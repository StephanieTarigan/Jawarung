<?php
include "../dbconfig.php";

$id = $_GET['id'];

$sqlDelete = "DELETE FROM warung WHERE WarungID = $id";
$query = mysqli_query($conn, $sqlDelete);

if ($query) {
    header("Location: index.php?successMsg=Warung berhasil dihapus.");
} else {
    header("Location: index.php?errorMsg=Gagal menghapus data warung!");
}

mysqli_close($conn);
?>
