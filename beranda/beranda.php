<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Beranda</title>
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="styleview.css">
</head>
<?php include "../template/main_header.php"; ?>

<body>

  <a href="../CRUD/Dasboard.php">
    <button type="button" class="button1" id="btnLogin">TAMBAH RESEP</button>
  </a>

  <a href="../crudproduk/Dashboard.php">
    <button type="button" class="button1" id="btnLogin">TAMBAH PRODUK</button>
  </a>
  <!-- <a href="../crudproduk/create.php">+ &nbsp; Tambah Produk
</a> -->


  <div class="container">
    <?php
    include('../service/koneksi.php');
    // Jalankan query untuk menampilkan semua data produk
    $query = "SELECT * FROM resep ORDER BY id ASC";
    $result = mysqli_query($koneksi, $query);

    // Mengecek apakah ada error saat menjalankan query
    if (!$result) {
      die("Query Error: " . mysqli_errno($koneksi) . " - " . mysqli_error($koneksi));
    }

    // Buat perulangan untuk setiap data resep
    while ($row = mysqli_fetch_assoc($result)) {
    ?>
      <div class="card">
        <img src="../crud/uploads/<?php echo htmlspecialchars($row['gambar']); ?>" alt="Gambar Resep">
        <h3><?php echo htmlspecialchars($row['nama']); ?></h3>
        <p><?php echo substr(htmlspecialchars($row['deskripsi']), 0, 50); ?>...</p>
        <div class="info">
          <span>⏱️ <?php echo htmlspecialchars(substr($row['waktu'], 0, 5), ENT_QUOTES); ?> menit</span>
          <span>👥 <?php echo htmlspecialchars($row['porsi']); ?> Porsi</span>
        </div>
        <a href="detail.php?id=<?php echo $row['id']; ?>">Selengkapnya</a>
      </div>
    <?php
    }
    ?>
  </div>
</body>


<!-- section produk -->
<div class="container">
  <?php
  include('../service/koneksi.php');
  // Jalankan query untuk menampilkan semua data produk
  $query = "SELECT * FROM produk ORDER BY id ASC";
  $result = mysqli_query($koneksi, $query);

  // Mengecek apakah ada error saat menjalankan query
  if (!$result) {
    die("Query Error: " . mysqli_errno($koneksi) . " - " . mysqli_error($koneksi));
  }

  // Buat perulangan untuk setiap data produk
  while ($row = mysqli_fetch_assoc($result)) {
  ?>
    <div class="card">
      <img src="../crudproduk/uploads/<?php echo htmlspecialchars($row['foto_produk']); ?>" alt="Gambar Produk"> <!-- Perbaiki nama kolom gambar -->
      <h3><?php echo htmlspecialchars($row['nama_produk']); ?></h3> <!-- Sesuaikan nama kolom -->
      <p><?php echo substr(htmlspecialchars($row['deskripsi']), 0, 50); ?>...</p>
      <a href="detail.php?id=<?php echo $row['id']; ?>">BELI</a>
    </div>
  <?php
  }
  ?>
</div>
</body>
<?php include "../template/main_footer.php"; ?>

</html>