-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 01, 2025 at 09:05 PM
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
-- Table structure for table `asalmasakan`
--

CREATE TABLE `asalmasakan` (
  `AsalMasakanID` int(11) NOT NULL,
  `asalMasakan` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `asalmasakan`
--

INSERT INTO `asalmasakan` (`AsalMasakanID`, `asalMasakan`) VALUES
(1, 'Indonesia'),
(2, 'Cina'),
(3, 'Asia'),
(4, 'Afrika'),
(5, 'Thailand'),
(6, 'Chinese');

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

--
-- Dumping data for table `bahanbaku`
--

INSERT INTO `bahanbaku` (`BahanBakuID`, `NamaBahanBaku`, `Harga`, `SatuanID`) VALUES
(4, 'Udang', 50000.00, 1),
(5, 'Jamur', 30000.00, 1),
(6, 'Mi Instan', 4000.00, 1),
(7, 'Kecap Manis', 12000.00, 2),
(8, 'Sosis', 25000.00, 3),
(9, 'Tepung Jagung', 8000.00, 1),
(10, 'Ayam', 20000.00, 1),
(11, 'Santang Kelapa', 5000.00, 2);

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

--
-- Dumping data for table `detailpesanan`
--

INSERT INTO `detailpesanan` (`DetailPesananID`, `PesananID`, `ProdukID`, `Kuantitas`, `Harga`) VALUES
(1, 1, 1, 2, 15000.00),
(2, 1, 2, 1, 5000.00),
(3, 2, 3, 3, 45000.00),
(4, 2, 4, 1, 28000.00),
(5, 3, 5, 1, 65000.00),
(6, 3, 6, 2, 33000.00),
(7, 4, 7, 3, 15000.00),
(8, 4, 8, 1, 45000.00),
(9, 5, 9, 2, 12000.00),
(10, 5, 10, 4, 15000.00);

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
(1, 1, 'uploads/foto_produk/kentang.jpg'),
(2, 2, 'uploads/foto_produk/sayur_bayam.jpg'),
(3, 3, 'uploads/foto_produk/ayam_potong.jpg'),
(4, 4, 'uploads/foto_produk/telur_ayam.jpg'),
(5, 5, 'uploads/foto_produk/beras_premium.jpg'),
(6, 6, 'uploads/foto_produk/minyak_goreng.jpg'),
(7, 7, 'uploads/foto_produk/gula_pasir.jpg'),
(8, 8, 'uploads/foto_produk/kopi_bubuk.jpg'),
(9, 9, 'uploads/foto_produk/tepung_terigu.jpg'),
(10, 10, 'uploads/foto_produk/susu_uht.jpg'),
(15, 17, 'uploads/foto_produk/677507a258a29.jpeg'),
(16, 18, 'uploads/foto_produk/67750946bdd41.jpeg'),
(17, 19, 'uploads/foto_produk/67750a3b0041d.jpeg'),
(18, 20, 'uploads/foto_produk/67750b2e02c1a.jpeg'),
(19, 21, 'uploads/foto_produk/67750bae2f6b3.jpeg'),
(20, 22, 'uploads/foto_produk/67750c400e712.jpeg');

-- --------------------------------------------------------

--
-- Table structure for table `fotoresep`
--

CREATE TABLE `fotoresep` (
  `FotoID` int(11) NOT NULL,
  `ResepID` int(11) NOT NULL,
  `FotoPath` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `fotoresep`
--

INSERT INTO `fotoresep` (`FotoID`, `ResepID`, `FotoPath`) VALUES
(1, 7, 'uploads/foto_resep/nasigoreng.jpg'),
(2, 8, 'uploads/foto_resep/tomyum.jpg'),
(3, 9, 'uploads/foto_resep/migoreng.jpg'),
(4, 10, 'uploads/foto_resep/corndog.jpg'),
(5, 11, 'uploads/foto_resep/ayamgulai.jpg'),
(18, 29, 'uploads/foto_resep/6775855fb6fbf.jpeg');

-- --------------------------------------------------------

--
-- Table structure for table `jenishidangan`
--

CREATE TABLE `jenishidangan` (
  `JenisHidanganID` int(11) NOT NULL,
  `jenisHidangan` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jenishidangan`
--

INSERT INTO `jenishidangan` (`JenisHidanganID`, `jenisHidangan`) VALUES
(1, 'Sarapan'),
(2, 'Makan Siang'),
(3, 'Makan Malam'),
(4, 'Camilan'),
(5, 'Makanan Ringan');

-- --------------------------------------------------------

--
-- Table structure for table `kategoriresep`
--

CREATE TABLE `kategoriresep` (
  `ResepID` int(11) NOT NULL,
  `AsalMasakanID` int(11) NOT NULL,
  `JenisHidanganID` int(11) NOT NULL,
  `WaktuMemasakID` int(11) NOT NULL,
  `PerkiraanHargaID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kategoriresep`
--

INSERT INTO `kategoriresep` (`ResepID`, `AsalMasakanID`, `JenisHidanganID`, `WaktuMemasakID`, `PerkiraanHargaID`) VALUES
(7, 1, 1, 1, 1),
(8, 2, 4, 2, 3),
(9, 1, 1, 1, 1),
(10, 3, 2, 1, 2),
(11, 1, 1, 3, 2),
(29, 2, 4, 2, 17);

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

--
-- Dumping data for table `keranjang`
--

INSERT INTO `keranjang` (`KeranjangID`, `UserID`, `ProdukID`, `Kuantitas`) VALUES
(1, 1, 1, 2),
(2, 1, 2, 1),
(3, 2, 3, 3),
(4, 2, 4, 1),
(5, 3, 5, 1),
(6, 3, 6, 2),
(7, 4, 7, 3),
(8, 4, 8, 1),
(9, 5, 9, 2),
(10, 5, 10, 4);

-- --------------------------------------------------------

--
-- Table structure for table `kode_diskon`
--

CREATE TABLE `kode_diskon` (
  `DiskonID` int(11) NOT NULL,
  `KodeDiskon` varchar(50) NOT NULL,
  `PersentaseDiskon` decimal(5,2) DEFAULT 0.00,
  `NilaiDiskon` decimal(10,2) DEFAULT 0.00,
  `MinPembelian` decimal(10,2) DEFAULT 0.00,
  `ExpiredAt` timestamp NULL DEFAULT NULL,
  `Status` enum('aktif','nonaktif') DEFAULT 'aktif'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kode_diskon`
--

INSERT INTO `kode_diskon` (`DiskonID`, `KodeDiskon`, `PersentaseDiskon`, `NilaiDiskon`, `MinPembelian`, `ExpiredAt`, `Status`) VALUES
(1, 'DISKON10', 10.00, 0.00, 50000.00, '2025-01-31 16:59:59', 'aktif'),
(2, 'DISKON20', 20.00, 0.00, 100000.00, '2025-02-28 16:59:59', 'aktif'),
(3, 'DISKON50K', 0.00, 50000.00, 200000.00, '2025-03-31 16:59:59', 'aktif');

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

--
-- Dumping data for table `komentar`
--

INSERT INTO `komentar` (`KomentarID`, `ProdukID`, `ResepID`, `UserID`, `Rating`, `KomentarText`, `FotoKomentar`) VALUES
(1, NULL, NULL, 3, 5, 'Produk bagus dan segar!', NULL),
(2, NULL, NULL, 5, 4, 'Ayamnya fresh.', NULL),
(3, NULL, 7, 3, 5, 'Resepnya sangat mudah diikuti.', 'uploads/komentar/nasigoreng.jpg'),
(4, NULL, 8, 5, 4, 'Rasa Tom Yum autentik.', 'uploads/komentar/tomyum.jpg'),
(5, NULL, 9, 3, 3, 'Mi goreng lumayan.', NULL),
(6, NULL, 29, 7, NULL, 'mantap resepnya', ''),
(7, NULL, 29, 7, NULL, 'enak ih', '67758cf758eb6.jpeg'),
(8, NULL, 29, 7, NULL, 'good', ''),
(9, NULL, 29, 7, NULL, 'a', ''),
(10, NULL, 29, 7, 5, 'aye', '6775942029d6a.jpeg'),
(11, 1, NULL, 7, 4, 'fresh', '67759966371e8.png');

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

--
-- Dumping data for table `langkahmemasak`
--

INSERT INTO `langkahmemasak` (`LangkahID`, `ResepID`, `NomorLangkah`, `DeskripsiLangkah`) VALUES
(18, 10, 1, 'Siapkan adonan tepung jagung dengan mencampur tepung, susu, dan telur.'),
(19, 10, 2, 'Potong sosis menjadi ukuran kecil, lalu tusuk dengan tusuk sate.'),
(20, 10, 3, 'Celupkan sosis ke dalam adonan hingga seluruhnya tertutup.'),
(21, 10, 4, 'Gulingkan sosis yang telah dilapisi adonan ke dalam tepung roti.'),
(22, 10, 5, 'Goreng dalam minyak panas hingga keemasan, lalu tiriskan.'),
(23, 11, 1, 'Panaskan minyak dan tumis bumbu halus hingga harum.'),
(24, 11, 2, 'Masukkan ayam dan masak hingga berubah warna.'),
(25, 11, 3, 'Tambahkan santan, daun salam, dan serai. Masak hingga mendidih.'),
(26, 11, 4, 'Tambahkan garam, gula, dan air asam jawa. Aduk rata.'),
(27, 11, 5, 'Masak hingga ayam matang dan bumbu meresap. Sajikan.'),
(64, 7, 2, 'Panaskan minyak goreng dalam wajan atau penggorengan.'),
(65, 7, 3, 'Tumis bawang putih dan bawang merah hingga harum.'),
(66, 7, 4, 'Masukkan daging ayam atau udang dan sayuran. Tumis hingga daging matang dan sayuran layu.'),
(67, 7, 5, 'Masukkan nasi putih yang sudah dimasak. Aduk hingga tercampur rata.'),
(68, 7, 6, 'Tambahkan kecap manis, kecap asin, dan merica. Aduk kembali hingga bumbu meresap.'),
(69, 7, 7, 'Masak selama 2-3 menit atau hingga nasi goreng matang dan kering.'),
(70, 7, 8, 'Angkat dari wajan dan taburi dengan daun bawang.'),
(71, 8, 9, 'Panaskan air di panci hingga mendidih.'),
(72, 8, 10, 'Masukkan serai, daun jeruk, dan lengkuas ke dalam air mendidih.'),
(73, 8, 11, 'Tambahkan pasta tom yum, cabai, dan udang. Masak hingga udang matang.'),
(74, 8, 12, 'Masukkan jamur dan tomat, lalu biarkan mendidih selama 5 menit.'),
(75, 8, 13, 'Tambahkan air jeruk nipis, kecap ikan, dan gula. Aduk rata, lalu sajikan.'),
(76, 9, 14, 'Rebus mi hingga matang, lalu tiriskan.'),
(77, 9, 15, 'Panaskan minyak di wajan, tumis bawang putih hingga harum.'),
(78, 9, 16, 'Masukkan sayuran dan tumis hingga layu.'),
(79, 9, 17, 'Tambahkan mi, kecap manis, dan bumbu mi instan. Aduk hingga rata.'),
(80, 9, 18, 'Masak hingga mi matang sempurna, lalu sajikan.'),
(81, 29, 1, 'asad'),
(82, 29, 2, 'asdffd'),
(83, 29, 1, 'asad'),
(84, 29, 2, 'asdffd');

-- --------------------------------------------------------

--
-- Table structure for table `pelanggan`
--

CREATE TABLE `pelanggan` (
  `PelangganID` int(11) NOT NULL,
  `Nama` varchar(100) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `Alamat` text DEFAULT NULL,
  `NoTelepon` varchar(15) DEFAULT NULL,
  `Kabupaten` varchar(50) DEFAULT NULL,
  `Kota` varchar(50) DEFAULT NULL,
  `Kecamatan` varchar(50) DEFAULT NULL,
  `KodePos` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pelanggan`
--

INSERT INTO `pelanggan` (`PelangganID`, `Nama`, `Email`, `Alamat`, `NoTelepon`, `Kabupaten`, `Kota`, `Kecamatan`, `KodePos`) VALUES
(1, 'Stephanie', 'stephanie@gmail.com', 'Jl. Asia Afrika No. 1', '081234567111', 'Bandung', 'Bandung', 'Lengkong', '40111'),
(2, 'Diaz', 'diaz@gmail.com', 'Jl. Merdeka No. 12', '081234567112', 'Jakarta', 'Jakarta Pusat', 'Gambir', '10110'),
(3, 'Dhayfan', 'dhayfan@gmail.com', 'Jl. Malioboro No. 23', '081234567113', 'Yogyakarta', 'Yogyakarta', 'Gondomanan', '55271'),
(4, 'Maria', 'maria@gmail.com', 'Jl. Sudirman No. 45', '081234567114', 'Surabaya', 'Surabaya', 'Tegalsari', '60262'),
(5, 'Dian', 'dian@gmail.com', 'Jl. Diponegoro No. 67', '081234567115', 'Semarang', 'Semarang', 'Banyumanik', '50265'),
(6, 'Aido', 'aido@gmail.com', 'Jl. Gajah Mada No. 89', '081234567116', 'Denpasar', 'Denpasar', 'Denpasar Barat', '80231'),
(7, 'Hoper', 'hoper@gmail.com', 'Jl. Panglima Polim No. 101', '081234567117', 'Jakarta', 'Jakarta Selatan', 'Kebayoran Baru', '12160'),
(8, 'Rey', 'rey@gmail.com', 'Jl. Jenderal Sudirman No. 202', '081234567118', 'Medan', 'Medan', 'Medan Petisah', '20111');

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

--
-- Dumping data for table `pembayaran`
--

INSERT INTO `pembayaran` (`PembayaranID`, `UserID`, `TotalBayar`, `TanggalPembayaran`, `MetodePembayaran`, `StatusPembayaran`) VALUES
(1, 3, 100000.00, '2024-12-25', 'Transfer Bank', 'Sudah Dibayar'),
(2, 3, 75000.00, '2024-12-26', 'E-Wallet', 'Sudah Dibayar'),
(3, 5, 50000.00, '2024-12-27', 'Kartu Kredit', 'Belum Dibayar'),
(4, 5, 120000.00, '2024-12-28', 'Transfer Bank', 'Sudah Dibayar'),
(5, 3, 60000.00, '2024-12-29', 'E-Wallet', 'Belum Dibayar');

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

--
-- Dumping data for table `pengiriman`
--

INSERT INTO `pengiriman` (`PengirimanID`, `UserID`, `AlamatTujuan`, `Kabupaten`, `Kota`, `Kecamatan`, `KodePos`, `TanggalPengiriman`, `StatusPengiriman`, `BiayaPengiriman`) VALUES
(1, 1, 'Jl. Asia Afrika No. 1', 'Bandung', 'Bandung', 'Lengkong', '40111', '2024-12-25', 'Diproses', 10000.00),
(2, 2, 'Jl. Merdeka No. 12', 'Jakarta', 'Jakarta Pusat', 'Gambir', '10110', '2024-12-26', 'Dalam Pengiriman', 15000.00),
(3, 3, 'Jl. Malioboro No. 23', 'Yogyakarta', 'Yogyakarta', 'Gondomanan', '55271', '2024-12-27', 'Terkirim', 20000.00),
(4, 4, 'Jl. Sudirman No. 45', 'Surabaya', 'Surabaya', 'Tegalsari', '60262', '2024-12-28', 'Dalam Pengiriman', 25000.00),
(5, 5, 'Jl. Diponegoro No. 67', 'Semarang', 'Semarang', 'Banyumanik', '50265', '2024-12-29', 'Diproses', 10000.00);

-- --------------------------------------------------------

--
-- Table structure for table `perkiraanharga`
--

CREATE TABLE `perkiraanharga` (
  `PerkiraanHargaID` int(11) NOT NULL,
  `hargaMin` int(11) NOT NULL,
  `hargaMax` int(11) NOT NULL,
  `TotalHarga` decimal(10,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `perkiraanharga`
--

INSERT INTO `perkiraanharga` (`PerkiraanHargaID`, `hargaMin`, `hargaMax`, `TotalHarga`) VALUES
(1, 50000, 70000, 0.00),
(2, 70000, 100000, 0.00),
(3, 100000, 150000, 0.00),
(4, 150000, 200000, 0.00),
(5, 0, 10000, 0.00),
(6, 10000, 20000, 0.00),
(7, 40000, 50000, 0.00),
(8, 20000, 30000, 0.00),
(9, 30000, 40000, 0.00),
(11, 3000, 30000, 0.00),
(12, 10000, 15000, 0.00),
(13, 4000, 13000, 0.00),
(14, 8000, 8000, 0.00),
(15, 4000, 28000, 0.00),
(16, 5000, 16000, 0.00),
(17, 2000, 15000, 0.00);

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
  `TotalPesanan` decimal(10,2) NOT NULL,
  `DiskonID` int(11) DEFAULT NULL,
  `TotalSetelahDiskon` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pesanan`
--

INSERT INTO `pesanan` (`PesananID`, `PelangganID`, `PengirimanID`, `PembayaranID`, `TanggalPesanan`, `StatusPesanan`, `TotalPesanan`, `DiskonID`, `TotalSetelahDiskon`) VALUES
(1, 1, 1, 1, '2024-12-25', 'Selesai', 10000.00, NULL, NULL),
(2, 2, 2, 2, '2024-12-26', 'Selesai', 25000.00, NULL, NULL),
(3, 3, 3, 3, '2024-12-27', 'Diproses', 15000.00, NULL, NULL),
(4, 4, 4, 4, '2024-12-28', 'Dikirim', 120000.00, NULL, NULL),
(5, 5, 5, 5, '2024-12-29', 'Diproses', 60000.00, NULL, NULL),
(6, 1, 1, 1, '2024-12-30', 'Diproses', 150000.00, NULL, NULL),
(7, 3, 3, 3, '2024-12-31', 'Dikirim', 80000.00, NULL, NULL),
(8, 2, 2, 2, '2025-01-01', 'Selesai', 90000.00, NULL, NULL),
(9, 4, 4, 4, '2025-01-02', 'Diproses', 70000.00, NULL, NULL),
(10, 5, 5, 5, '2025-01-03', 'Diproses', 110000.00, NULL, NULL);

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
  `SatuanID` int(11) DEFAULT NULL,
  `Stock` int(11) DEFAULT 0,
  `StatusProduk` enum('aktif','nonaktif') DEFAULT 'aktif',
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `UpdatedAt` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `produk`
--

INSERT INTO `produk` (`ProdukID`, `NamaProduk`, `DeskripsiProduk`, `Harga`, `WarungID`, `SatuanID`, `Stock`, `StatusProduk`, `CreatedAt`, `UpdatedAt`) VALUES
(1, 'Kentang', 'Kentang segar per kg', 15000.00, 2, 2, 100, 'aktif', '2025-01-01 08:12:47', '2025-01-01 08:13:45'),
(2, 'Sayur Bayam', 'Sayur bayam segar per ikat', 5000.00, 1, 1, 100, 'aktif', '2025-01-01 08:12:47', '2025-01-01 08:13:45'),
(3, 'Ayam Potong', 'Ayam potong segar per kg', 45000.00, 1, 2, 100, 'aktif', '2025-01-01 08:12:47', '2025-01-01 08:13:45'),
(4, 'Telur Ayam', 'Telur ayam segar per kg', 28000.00, 1, 2, 100, 'aktif', '2025-01-01 08:12:47', '2025-01-01 08:13:45'),
(5, 'Beras Premium', 'Beras premium 5kg', 65000.00, 2, 2, 100, 'aktif', '2025-01-01 08:12:47', '2025-01-01 08:13:45'),
(6, 'Minyak Goreng', 'Minyak goreng 2L', 33000.00, 3, 3, 100, 'aktif', '2025-01-01 08:12:47', '2025-01-01 08:13:45'),
(7, 'Gula Pasir', 'Gula pasir per kg', 15000.00, 1, 1, 100, 'aktif', '2025-01-01 08:12:47', '2025-01-01 08:13:45'),
(8, 'Kopi Bubuk', 'Kopi bubuk 500 gram', 45000.00, 2, 1, 100, 'aktif', '2025-01-01 08:12:47', '2025-01-01 08:13:45'),
(9, 'Tepung Terigu', 'Tepung terigu serbaguna 1 kg', 12000.00, 3, 1, 100, 'aktif', '2025-01-01 08:12:47', '2025-01-01 08:13:45'),
(10, 'Susu UHT', 'Susu UHT 1L', 15000.00, 1, 3, 100, 'aktif', '2025-01-01 08:12:47', '2025-01-01 08:13:45'),
(17, 'Mi Telur 200g', 'Mi berkualitas tinggi, cocok untuk berbagai masakan seperti mi goreng atau mi kuah. Mudah dimasak dan kenyal!', 8000.00, 14, 7, 100, 'aktif', '2025-01-01 09:15:14', '2025-01-01 09:15:14'),
(18, 'Sayur Kol', 'Kol per 100 gram segar, dan higienis. Sempurna untuk pelengkap hidangan berkuah atau tumisan.', 4000.00, 13, 1, 100, 'aktif', '2025-01-01 09:22:14', '2025-01-01 09:22:14'),
(19, 'Daun Bawang', 'Daun bawang segar, menambah aroma gurih. minimal pembelian 2000/ satu batang', 2000.00, 14, 6, 100, 'aktif', '2025-01-01 09:26:19', '2025-01-01 09:26:19'),
(20, 'Tomat per 500gr', 'Tomat berkualitas tinggi, memberikan rasa segar dan manis alami.', 6500.00, 14, 1, 100, 'aktif', '2025-01-01 09:30:22', '2025-01-01 09:30:22'),
(21, 'Sawi Hijau', 'Sawi hijau segar, memberikan tekstur renyah dan gizi pada masakan.\r\n\r\nMinimal pembelian Rp5000 (1 ikat)', 5000.00, 14, 7, 100, 'aktif', '2025-01-01 09:32:30', '2025-01-01 09:32:30'),
(22, 'Kecap Bango 250ml', 'Kecap manis pilihan, memberikan rasa manis khas pada masakan Anda.', 15000.00, 13, 7, 100, 'aktif', '2025-01-01 09:34:56', '2025-01-01 09:34:56');

-- --------------------------------------------------------

--
-- Table structure for table `resep`
--

CREATE TABLE `resep` (
  `ResepID` int(11) NOT NULL,
  `NamaResep` varchar(100) NOT NULL,
  `DeskripsiResep` text DEFAULT NULL,
  `LinkVideoTutorial` text DEFAULT NULL,
  `TotalHarga` decimal(10,2) DEFAULT 0.00,
  `porsi` int(11) NOT NULL DEFAULT 4
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `resep`
--

INSERT INTO `resep` (`ResepID`, `NamaResep`, `DeskripsiResep`, `LinkVideoTutorial`, `TotalHarga`, `porsi`) VALUES
(7, 'Nasi Goreng', 'Nasi goreng sederhana dengan bumbu khas', 'https://youtu.be/Js9FXCkn798', 0.00, 4),
(8, 'Tom Yum', 'Sup pedas asam khas Thailand dengan udang dan jamur', 'https://youtu.be/2pRj9TiGldY', 0.00, 4),
(9, 'Mi Goreng', 'Mi goreng dengan bumbu kecap manis dan sayuran', 'https://youtu.be/oyTI-M-FrzQ', 0.00, 4),
(10, 'Corn Dog', 'Hot dog yang dibalut adonan tepung jagung dan digoreng', 'https://youtu.be/AfKPJhgrbYY', 0.00, 4),
(11, 'Ayam Gulai', 'Ayam dimasak dengan bumbu gulai yang kental dan pedas', 'https://youtu.be/Z5kH8jAfNe0', 0.00, 4),
(12, 'a', 'a', 'https://getbootstrap.com/docs/5.0/utilities/background/', 0.00, 4),
(29, 'aaaaaas', 'anmasds', 'https://youtu.be/bkC_3z7FHXw?si=Cso4OLsppPFzCmtS', 0.00, 4);

-- --------------------------------------------------------

--
-- Table structure for table `resepbahan`
--

CREATE TABLE `resepbahan` (
  `ResepBahanID` int(11) NOT NULL,
  `ResepID` int(11) NOT NULL,
  `ProdukID` int(11) NOT NULL,
  `JumlahBahan` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `resepbahan`
--

INSERT INTO `resepbahan` (`ResepBahanID`, `ResepID`, `ProdukID`, `JumlahBahan`) VALUES
(24, 29, 10, 1.00),
(25, 29, 19, 1.00);

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
(5, 'Porsi', 'Jumlah bahan per porsi'),
(6, 'Butir', 'Satuan untuk jumlah benda seperti telur'),
(7, 'Bungkus', 'Satuan untuk jumlah produk dalam kemasan');

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
  `role` varchar(20) DEFAULT 'pelanggan',
  `ProfilePicture` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`UserID`, `Username`, `Email`, `Password`, `Alamat`, `Kabupaten`, `Kota`, `Kecamatan`, `KodePos`, `NoTelepon`, `role`, `ProfilePicture`) VALUES
(1, 'admin', 'admin@gmail.com', '$2y$10$ULp0W84OYc8LmS3v57iArOQ9nsPh28ZwBWHDtumLMH0nKSL8wraoO', 'Jl. Mitra Sehati No 4', 'Bandung', 'Bandung', 'Cileunyi', '23456', '', 'admin', 'uploads/profile_pics/675126d7d10799.79709731.jpeg'),
(2, 'diaz', 'diaz@gmail.com', 'password2', NULL, NULL, NULL, NULL, NULL, '081234567112', 'pelanggan', NULL),
(3, 'user', 'user@gmail.com', '$2y$10$tVVxN2ySWn7yDnh9urtRVu3arNb2IMvijlNguMPMcAw0DdElk8G7C', NULL, NULL, NULL, NULL, NULL, '', 'pelanggan', NULL),
(4, 'stephanie', 'setep@gmail.com', 'password4', NULL, NULL, NULL, NULL, NULL, '081234567114', 'pelanggan', NULL),
(5, 'dhayfan', 'depan@gmail.com', 'password5', NULL, NULL, NULL, NULL, NULL, '081234567115', 'pelanggan', NULL),
(7, 'setep', 'hanitrgn@gmail.com', '$2y$10$fjyWVIOMTIBfmozM.Zus9uWAj6u/ZkEhCHsAEleGfRkXIXN0FkIvy', 'posindo blok b4 no.4, Cileunyi, Cibiru', 'Bandung', 'Bandung', 'Cileunyi', '10000', '', 'pelanggan', 'uploads/profile_pics/67757b660c62c8.32567658.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `waktumemasak`
--

CREATE TABLE `waktumemasak` (
  `WaktuMemasakID` int(11) NOT NULL,
  `waktuMemasak` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `waktumemasak`
--

INSERT INTO `waktumemasak` (`WaktuMemasakID`, `waktuMemasak`) VALUES
(1, '15 Menit'),
(2, '30 Menit'),
(3, '1 Jam'),
(4, '2 Jam');

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
(2, 'Warung Bu Nani', 'Jl. Kebon Jeruk No. 5'),
(3, 'Warung Bu Yuyun', 'Jalan Jalan'),
(5, 'Warung Sayur Bu Tini', 'Jl. Pasar Minggu No. 23, Jakarta Selatan'),
(6, 'Warung Daging Segar Pak Agus', 'Jl. Raya Pasar Baru No. 12, Surabaya'),
(7, 'Warung Sayur dan Bumbu Mak Siti', 'Jl. Merdeka No. 45, Bandung'),
(8, 'Warung Sembako dan Sayur Pak Soleh', 'Jl. Diponegoro No. 34, Yogyakarta'),
(9, 'Warung Daging Ayam Fresh', 'Jl. Gajah Mada No. 78, Semarang'),
(10, 'Warung Sayur dan Ikan Segar', 'Jl. Jenderal Sudirman No. 15, Malang'),
(11, 'Warung Keluarga Bu Aminah', 'Jl. Kebon Jeruk No. 10, Jakarta Barat'),
(12, 'Warung Daging dan Sayur Segar', 'Jl. Asia Afrika No. 5, Bandung'),
(13, 'Warung Pasar Tradisional', 'Jl. Malioboro No. 23, Yogyakarta'),
(14, 'Warung Tani Makmur', 'Jl. Ahmad Yani No. 67, Medan');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `asalmasakan`
--
ALTER TABLE `asalmasakan`
  ADD PRIMARY KEY (`AsalMasakanID`);

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
-- Indexes for table `jenishidangan`
--
ALTER TABLE `jenishidangan`
  ADD PRIMARY KEY (`JenisHidanganID`);

--
-- Indexes for table `kategoriresep`
--
ALTER TABLE `kategoriresep`
  ADD PRIMARY KEY (`ResepID`,`AsalMasakanID`,`JenisHidanganID`,`WaktuMemasakID`,`PerkiraanHargaID`),
  ADD KEY `AsalMasakanID` (`AsalMasakanID`),
  ADD KEY `JenisHidanganID` (`JenisHidanganID`),
  ADD KEY `WaktuMemasakID` (`WaktuMemasakID`),
  ADD KEY `PerkiraanHargaID` (`PerkiraanHargaID`);

--
-- Indexes for table `keranjang`
--
ALTER TABLE `keranjang`
  ADD PRIMARY KEY (`KeranjangID`),
  ADD KEY `UserID` (`UserID`),
  ADD KEY `ProdukID` (`ProdukID`);

--
-- Indexes for table `kode_diskon`
--
ALTER TABLE `kode_diskon`
  ADD PRIMARY KEY (`DiskonID`),
  ADD UNIQUE KEY `KodeDiskon` (`KodeDiskon`);

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
-- Indexes for table `perkiraanharga`
--
ALTER TABLE `perkiraanharga`
  ADD PRIMARY KEY (`PerkiraanHargaID`);

--
-- Indexes for table `pesanan`
--
ALTER TABLE `pesanan`
  ADD PRIMARY KEY (`PesananID`),
  ADD KEY `PelangganID` (`PelangganID`),
  ADD KEY `PengirimanID` (`PengirimanID`),
  ADD KEY `PembayaranID` (`PembayaranID`),
  ADD KEY `fk_pesanan_diskon` (`DiskonID`);

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
  ADD PRIMARY KEY (`ResepID`);

--
-- Indexes for table `resepbahan`
--
ALTER TABLE `resepbahan`
  ADD PRIMARY KEY (`ResepBahanID`),
  ADD KEY `ResepID` (`ResepID`),
  ADD KEY `BahanBakuID` (`ProdukID`);

--
-- Indexes for table `satuan`
--
ALTER TABLE `satuan`
  ADD PRIMARY KEY (`SatuanID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`UserID`),
  ADD UNIQUE KEY `Email` (`Email`);

--
-- Indexes for table `waktumemasak`
--
ALTER TABLE `waktumemasak`
  ADD PRIMARY KEY (`WaktuMemasakID`);

--
-- Indexes for table `warung`
--
ALTER TABLE `warung`
  ADD PRIMARY KEY (`WarungID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `asalmasakan`
--
ALTER TABLE `asalmasakan`
  MODIFY `AsalMasakanID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `bahanbaku`
--
ALTER TABLE `bahanbaku`
  MODIFY `BahanBakuID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `detailpesanan`
--
ALTER TABLE `detailpesanan`
  MODIFY `DetailPesananID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `fotoproduk`
--
ALTER TABLE `fotoproduk`
  MODIFY `FotoID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `fotoresep`
--
ALTER TABLE `fotoresep`
  MODIFY `FotoID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `jenishidangan`
--
ALTER TABLE `jenishidangan`
  MODIFY `JenisHidanganID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `keranjang`
--
ALTER TABLE `keranjang`
  MODIFY `KeranjangID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `kode_diskon`
--
ALTER TABLE `kode_diskon`
  MODIFY `DiskonID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `komentar`
--
ALTER TABLE `komentar`
  MODIFY `KomentarID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `langkahmemasak`
--
ALTER TABLE `langkahmemasak`
  MODIFY `LangkahID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=85;

--
-- AUTO_INCREMENT for table `pelanggan`
--
ALTER TABLE `pelanggan`
  MODIFY `PelangganID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `pembayaran`
--
ALTER TABLE `pembayaran`
  MODIFY `PembayaranID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `pengiriman`
--
ALTER TABLE `pengiriman`
  MODIFY `PengirimanID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `perkiraanharga`
--
ALTER TABLE `perkiraanharga`
  MODIFY `PerkiraanHargaID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `pesanan`
--
ALTER TABLE `pesanan`
  MODIFY `PesananID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `produk`
--
ALTER TABLE `produk`
  MODIFY `ProdukID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `resep`
--
ALTER TABLE `resep`
  MODIFY `ResepID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `resepbahan`
--
ALTER TABLE `resepbahan`
  MODIFY `ResepBahanID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `satuan`
--
ALTER TABLE `satuan`
  MODIFY `SatuanID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `UserID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `waktumemasak`
--
ALTER TABLE `waktumemasak`
  MODIFY `WaktuMemasakID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `warung`
--
ALTER TABLE `warung`
  MODIFY `WarungID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

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
-- Constraints for table `kategoriresep`
--
ALTER TABLE `kategoriresep`
  ADD CONSTRAINT `kategoriresep_ibfk_1` FOREIGN KEY (`ResepID`) REFERENCES `resep` (`ResepID`) ON DELETE CASCADE,
  ADD CONSTRAINT `kategoriresep_ibfk_2` FOREIGN KEY (`AsalMasakanID`) REFERENCES `asalmasakan` (`AsalMasakanID`) ON DELETE CASCADE,
  ADD CONSTRAINT `kategoriresep_ibfk_3` FOREIGN KEY (`JenisHidanganID`) REFERENCES `jenishidangan` (`JenisHidanganID`) ON DELETE CASCADE,
  ADD CONSTRAINT `kategoriresep_ibfk_4` FOREIGN KEY (`WaktuMemasakID`) REFERENCES `waktumemasak` (`WaktuMemasakID`) ON DELETE CASCADE,
  ADD CONSTRAINT `kategoriresep_ibfk_5` FOREIGN KEY (`PerkiraanHargaID`) REFERENCES `perkiraanharga` (`PerkiraanHargaID`) ON DELETE CASCADE;

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
  ADD CONSTRAINT `fk_pesanan_diskon` FOREIGN KEY (`DiskonID`) REFERENCES `kode_diskon` (`DiskonID`) ON DELETE SET NULL,
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
-- Constraints for table `resepbahan`
--
ALTER TABLE `resepbahan`
  ADD CONSTRAINT `fk_produk` FOREIGN KEY (`ProdukID`) REFERENCES `produk` (`ProdukID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `resepbahan_ibfk_1` FOREIGN KEY (`ResepID`) REFERENCES `resep` (`ResepID`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
