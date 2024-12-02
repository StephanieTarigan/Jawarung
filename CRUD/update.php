<?php
// Memanggil file koneksi.php untuk melakukan koneksi ke database
include('../service/koneksi.php'); // Menghubungkan ke database

// Mengecek apakah ada nilai 'id' pada URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Query untuk mengambil data produk berdasarkan ID
    $query = "SELECT * FROM resep WHERE id = '$id'";
    $result = mysqli_query($koneksi, $query);

    // Memeriksa apakah data ditemukan
    if ($result) {
        $data = mysqli_fetch_assoc($result);
    } else {
        // Jika data tidak ditemukan
        echo "<script>alert('Data produk tidak ditemukan.');window.location='view_dataresep.php';</script>";
    }
} else {
    // Jika ID tidak ditemukan di URL
    echo "<script>alert('ID produk tidak ditemukan.');window.location='view_dataresep.php';</script>";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Produk</title>
    <style type="text/css">
        * {
            font-family: "Trebuchet MS";
        }
        h1 {
            text-transform: uppercase;
            color: salmon;
        }
        button {
          background-color: salmon;
          color: #fff;
          padding: 10px;
          text-decoration: none;
          font-size: 12px;
          border: 0px;
          margin-top: 20px;
    }
        label {
            margin-top: 10px;
            float: left;
            text-align: left;
            width: 100%;
        }
        input {
            padding: 6px;
            width: 100%;
            box-sizing: border-box;
            background: #f8f8f8;
            border: 2px solid #ccc;
            outline-color: salmon;
        }
        div {
            width: 100%;
            height: auto;
        }
        .base {
            width: 400px;
            height: auto;
            padding: 20px;
            margin-left: auto;
            margin-right: auto;
            background: #ededed;
        }
    </style>
</head>
<body>
    <center>
        <h1>Edit Produk: <?php echo $data['nama']; ?></h1>
    </center>
    <form method="POST" action="proses_update.php" enctype="multipart/form-data">
        <section class="base">
            <!-- Menyimpan ID produk dalam input tersembunyi -->
            <input name="id" value="<?php echo $data['id']; ?>" type="hidden" />
            
            <div>
                <label>Nama Produk</label>
                <input type="text" name="nama" value="<?php echo $data['nama']; ?>" autofocus required />
            </div>
            
            <div>
                <label>Deskripsi</label>
                <input type="text" name="deskripsi" value="<?php echo $data['deskripsi']; ?>" required />
            </div>
            
            <div>
                <label>Waktu</label>
                <input type="time" name="waktu" value="<?php echo $data['waktu']; ?>" required />
            </div>
            
            <div>
                <label>Porsi</label>
                <input type="text" name="porsi" value="<?php echo $data['porsi']; ?>" required />
            </div>

            <div>
                <label>Gambar Produk</label>
                <img src="gambar/<?php echo $data['gambar']; ?>" style="width: 120px; float: left; margin-bottom: 5px;">
                <input type="file" name="gambar" />
                <i style="float: left; font-size: 11px; color: red;">Abaikan jika tidak ingin mengubah gambar produk.</i>
            </div>

            <div>
                <button type="submit">Simpan Perubahan</button>
            </div>
        </section>
    </form>
</body>
</html>
