<?php
$host = "localhost";
$user = "root";
$password = "";
$dbname = "resep_db";

// Koneksi ke MySQL
$conn = new mysqli($host, $user, $password);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Buat database jika belum ada
$sql = "CREATE DATABASE IF NOT EXISTS $dbname";
if ($conn->query($sql) === FALSE) {
    die("Gagal membuat database: " . $conn->error);
}

// Pilih database
$conn->select_db($dbname);

// Buat tabel jika belum ada
$sql = "
CREATE TABLE IF NOT EXISTS resep (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_resep VARCHAR(255) NOT NULL,
    kategori VARCHAR(255),
    waktu_total VARCHAR(50),
    waktu_persiapan VARCHAR(50),
    waktu_memasak VARCHAR(50),
    bahan TEXT,
    cara_membuat TEXT,
    rating FLOAT,
    ulasan TEXT
)";
if ($conn->query($sql) === FALSE) {
    die("Gagal membuat tabel: " . $conn->error);
}
?>
