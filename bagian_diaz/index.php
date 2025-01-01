<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Pembayaran & Pengiriman</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #e8f5e9; /* Warna hijau muda */
            color: #1b5e20; /* Warna hijau gelap untuk teks */
            margin: 0;
            padding: 20px;
        }
        h1 {
            color: #2e7d32; /* Warna hijau untuk judul */
            text-align: center;
        }
        form {
            background-color: #ffffff; /* Warna putih untuk latar form */
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            margin: 20px auto;
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
            border: 1px solid #2e7d32;
            border-radius: 5px;
            box-sizing: border-box;
        }
        input:focus, select:focus {
            border-color: #66bb6a; /* Warna hijau lebih terang saat fokus */
            outline: none;
        }
        button {
            background-color: #2e7d32; /* Warna hijau untuk tombol */
            color: white;
            border: none;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover {
            background-color: #66bb6a; /* Warna hijau lebih terang untuk hover */
        }
    </style>

    <script>
         function validateForm() {
            const kabupaten = document.getElementById("kabupaten").value.trim();
            const kota = document.getElementById("kota").value.trim();

            if (!kabupaten && !kota) {
                alert("Harap isi salah satu antara Kabupaten atau Kota.");
                return false; // Menghentikan pengiriman formulir
            }

            return true; // Melanjutkan pengiriman formulir
        }
    </script>
</head>
<body>
<h1>Form Pembayaran dan pengiriman</h1>
<form action="prosess.php" method="POST">
        <label for="Userid">UserID:</label>
        <input type="text" id="Userid" name="Userid" required><br><br>

        <label for="TotalBayar">Total Bayar:</label>
        <input type="number" id="TotalBayar" name="TotalBayar" required><br><br>

        <label for="MetodePembayaran">Metode Pembayaran:</label>
        <select id="MetodePembayaran" name="MetodePembayaran" required>
            <option value="Transfer Bank">Transfer Bank</option>
            <option value="COD">COD</option>
            <option value="E-Wallet">E-Wallet</option>
        </select><br><br>

        <label for="AlamatTujuan">Alamat Tujuan:</label>
        <input type="text" id="AlamatTujuan" name="AlamatTujuan" required><br><br>

        <label for="Kabupaten">Kabupaten (Opsional):</label>
        <input type="text" id="Kabupaten" name="Kabupaten"><br><br>

        <label for="Kota">Kota (Opsional):</label>
        <input type="text" id="Kota" name="Kota"><br><br>

        <label for="Kecamatan">Kecamatan:</label>
        <input type="text" id="Kecamatan" name="Kecamatan" required><br><br>

        <label for="KodePos">Kode Pos:</label>
        <input type="number" id="KodePos" name="KodePos" required><br><br>

        <label for="BiayaPengiriman">Biaya Pengiriman:</label>
        <input type="number" id="BiayaPengiriman" name="BiayaPengiriman" required><br><br>

        <button type="submit">Bayar</button>
    </form>
</body>
</html>
    </form>
</body>
</html>
