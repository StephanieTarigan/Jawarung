<?php
include "../dbconfig.php";

$id = $_GET['WarungID'];

$sqlDelete = "DELETE FROM warung WHERE WarungID = $id";
$query = mysqli_query($conn, $sqlDelete);

if ($query) {
    header("Location: indexWarung.php?successMsg=Warung berhasil dihapus.");
} else {
    header("Location: indexWarung.php?errorMsg=Gagal menghapus data warung!");
}

mysqli_close($conn);
?>
