<?php
  include('../service/koneksi.php'); // Menghubungkan ke database
?>

<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Produk</title>
    <style type="text/css">
      * {
        font-family: "Trebuchet MS";
      }
      h1 {
        text-transform: uppercase;
        color: salmon;
      }
      table {
        border: solid 1px #DDEEEE;
        border-collapse: collapse;
        border-spacing: 0;
        width: 70%;
        margin: 10px auto 10px auto;
      }
      table thead th {
        background-color: #DDEFEF;
        border: solid 1px #DDEEEE;
        color: #336B6B;
        padding: 10px;
        text-align: left;
        text-shadow: 1px 1px 1px #fff;
      }
      table tbody td {
        border: solid 1px #DDEEEE;
        color: #333;
        padding: 10px;
        text-shadow: 1px 1px 1px #fff;
      }
      a {
        background-color: salmon;
        color: #fff;
        padding: 10px;
        text-decoration: none;
        font-size: 12px;
      }
      .gambar {
        width: 120px;
        height: auto;
      }
    </style>
  </head>
  <body>
    <center><h1>Data Produk</h1></center>
    <center><a href="create.php">+ &nbsp; Tambah Produk</a></center>
    <br/>
    <table>
      <thead>
        <tr>
          <th>No</th>
          <th>Nama Produk</th>
          <th>Harga</th>
          <th>Deskripsi</th>
          <th>Stok</th>
          <th>Gambar</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php
          // query untuk menampilkan semua data produk
          $query = "SELECT * FROM produk ORDER BY id ASC";
          $result = mysqli_query($koneksi, $query);

          // dicek apakah ada error saat menjalankan query
          if (!$result) {
            die("Query Error: ".mysqli_errno($koneksi)." - ".mysqli_error($koneksi));
          }

          // Buat perulangan untuk elemen tabel dari data produk
          $no = 1; // Variabel untuk nomor urut
          while ($row = mysqli_fetch_assoc($result)) {
        ?>
          <tr>
            <td><?php echo $no; ?></td>
            <td><?php echo htmlspecialchars($row['nama_produk']); ?></td>
            <td>Rp <?php echo number_format($row['harga'], 2, ',', '.'); ?></td>
            <td><?php echo substr(htmlspecialchars($row['deskripsi']), 0, 50); ?>...</td>
            <td><?php echo htmlspecialchars($row['stok']); ?></td>
            <td style="text-align: center;">
              <img src="uploads/<?php echo htmlspecialchars($row['foto_produk']); ?>" class="gambar" alt="gambar produk">
            </td>
            <td>
              <a href="update.php?id=<?php echo $row['id']; ?>">Edit</a> |
              <a href="delete.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Anda yakin akan menghapus data ini?')">Hapus</a>
            </td>
          </tr>
        <?php
            $no++; // Menambah nomor urut
          }
        ?>
      </tbody>
    </table>
  </body>
</html>
