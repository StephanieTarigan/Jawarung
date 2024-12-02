<?php
  include('../service/koneksi.php'); // Menghubungkan ke database

  if (isset($_POST['submit'])) {
    // Ambil data dari formulir
    $nama_produk = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $harga = mysqli_real_escape_string($koneksi, $_POST['harga']);
    $deskripsi = mysqli_real_escape_string($koneksi, $_POST['deskripsi']);
    $stok = mysqli_real_escape_string($koneksi, $_POST['stok']);
    $foto_produk = $_FILES['gambar']['name'];
    $foto_tmp = $_FILES['gambar']['tmp_name'];

    // Validasi input
    if (empty($nama_produk) || empty($harga) || empty($deskripsi) || empty($stok) || empty($foto_produk)) {
      echo "<script>alert('Semua field harus diisi!'); window.location='create.php';</script>";
      exit();
    }

    // Tentukan lokasi penyimpanan gambar
    $upload_dir = 'uploads/';
    $upload_file = $upload_dir . basename($foto_produk);

    // Periksa izin folder upload
    if (!is_dir($upload_dir)) {
      mkdir($upload_dir, 0755, true);
    }

    // Validasi tipe file gambar
    $allowed_types = array("jpg", "jpeg", "png", "gif");
    $file_extension = strtolower(pathinfo($foto_produk, PATHINFO_EXTENSION));
    if (!in_array($file_extension, $allowed_types)) {
      echo "<script>alert('Format gambar tidak valid!'); window.location='create.php';</script>";
      exit();
    }

    // Proses upload gambar
    if (move_uploaded_file($foto_tmp, $upload_file)) {
      // Query untuk menambah data produk
      $query = "INSERT INTO produk (nama_produk, harga, deskripsi, stok, foto_produk) 
                VALUES ('$nama_produk', '$harga', '$deskripsi', '$stok', '$foto_produk')";
      
      $result = mysqli_query($koneksi, $query);

      if ($result) {
        echo "<script>alert('Produk berhasil ditambahkan!'); window.location='index.php';</script>";
      } else {
        // Debug query
        echo "<script>alert('Gagal menambahkan produk: " . mysqli_error($koneksi) . "'); window.location='create.php';</script>";
      }
    } else {
      // Debug error upload
      echo "<script>alert('Gagal mengupload gambar! Error: " . $_FILES['gambar']['error'] . "'); window.location='create.php';</script>";
    }
  }
?>
