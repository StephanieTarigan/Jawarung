-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 05, 2024 at 05:19 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `highdsd`
--

-- --------------------------------------------------------

--
-- Table structure for table `bahanbaku`
--

CREATE TABLE `bahanbaku` (
  `BahanBakuID` int(11) NOT NULL,
  `NamaBahanBaku` varchar(100) NOT NULL,
  `Harga` decimal(10,2) DEFAULT NULL,
  `SatuanID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `detailpesanan`
--

CREATE TABLE `detailpesanan` (
  `DetailPesananID` int(11) NOT NULL,
  `PesananID` int(11) NOT NULL,
  `ProdukID` int(11) NOT NULL,
  `Kuantitas` int(11) NOT NULL,
  `Harga` decimal(10,2) NOT NULL,
  `Subtotal` decimal(10,2) GENERATED ALWAYS AS (`Kuantitas` * `Harga`) STORED
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `fotoproduk`
--

CREATE TABLE `fotoproduk` (
  `FotoID` int(11) NOT NULL,
  `ProdukID` int(11) NOT NULL,
  `FotoPath` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `fotoproduk`
--

INSERT INTO `fotoproduk` (`FotoID`, `ProdukID`, `FotoPath`) VALUES
(1, 1, 'uploads/foto_produk/67511f5c9551b.jpg'),
(2, 2, 'uploads/foto_produk/6751207220da2.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `fotoresep`
--

CREATE TABLE `fotoresep` (
  `FotoID` int(11) NOT NULL,
  `ResepID` int(11) NOT NULL,
  `FotoPath` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kategoriresep`
--

CREATE TABLE `kategoriresep` (
  `KategoriResepID` int(11) NOT NULL,
  `TipeKategori` enum('Waktu Memasak','Perkiraan Harga','Jenis Hidangan','Asal Masakan') NOT NULL,
  `NamaKategori` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `keranjang`
--

CREATE TABLE `keranjang` (
  `KeranjangID` int(11) NOT NULL,
  `UserID` int(11) NOT NULL,
  `ProdukID` int(11) NOT NULL,
  `Kuantitas` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `komentar`
--

CREATE TABLE `komentar` (
  `KomentarID` int(11) NOT NULL,
  `ProdukID` int(11) DEFAULT NULL,
  `ResepID` int(11) DEFAULT NULL,
  `UserID` int(11) NOT NULL,
  `Rating` int(11) DEFAULT NULL CHECK (`Rating` between 1 and 5),
  `KomentarText` text DEFAULT NULL,
  `FotoKomentar` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `langkahmemasak`
--

CREATE TABLE `langkahmemasak` (
  `LangkahID` int(11) NOT NULL,
  `ResepID` int(11) NOT NULL,
  `NomorLangkah` int(11) NOT NULL,
  `DeskripsiLangkah` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pelanggan`
--

CREATE TABLE `pelanggan` (
  `PelangganID` int(11) NOT NULL,
  `Nama` varchar(100) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `Alamat` text DEFAULT NULL,
  `NoTelepon` varchar(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pembayaran`
--

CREATE TABLE `pembayaran` (
  `PembayaranID` int(11) NOT NULL,
  `UserID` int(11) NOT NULL,
  `TotalBayar` decimal(10,2) NOT NULL,
  `TanggalPembayaran` date NOT NULL,
  `MetodePembayaran` enum('Transfer Bank','E-Wallet','Kartu Kredit') NOT NULL,
  `StatusPembayaran` enum('Belum Dibayar','Sudah Dibayar') DEFAULT 'Belum Dibayar'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pengiriman`
--

CREATE TABLE `pengiriman` (
  `PengirimanID` int(11) NOT NULL,
  `UserID` int(11) NOT NULL,
  `AlamatTujuan` text NOT NULL,
  `Kabupaten` varchar(50) DEFAULT NULL,
  `Kota` varchar(50) DEFAULT NULL,
  `Kecamatan` varchar(50) DEFAULT NULL,
  `KodePos` varchar(10) DEFAULT NULL,
  `TanggalPengiriman` date NOT NULL,
  `StatusPengiriman` enum('Diproses','Dalam Pengiriman','Terkirim') DEFAULT 'Diproses',
  `BiayaPengiriman` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pesanan`
--

CREATE TABLE `pesanan` (
  `PesananID` int(11) NOT NULL,
  `PelangganID` int(11) NOT NULL,
  `PengirimanID` int(11) NOT NULL,
  `PembayaranID` int(11) NOT NULL,
  `TanggalPesanan` date NOT NULL,
  `StatusPesanan` enum('Diproses','Dikirim','Selesai') DEFAULT 'Diproses',
  `TotalPesanan` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `produk`
--

CREATE TABLE `produk` (
  `ProdukID` int(11) NOT NULL,
  `NamaProduk` varchar(100) NOT NULL,
  `DeskripsiProduk` text DEFAULT NULL,
  `Harga` decimal(10,2) NOT NULL,
  `WarungID` int(11) NOT NULL,
  `SatuanID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `produk`
--

INSERT INTO `produk` (`ProdukID`, `NamaProduk`, `DeskripsiProduk`, `Harga`, `WarungID`, `SatuanID`) VALUES
(1, 'gula', 'karbohidrat sederhana yang menjadi sumber energi dan komoditas perdagangan utama.', 22000.00, 2, NULL),
(2, 'bayam', 'sayuran hijau', 5000.00, 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `resep`
--

CREATE TABLE `resep` (
  `ResepID` int(11) NOT NULL,
  `NamaResep` varchar(100) NOT NULL,
  `DeskripsiResep` text DEFAULT NULL,
  `KategoriResepID` int(11) NOT NULL,
  `WaktuMemasakID` int(11) DEFAULT NULL,
  `PerkiraanHargaID` int(11) DEFAULT NULL,
  `JenisHidanganID` int(11) DEFAULT NULL,
  `AsalMasakanID` int(11) DEFAULT NULL,
  `LinkVideoTutorial` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `resepbahan`
--

CREATE TABLE `resepbahan` (
  `ResepBahanID` int(11) NOT NULL,
  `ResepID` int(11) NOT NULL,
  `BahanBakuID` int(11) NOT NULL,
  `JumlahBahan` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `satuan`
--

CREATE TABLE `satuan` (
  `SatuanID` int(11) NOT NULL,
  `NamaSatuan` varchar(50) NOT NULL,
  `DeskripsiSatuan` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `satuan`
--

INSERT INTO `satuan` (`SatuanID`, `NamaSatuan`, `DeskripsiSatuan`) VALUES
(1, 'Gram', 'Berat bahan dalam gram'),
(2, 'Kg', 'Berat bahan dalam kilogram'),
(3, 'Liter', 'Volume bahan dalam liter'),
(4, 'Sendok', 'Jumlah bahan dalam satuan sendok'),
(5, 'Porsi', 'Jumlah bahan per porsi');

-- --------------------------------------------------------

--
-- Table structure for table `subkategori`
--

CREATE TABLE `subkategori` (
  `SubkategoriID` int(11) NOT NULL,
  `KategoriResepID` int(11) NOT NULL,
  `NamaSubkategori` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `UserID` int(11) NOT NULL,
  `Username` varchar(100) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `Alamat` text DEFAULT NULL,
  `Kabupaten` varchar(50) DEFAULT NULL,
  `Kota` varchar(50) DEFAULT NULL,
  `Kecamatan` varchar(50) DEFAULT NULL,
  `KodePos` varchar(10) DEFAULT NULL,
  `NoTelepon` varchar(15) NOT NULL,
  `Role` enum('admin','pelanggan') NOT NULL,
  `ProfilePicture` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`UserID`, `Username`, `Email`, `Password`, `Alamat`, `Kabupaten`, `Kota`, `Kecamatan`, `KodePos`, `NoTelepon`, `Role`, `ProfilePicture`) VALUES
(1, 'admin', 'admin@gmail.com', '$2y$10$ULp0W84OYc8LmS3v57iArOQ9nsPh28ZwBWHDtumLMH0nKSL8wraoO', 'Jl. Mitra Sehati No 4', 'Bandung', 'Bandung', 'Cileunyi', '23456', '', 'admin', 'uploads/profile_pics/675126d7d10799.79709731.jpeg');

-- --------------------------------------------------------

--
-- Table structure for table `warung`
--

CREATE TABLE `warung` (
  `WarungID` int(11) NOT NULL,
  `NamaWarung` varchar(100) NOT NULL,
  `AlamatWarung` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `warung`
--

INSERT INTO `warung` (`WarungID`, `NamaWarung`, `AlamatWarung`) VALUES
(1, 'Warung Pak Slamet', 'Jl. Raya Pasar No. 12'),
(2, 'Warung Bu Nani', 'Jl. Kebon Jeruk No. 5');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bahanbaku`
--
ALTER TABLE `bahanbaku`
  ADD PRIMARY KEY (`BahanBakuID`),
  ADD KEY `SatuanID` (`SatuanID`);

--
-- Indexes for table `detailpesanan`
--
ALTER TABLE `detailpesanan`
  ADD PRIMARY KEY (`DetailPesananID`),
  ADD KEY `PesananID` (`PesananID`),
  ADD KEY `ProdukID` (`ProdukID`);

--
-- Indexes for table `fotoproduk`
--
ALTER TABLE `fotoproduk`
  ADD PRIMARY KEY (`FotoID`),
  ADD KEY `ProdukID` (`ProdukID`);

--
-- Indexes for table `fotoresep`
--
ALTER TABLE `fotoresep`
  ADD PRIMARY KEY (`FotoID`),
  ADD KEY `ResepID` (`ResepID`);

--
-- Indexes for table `kategoriresep`
--
ALTER TABLE `kategoriresep`
  ADD PRIMARY KEY (`KategoriResepID`);

--
-- Indexes for table `keranjang`
--
ALTER TABLE `keranjang`
  ADD PRIMARY KEY (`KeranjangID`),
  ADD KEY `UserID` (`UserID`),
  ADD KEY `ProdukID` (`ProdukID`);

--
-- Indexes for table `komentar`
--
ALTER TABLE `komentar`
  ADD PRIMARY KEY (`KomentarID`),
  ADD KEY `ProdukID` (`ProdukID`),
  ADD KEY `ResepID` (`ResepID`),
  ADD KEY `UserID` (`UserID`);

--
-- Indexes for table `langkahmemasak`
--
ALTER TABLE `langkahmemasak`
  ADD PRIMARY KEY (`LangkahID`),
  ADD KEY `ResepID` (`ResepID`);

--
-- Indexes for table `pelanggan`
--
ALTER TABLE `pelanggan`
  ADD PRIMARY KEY (`PelangganID`);

--
-- Indexes for table `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD PRIMARY KEY (`PembayaranID`),
  ADD KEY `UserID` (`UserID`);

--
-- Indexes for table `pengiriman`
--
ALTER TABLE `pengiriman`
  ADD PRIMARY KEY (`PengirimanID`),
  ADD KEY `UserID` (`UserID`);

--
-- Indexes for table `pesanan`
--
ALTER TABLE `pesanan`
  ADD PRIMARY KEY (`PesananID`),
  ADD KEY `PelangganID` (`PelangganID`),
  ADD KEY `PengirimanID` (`PengirimanID`),
  ADD KEY `PembayaranID` (`PembayaranID`);

--
-- Indexes for table `produk`
--
ALTER TABLE `produk`
  ADD PRIMARY KEY (`ProdukID`),
  ADD KEY `WarungID` (`WarungID`),
  ADD KEY `SatuanID` (`SatuanID`);

--
-- Indexes for table `resep`
--
ALTER TABLE `resep`
  ADD PRIMARY KEY (`ResepID`),
  ADD KEY `KategoriResepID` (`KategoriResepID`),
  ADD KEY `WaktuMemasakID` (`WaktuMemasakID`),
  ADD KEY `PerkiraanHargaID` (`PerkiraanHargaID`),
  ADD KEY `JenisHidanganID` (`JenisHidanganID`),
  ADD KEY `AsalMasakanID` (`AsalMasakanID`);

--
-- Indexes for table `resepbahan`
--
ALTER TABLE `resepbahan`
  ADD PRIMARY KEY (`ResepBahanID`),
  ADD KEY `ResepID` (`ResepID`),
  ADD KEY `BahanBakuID` (`BahanBakuID`);

--
-- Indexes for table `satuan`
--
ALTER TABLE `satuan`
  ADD PRIMARY KEY (`SatuanID`);

--
-- Indexes for table `subkategori`
--
ALTER TABLE `subkategori`
  ADD PRIMARY KEY (`SubkategoriID`),
  ADD KEY `KategoriResepID` (`KategoriResepID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`UserID`),
  ADD UNIQUE KEY `Email` (`Email`);

--
-- Indexes for table `warung`
--
ALTER TABLE `warung`
  ADD PRIMARY KEY (`WarungID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bahanbaku`
--
ALTER TABLE `bahanbaku`
  MODIFY `BahanBakuID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `detailpesanan`
--
ALTER TABLE `detailpesanan`
  MODIFY `DetailPesananID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fotoproduk`
--
ALTER TABLE `fotoproduk`
  MODIFY `FotoID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `fotoresep`
--
ALTER TABLE `fotoresep`
  MODIFY `FotoID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kategoriresep`
--
ALTER TABLE `kategoriresep`
  MODIFY `KategoriResepID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `keranjang`
--
ALTER TABLE `keranjang`
  MODIFY `KeranjangID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `komentar`
--
ALTER TABLE `komentar`
  MODIFY `KomentarID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `langkahmemasak`
--
ALTER TABLE `langkahmemasak`
  MODIFY `LangkahID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pelanggan`
--
ALTER TABLE `pelanggan`
  MODIFY `PelangganID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pembayaran`
--
ALTER TABLE `pembayaran`
  MODIFY `PembayaranID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pengiriman`
--
ALTER TABLE `pengiriman`
  MODIFY `PengirimanID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pesanan`
--
ALTER TABLE `pesanan`
  MODIFY `PesananID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `produk`
--
ALTER TABLE `produk`
  MODIFY `ProdukID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `resep`
--
ALTER TABLE `resep`
  MODIFY `ResepID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `resepbahan`
--
ALTER TABLE `resepbahan`
  MODIFY `ResepBahanID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `satuan`
--
ALTER TABLE `satuan`
  MODIFY `SatuanID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `subkategori`
--
ALTER TABLE `subkategori`
  MODIFY `SubkategoriID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `UserID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `warung`
--
ALTER TABLE `warung`
  MODIFY `WarungID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bahanbaku`
--
ALTER TABLE `bahanbaku`
  ADD CONSTRAINT `bahanbaku_ibfk_1` FOREIGN KEY (`SatuanID`) REFERENCES `satuan` (`SatuanID`) ON DELETE CASCADE;

--
-- Constraints for table `detailpesanan`
--
ALTER TABLE `detailpesanan`
  ADD CONSTRAINT `detailpesanan_ibfk_1` FOREIGN KEY (`PesananID`) REFERENCES `pesanan` (`PesananID`) ON DELETE CASCADE,
  ADD CONSTRAINT `detailpesanan_ibfk_2` FOREIGN KEY (`ProdukID`) REFERENCES `produk` (`ProdukID`) ON DELETE CASCADE;

--
-- Constraints for table `fotoproduk`
--
ALTER TABLE `fotoproduk`
  ADD CONSTRAINT `fotoproduk_ibfk_1` FOREIGN KEY (`ProdukID`) REFERENCES `produk` (`ProdukID`) ON DELETE CASCADE;

--
-- Constraints for table `fotoresep`
--
ALTER TABLE `fotoresep`
  ADD CONSTRAINT `fotoresep_ibfk_1` FOREIGN KEY (`ResepID`) REFERENCES `resep` (`ResepID`) ON DELETE CASCADE;

--
-- Constraints for table `keranjang`
--
ALTER TABLE `keranjang`
  ADD CONSTRAINT `keranjang_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `users` (`UserID`) ON DELETE CASCADE,
  ADD CONSTRAINT `keranjang_ibfk_2` FOREIGN KEY (`ProdukID`) REFERENCES `produk` (`ProdukID`) ON DELETE CASCADE;

--
-- Constraints for table `komentar`
--
ALTER TABLE `komentar`
  ADD CONSTRAINT `komentar_ibfk_1` FOREIGN KEY (`ProdukID`) REFERENCES `produk` (`ProdukID`) ON DELETE SET NULL,
  ADD CONSTRAINT `komentar_ibfk_2` FOREIGN KEY (`ResepID`) REFERENCES `resep` (`ResepID`) ON DELETE SET NULL,
  ADD CONSTRAINT `komentar_ibfk_3` FOREIGN KEY (`UserID`) REFERENCES `users` (`UserID`) ON DELETE CASCADE;

--
-- Constraints for table `langkahmemasak`
--
ALTER TABLE `langkahmemasak`
  ADD CONSTRAINT `langkahmemasak_ibfk_1` FOREIGN KEY (`ResepID`) REFERENCES `resep` (`ResepID`) ON DELETE CASCADE;

--
-- Constraints for table `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD CONSTRAINT `pembayaran_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `users` (`UserID`) ON DELETE CASCADE;

--
-- Constraints for table `pengiriman`
--
ALTER TABLE `pengiriman`
  ADD CONSTRAINT `pengiriman_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `users` (`UserID`) ON DELETE CASCADE;

--
-- Constraints for table `pesanan`
--
ALTER TABLE `pesanan`
  ADD CONSTRAINT `pesanan_ibfk_1` FOREIGN KEY (`PelangganID`) REFERENCES `pelanggan` (`PelangganID`) ON DELETE CASCADE,
  ADD CONSTRAINT `pesanan_ibfk_2` FOREIGN KEY (`PengirimanID`) REFERENCES `pengiriman` (`PengirimanID`) ON DELETE CASCADE,
  ADD CONSTRAINT `pesanan_ibfk_3` FOREIGN KEY (`PembayaranID`) REFERENCES `pembayaran` (`PembayaranID`) ON DELETE CASCADE;

--
-- Constraints for table `produk`
--
ALTER TABLE `produk`
  ADD CONSTRAINT `produk_ibfk_1` FOREIGN KEY (`WarungID`) REFERENCES `warung` (`WarungID`) ON DELETE CASCADE,
  ADD CONSTRAINT `produk_ibfk_2` FOREIGN KEY (`SatuanID`) REFERENCES `satuan` (`SatuanID`);

--
-- Constraints for table `resep`
--
ALTER TABLE `resep`
  ADD CONSTRAINT `resep_ibfk_1` FOREIGN KEY (`KategoriResepID`) REFERENCES `kategoriresep` (`KategoriResepID`) ON DELETE CASCADE,
  ADD CONSTRAINT `resep_ibfk_2` FOREIGN KEY (`WaktuMemasakID`) REFERENCES `subkategori` (`SubkategoriID`) ON DELETE SET NULL,
  ADD CONSTRAINT `resep_ibfk_3` FOREIGN KEY (`PerkiraanHargaID`) REFERENCES `subkategori` (`SubkategoriID`) ON DELETE SET NULL,
  ADD CONSTRAINT `resep_ibfk_4` FOREIGN KEY (`JenisHidanganID`) REFERENCES `subkategori` (`SubkategoriID`) ON DELETE SET NULL,
  ADD CONSTRAINT `resep_ibfk_5` FOREIGN KEY (`AsalMasakanID`) REFERENCES `subkategori` (`SubkategoriID`) ON DELETE SET NULL;

--
-- Constraints for table `resepbahan`
--
ALTER TABLE `resepbahan`
  ADD CONSTRAINT `resepbahan_ibfk_1` FOREIGN KEY (`ResepID`) REFERENCES `resep` (`ResepID`) ON DELETE CASCADE,
  ADD CONSTRAINT `resepbahan_ibfk_2` FOREIGN KEY (`BahanBakuID`) REFERENCES `bahanbaku` (`BahanBakuID`) ON DELETE CASCADE;

--
-- Constraints for table `subkategori`
--
ALTER TABLE `subkategori`
  ADD CONSTRAINT `subkategori_ibfk_1` FOREIGN KEY (`KategoriResepID`) REFERENCES `kategoriresep` (`KategoriResepID`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
