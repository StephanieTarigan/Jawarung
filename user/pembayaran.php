<?php
session_start();

// Inisialisasi total bayar
$totalBayar = 0;

// Periksa jika keranjang ada di sesi
if (isset($_SESSION['keranjang']) && !empty($_SESSION['keranjang'])) {
    foreach ($_SESSION['keranjang'] as $item) {
        $ProdukID = $item['ProdukID'];
        $kuantitas = $item['kuantitas'];
        $harga = 0;

        // Data produk (contoh, lebih baik ambil dari database)
        if ($ProdukID == 1) $harga = 15000;
        if ($ProdukID == 2) $harga = 5000;
        if ($ProdukID == 3) $harga = 45000;

        $totalBayar += $harga * $kuantitas;
    }
}

// Simpan total harga ke sesi untuk penggunaan lebih lanjut
$_SESSION['total_bayar'] = $totalBayar;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran</title>
    <style>
      body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            padding: 20px;
        }
        form {
            max-width: 500px;
            margin: auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            color: #34a853;
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #34a853;
        }
        input, select, button {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        button {
            background-color: #34a853;
            color: white;
            font-size: 16px;
            cursor: pointer;
        }
        button:hover {
            background-color: #1e7e34;
        }
</style>

</head>
<body>
    <h1>Form Pembayaran</h1>
    <form method="POST" action="proses_pembayaran.php">
        <h3>Total Harga: Rp. <?php echo number_format($totalBayar); ?></h3>
        <input type="hidden" name="TotalBayar" value="<?php echo $totalBayar; ?>">

        <label for="MetodePembayaran">Metode Pembayaran:</label>
        <select name="MetodePembayaran" id="MetodePembayaran" required>
            <option value="Transfer Bank">Transfer Bank</option>
            <option value="Kartu Kredit">Kartu Kredit</option>
            <option value="E-Wallet">E-Wallet</option>
        </select>

        <label for="AlamatTujuan">Alamat Tujuan:</label>
        <input type="text" id="AlamatTujuan" name="AlamatTujuan" required>

        <label for="Kabupaten">Kabupaten:</label>
        <input type="text" id="Kabupaten" name="Kabupaten">

        <label for="Kota">Kota:</label>
        <input type="text" id="Kota" name="Kota">

        <label for="Kecamatan">Kecamatan:</label>
        <input type="text" id="Kecamatan" name="Kecamatan" required>

        <label for="KodePos">Kode Pos:</label>
        <input type="text" id="KodePos" name="KodePos" required>

        <label for="BiayaPengiriman">Biaya Pengiriman:</label>
        <select id="BiayaPengiriman" name="BiayaPengiriman" required>
            <option value="10000">Rp 10,000</option>
            <option value="15000">Rp 15,000</option>
        </select>

        <button type="submit">Lanjutkan Pembayaran</button>
    </form>
</body>
</html>