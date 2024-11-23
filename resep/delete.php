<?php
include 'config.php';
$id = $_GET['id'];
$query = "DELETE FROM resep WHERE id=$id";

if ($conn->query($query) === TRUE) {
    header("Location: index.php");
} else {
    echo "Error: " . $query . "<br>" . $conn->error;
}
?>
