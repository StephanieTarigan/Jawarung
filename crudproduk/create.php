<?php
  include('../service/koneksi.php'); // Menghubungkan ke database
?>

<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Produk</title>
    <style type="text/css">
      * {
        font-family: "Trebuchet MS";
      }
      h1 {
        text-transform: uppercase;
        color: salmon;
      }
      .form-container {
        width: 50%;
        margin: 0 auto;
        padding: 20px;
        border: 1px solid #ddd;
        border-radius: 8px;
        background-color: #f9f9f9;
      }
      label {
        display: block;
        margin: 10px 0 5px;
        font-weight: bold;
      }
      input[type="text"], input[type="number"], textarea {
        width: 100%;
        padding: 8px;
        margin: 5px 0 20px;
        border: 1px solid #ddd;
        border-radius: 4px;
      }
      input[type="file"] {
        margin-top: 10px;
      }
      button {
        background-color: salmon;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
      }
      button:hover {
        background-color: #f57c73;
      }
    </style>
  </head>
  <body>
    <center><h1>Tambah Produk</h1></center>
    <div class="form-container">
      <form action="proses_tambah_produk.php" method="POST" enctype="multipart/form-data">
        <label for="nama">Nama Produk</label>
        <input type="text" id="nama" name="nama" required>

        <label for="harga">Harga Produk</label>
        <input type="number" id="harga" name="harga" required>

        <label for="deskripsi">Deskripsi Produk</label>
        <textarea id="deskripsi" name="deskripsi" rows="5" required></textarea>

        <label for="stok">Stok Produk</label>
        <input type="number" id="stok" name="stok" required>

        <label for="gambar">Gambar Produk</label>
        <input type="file" id="gambar" name="gambar" accept="image/*" required>

        <button type="submit" name="submit">Tambah Produk</button>
      </form>
    </div>
  </body>
</html>
