<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Transaksi</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f8f8f8;
        }
        .container {
            max-width: 600px;
            margin: auto;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input, select, button {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        button {
            background-color: #4a7345;
            color: #fff;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Form Transaksi</h1>
        <form action="proses_transaksi.php" method="POST">
            <label for="nama_pembeli">Nama Pembeli:</label>
            <input type="text" id="nama_pembeli" name="nama_pembeli" required>

            <label for="alamat_pengiriman">Alamat Pengiriman:</label>
            <input type="text" id="alamat_pengiriman" name="alamat_pengiriman" required>

            <label for="no_telepon">Nomor Telepon:</label>
            <input type="text" id="no_telepon" name="no_telepon" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>

            <label for="metode_pembayaran">Metode Pembayaran:</label>
            <select id="metode_pembayaran" name="metode_pembayaran" required>
                <option value="Transfer Bank">Transfer Bank</option>
                <option value="Kartu Kredit">Kartu Kredit</option>
                <option value="COD">Cash On Delivery</option>
            </select>

            <label for="total_harga">Total Harga (Rp):</label>
            <input type="number" id="total_harga" name="total_harga" required>

            <button type="submit">Proses Transaksi</button>
        </form>
    </div>
</body>
</html>
