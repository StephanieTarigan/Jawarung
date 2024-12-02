<?php include "../template/main_header.php"; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../template/style.css">
    <style>
        .sidebar {
            width: 250px; /* Lebar sidebar */
            height: 100vh; /* Tinggi sidebar sama dengan tinggi viewport */
            position: fixed; /* Menjadikan elemen tetap di sisi kiri */
            top: 0;
            left: 0;
            margin-top: 142px;
            background-color: #f9f9f9; /* Warna latar belakang */
            border-right: 1px solid #ddd; /* Border di sisi kanan */
            padding: 15px;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1); /* Bayangan untuk sidebar */
            overflow-y: auto; /* Tambahkan scroll jika konten terlalu panjang */
            font-family: Arial, sans-serif;
            z-index: 1000; /* Pastikan sidebar berada di atas elemen lain */
        }

        .sidebar h3 {
            font-size: 18px;
            margin-bottom: 10px;
            font-weight: bold;
        }

        .accordion {
            background-color: #f9f9f9;
            color: #333;
            cursor: pointer;
            padding: 10px;
            text-align: left;
            border: none;
            outline: none;
            font-size: 16px;
            width: 100%;
            margin: 5px 0;
            transition: 0.4s;
            border: 1px solid #ddd;
            border-radius: 3px;
        }

        .accordion:hover {
            background-color: #eee;
        }

        .accordion.active {
            background-color: #ccc;
        }

        .panel {
            display: none;
            background-color: #fff;
            overflow: hidden;
            padding: 10px 0;
            border-left: 1px solid #ddd;
            border-right: 1px solid #ddd;
            border-bottom: 1px solid #ddd;
        }

        .panel label {
            display: block;
            margin: 5px 0;
            font-size: 14px;
        }

        input[type="checkbox"] {
            margin-right: 10px;
        }
    </style>
</head>

<body>
    
<div class="sidebar">
    <h3>Kategori Produk</h3>
    <div class="kategori">
        <button class="accordion">Bahan Segar</button>
        <div class="panel">
            <label><input type="checkbox"> Ikan</label>
            <label><input type="checkbox"> Daging</label>
            <label><input type="checkbox"> Buah-buahan</label>
            <label><input type="checkbox"> Sayuran</label>
            <label><input type="checkbox"> Susu, Mentega, Telur</label>
        </div>

        <button class="accordion">Bahan Kering</button>
        <div class="panel">
            <label><input type="checkbox"> Beras</label>
            <label><input type="checkbox"> Kacang-kacangan</label>
            <label><input type="checkbox"> Gandum</label>
            <label><input type="checkbox"> Rempah Kering</label>
            <label><input type="checkbox"> Mi dan Pasta</label>
            <label><input type="checkbox"> Tepung dan Produk Tepung</label>
        </div>

        <button class="accordion">Bahan Cair</button>
        <div class="panel">
            <label><input type="checkbox"> Sayuran Kalengan</label>
            <label><input type="checkbox"> Daging dan Ikan Kalengan</label>
            <label><input type="checkbox"> Buah Kering</label>
            <label><input type="checkbox"> Daging Asap dan Kering</label>
        </div>

        <button class="accordion">Bumbu dan Penyedap</button>
        <div class="panel">
            <label><input type="checkbox"> Bumbu Dasar</label>
        </div>
    </div>
</div>

<script>
    document.querySelectorAll(".accordion").forEach((accordion) => {
        accordion.addEventListener("click", function () {
            this.classList.toggle("active");
            const panel = this.nextElementSibling;
            if (panel.style.display === "block") {
                panel.style.display = "none";
            } else {
                panel.style.display = "block";
            }
        });
    });
</script>

</body>
</html>