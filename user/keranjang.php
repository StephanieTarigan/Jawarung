<?php
session_start();

// Konfigurasi database
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

// Menangani penambahan produk ke keranjang
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'add') {
    $ProdukID = intval($_POST['ProdukID']);
    $kuantitas = intval($_POST['kuantitas']);

    // Validasi input
    if ($ProdukID > 0 && $kuantitas > 0) {
        // Periksa apakah produk ada di database
        $stmt = $conn->prepare("SELECT NamaProduk, Harga FROM produk WHERE ProdukID = ?");
        $stmt->bind_param("i", $ProdukID);
        $stmt->execute();
        $stmt->bind_result($namaProduk, $harga);
        if ($stmt->fetch()) {
            // Tambahkan produk ke session keranjang
            $_SESSION['keranjang'][] = [
                'ProdukID' => $ProdukID,
                'nama' => $namaProduk,
                'harga' => $harga,
                'kuantitas' => $kuantitas,
            ];
        }
        $stmt->close();
    }
}

// Menangani penghapusan produk dari keranjang
if (isset($_GET['action']) && $_GET['action'] == 'remove') {
    $ProdukID = intval($_GET['ProdukID']);
    if (isset($_SESSION['keranjang'])) {
        foreach ($_SESSION['keranjang'] as $key => $item) {
            if ($item['ProdukID'] == $ProdukID) {
                unset($_SESSION['keranjang'][$key]);
                break;
            }
        }
        // Re-index array setelah penghapusan
        $_SESSION['keranjang'] = array_values($_SESSION['keranjang']);
    }
}

// Mengambil data produk dari database untuk daftar produk
$produk = [];
$result = $conn->query("SELECT ProdukID, NamaProduk, Harga FROM produk");
while ($row = $result->fetch_assoc()) {
    $produk[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang Belanja</title>
    <style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f9f9f9;
        padding: 20px;
    }
    h1, h2 {
        text-align: center;
        color: #34a853; /* Ganti warna judul menjadi hijau */
    }
    .produk, .keranjang {
        max-width: 500px;
        margin: auto;
        background: white;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        margin-bottom: 20px;
    }
    .produk h3, .produk p {
        margin: 5px 0;
    }
    button, a {
        background-color: #34a853; /* Ganti background tombol dan link menjadi hijau */
        color: white;
        border: none;
        padding: 10px;
        border-radius: 5px;
        text-decoration: none;
        display: inline-block;
        cursor: pointer;
        margin-top: 10px;
    }
    button:hover, a:hover {
        background-color: #1e7e34; /* Ganti warna hover menjadi hijau tua */
    }
</style>

</head>
<body>
    <h1>Pilih Produk</h1>
    <div class="produk">
        <form method="POST">
            <?php foreach ($produk as $item): ?>
                <h3><?php echo $item['NamaProduk']; ?></h3>
                <p>Harga: Rp. <?php echo number_format($item['Harga']); ?></p>
                <input type="hidden" name="ProdukID" value="<?php echo $item['ProdukID']; ?>">
                <label for="kuantitas_<?php echo $item['ProdukID']; ?>">Kuantitas:</label>
                <input type="number" name="kuantitas" id="kuantitas_<?php echo $item['ProdukID']; ?>" min="1" value="1" required>
                <input type="hidden" name="action" value="add">
                <button type="submit">Tambah ke Keranjang</button>
            <?php endforeach; ?>
        </form>
    </div>

    <h2>Keranjang Belanja</h2>
    <div class="keranjang">
        <ul>
            <?php
            $total = 0;
            if (isset($_SESSION['keranjang']) && !empty($_SESSION['keranjang'])) {
                foreach ($_SESSION['keranjang'] as $item) {
                    $subtotal = $item['harga'] * $item['kuantitas'];
                    $total += $subtotal;
                    echo "<li>
                        {$item['nama']} - Kuantitas: {$item['kuantitas']} - Total: Rp. " . number_format($subtotal) . "
                        <a href='?action=remove&ProdukID={$item['ProdukID']}' style='color: red;'>Hapus</a>
                    </li>";
                }
            } else {
                echo "<p>Keranjang kosong</p>";
            }
            ?>
        </ul>
        <h3>Total: Rp. <?php echo number_format($total); ?></h3>
        <a href="pembayaran.php">Lanjutkan ke Pembayaran</a>
    </div>
</body>
</html>
