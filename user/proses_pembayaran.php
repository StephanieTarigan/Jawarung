<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "highdsd";

// Membuat koneksi ke database
$conn = new mysqli($servername, $username, $password, $dbname);

// Memeriksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Menangkap dan memvalidasi data dari form
$TotalBayar = filter_input(INPUT_POST, 'TotalBayar', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
$MetodePembayaran = filter_input(INPUT_POST, 'MetodePembayaran', FILTER_SANITIZE_STRING);
$AlamatTujuan = filter_input(INPUT_POST, 'AlamatTujuan', FILTER_SANITIZE_STRING);
$Kabupaten = filter_input(INPUT_POST, 'Kabupaten', FILTER_SANITIZE_STRING);
$Kota = filter_input(INPUT_POST, 'Kota', FILTER_SANITIZE_STRING);
$Kecamatan = filter_input(INPUT_POST, 'Kecamatan', FILTER_SANITIZE_STRING);
$KodePos = filter_input(INPUT_POST, 'KodePos', FILTER_SANITIZE_STRING);
$BiayaPengiriman = filter_input(INPUT_POST, 'BiayaPengiriman', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
$UserID = filter_input(INPUT_POST, 'UserID', FILTER_SANITIZE_NUMBER_INT);

// Validasi input wajib
if (empty($TotalBayar) || empty($MetodePembayaran) || empty($AlamatTujuan) || empty($Kecamatan) || empty($KodePos) || empty($BiayaPengiriman)) {
    die("Semua field wajib diisi!");
}

// Validasi minimal salah satu antara Kabupaten atau Kota harus diisi
if (empty($Kabupaten) && empty($Kota)) {
    die("Harap isi salah satu: Kabupaten atau Kota!");
}

// Tanggal sekarang
$TanggalPembayaran = date("Y-m-d H:i:s");
$TanggalPengiriman = date("Y-m-d H:i:s");

// Status pembayaran dan pengiriman
$StatusPembayaran = "Pending";
$StatusPengiriman = "Diproses"; // Status pengiriman langsung menjadi "Diproses"

$conn->begin_transaction();

try {
    // Menggunakan prepared statements untuk pembayaran
    $stmtPembayaran = $conn->prepare("INSERT INTO pembayaran (UserID, TotalBayar, TanggalPembayaran, MetodePembayaran, StatusPembayaran) VALUES (?, ?, ?, ?, ?)");
    $stmtPembayaran->bind_param("sdsss", $UserID, $TotalBayar, $TanggalPembayaran, $MetodePembayaran, $StatusPembayaran);

    if (!$stmtPembayaran->execute()) {
        throw new Exception("Gagal menyimpan pembayaran: " . $stmtPembayaran->error);
    }

    // Memperbarui status pengiriman
    $stmtPengiriman = $conn->prepare("UPDATE pengiriman SET StatusPengiriman = ?, AlamatTujuan = ?, Kabupaten = ?, Kota = ?, Kecamatan = ?, KodePos = ?, BiayaPengiriman = ?, TanggalPengiriman = ? WHERE UserID = ?");
    $stmtPengiriman->bind_param("ssssssdsi", $StatusPengiriman, $AlamatTujuan, $Kabupaten, $Kota, $Kecamatan, $KodePos, $BiayaPengiriman, $TanggalPengiriman, $UserID);

    if (!$stmtPengiriman->execute()) {
        throw new Exception("Gagal memperbarui pengiriman: " . $stmtPengiriman->error);
    }

    // Commit transaksi jika semua berhasil
    $conn->commit();

    // Redirect ke halaman pembayaran Virtual Account BCA
    header("Location: pembayaran_bca.php?UserID=$UserID&TotalBayar=$TotalBayar");
    exit();
} catch (Exception $e) {
    $conn->rollback(); // Rollback jika terjadi error
    echo "
    <script>
        alert('Terjadi kesalahan: " . $e->getMessage() . "');
        window.history.back();
    </script>";
}

// Menutup koneksi
$conn->close();
?>
