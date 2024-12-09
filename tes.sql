-- 1. Buat Database
CREATE DATABASE highdsd;
USE highdsd;

-- 2. Buat Tabel Satuan
CREATE TABLE Satuan (
    SatuanID INT AUTO_INCREMENT PRIMARY KEY,
    NamaSatuan VARCHAR(50) NOT NULL,
    DeskripsiSatuan TEXT
);

-- 3. Buat Tabel Warung
CREATE TABLE Warung (
    WarungID INT AUTO_INCREMENT PRIMARY KEY,
    NamaWarung VARCHAR(100) NOT NULL,
    AlamatWarung TEXT NOT NULL
);

-- 4. Buat Tabel Produk
CREATE TABLE Produk (
    ProdukID INT AUTO_INCREMENT PRIMARY KEY,
    NamaProduk VARCHAR(100) NOT NULL,
    DeskripsiProduk TEXT,
    Harga DECIMAL(10, 2) NOT NULL,
    WarungID INT NOT NULL,
    FOREIGN KEY (WarungID) REFERENCES Warung(WarungID) ON DELETE CASCADE
);

-- 5. Buat Tabel Resep
CREATE TABLE Resep (
    ResepID INT AUTO_INCREMENT PRIMARY KEY,
    NamaResep VARCHAR(100) NOT NULL,
    DeskripsiResep TEXT,
    LinkVideoTutorial TEXT
);

-- 6. Buat Tabel LangkahMemasak
CREATE TABLE LangkahMemasak (
    LangkahID INT AUTO_INCREMENT PRIMARY KEY,
    ResepID INT NOT NULL,
    NomorLangkah INT NOT NULL,
    DeskripsiLangkah TEXT NOT NULL,
    FOREIGN KEY (ResepID) REFERENCES Resep(ResepID) ON DELETE CASCADE
);

-- 7. Buat Tabel BahanBaku
CREATE TABLE BahanBaku (
    BahanBakuID INT AUTO_INCREMENT PRIMARY KEY,
    NamaBahanBaku VARCHAR(100) NOT NULL,
    Harga DECIMAL(10, 2),
    SatuanID INT NOT NULL,
    FOREIGN KEY (SatuanID) REFERENCES Satuan(SatuanID) ON DELETE CASCADE
);

-- 8. Buat Tabel Relasi Resep dan Bahan
CREATE TABLE ResepBahan (
    ResepBahanID INT AUTO_INCREMENT PRIMARY KEY,
    ResepID INT NOT NULL,
    BahanBakuID INT NOT NULL,
    JumlahBahan DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (ResepID) REFERENCES Resep(ResepID) ON DELETE CASCADE,
    FOREIGN KEY (BahanBakuID) REFERENCES BahanBaku(BahanBakuID) ON DELETE CASCADE
);

-- 9. Buat Tabel Users (Pelanggan)
CREATE TABLE Users (
    UserID INT AUTO_INCREMENT PRIMARY KEY,
    Username VARCHAR(100) NOT NULL,
    Email VARCHAR(100) UNIQUE NOT NULL,
    Password VARCHAR(255) NOT NULL,
    Alamat TEXT,
    Kabupaten VARCHAR(50),
    Kota VARCHAR(50),
    Kecamatan VARCHAR(50),
    KodePos VARCHAR(10)
);

-- 10. Buat Tabel Keranjang
CREATE TABLE Keranjang (
    KeranjangID INT AUTO_INCREMENT PRIMARY KEY,
    UserID INT NOT NULL,         
    ProdukID INT NOT NULL,       
    Kuantitas INT NOT NULL,
    FOREIGN KEY (UserID) REFERENCES Users(UserID) ON DELETE CASCADE,
    FOREIGN KEY (ProdukID) REFERENCES Produk(ProdukID) ON DELETE CASCADE
);

-- 11. Buat Tabel Komentar
CREATE TABLE Komentar (
    KomentarID INT AUTO_INCREMENT PRIMARY KEY,
    ProdukID INT,
    ResepID INT,
    UserID INT NOT NULL,
    Rating INT CHECK (Rating BETWEEN 1 AND 5),
    KomentarText TEXT,
    FotoKomentar TEXT,
    FOREIGN KEY (ProdukID) REFERENCES Produk(ProdukID) ON DELETE SET NULL,
    FOREIGN KEY (ResepID) REFERENCES Resep(ResepID) ON DELETE SET NULL,
    FOREIGN KEY (UserID) REFERENCES Users(UserID) ON DELETE CASCADE
);

-- 12. Buat Tabel FotoProduk
CREATE TABLE FotoProduk (
    FotoID INT AUTO_INCREMENT PRIMARY KEY,
    ProdukID INT NOT NULL,
    FotoPath TEXT NOT NULL,
    FOREIGN KEY (ProdukID) REFERENCES Produk(ProdukID) ON DELETE CASCADE
);

-- 13. Buat Tabel FotoResep
CREATE TABLE FotoResep (
    FotoID INT AUTO_INCREMENT PRIMARY KEY,
    ResepID INT NOT NULL,
    FotoPath TEXT NOT NULL,
    FOREIGN KEY (ResepID) REFERENCES Resep(ResepID) ON DELETE CASCADE
);

-- 14. Buat Tabel Pengiriman
CREATE TABLE Pengiriman (
    PengirimanID INT AUTO_INCREMENT PRIMARY KEY,
    UserID INT NOT NULL,
    AlamatTujuan TEXT NOT NULL,
    Kabupaten VARCHAR(50),
    Kota VARCHAR(50),
    Kecamatan VARCHAR(50),
    KodePos VARCHAR(10),
    TanggalPengiriman DATE NOT NULL,
    StatusPengiriman ENUM('Diproses', 'Dalam Pengiriman', 'Terkirim') DEFAULT 'Diproses',
    BiayaPengiriman DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (UserID) REFERENCES Users(UserID) ON DELETE CASCADE
);

-- 15. Buat Tabel Pembayaran
CREATE TABLE Pembayaran (
    PembayaranID INT AUTO_INCREMENT PRIMARY KEY,
    UserID INT NOT NULL,
    TotalBayar DECIMAL(10, 2) NOT NULL,
    TanggalPembayaran DATE NOT NULL,
    MetodePembayaran ENUM('Transfer Bank', 'E-Wallet', 'Kartu Kredit') NOT NULL,
    StatusPembayaran ENUM('Belum Dibayar', 'Sudah Dibayar') DEFAULT 'Belum Dibayar',
    FOREIGN KEY (UserID) REFERENCES Users(UserID) ON DELETE CASCADE
);

-- 16. Buat Tabel Pesanan
CREATE TABLE Pesanan (
    PesananID INT AUTO_INCREMENT PRIMARY KEY,
    UserID INT NOT NULL,
    PengirimanID INT NOT NULL,
    PembayaranID INT NOT NULL,
    TanggalPesanan DATE NOT NULL,
    StatusPesanan ENUM('Diproses', 'Dikirim', 'Selesai') DEFAULT 'Diproses',
    TotalPesanan DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (UserID) REFERENCES Users(UserID) ON DELETE CASCADE,
    FOREIGN KEY (PengirimanID) REFERENCES Pengiriman(PengirimanID) ON DELETE CASCADE,
    FOREIGN KEY (PembayaranID) REFERENCES Pembayaran(PembayaranID) ON DELETE CASCADE
);

-- 17. Buat Tabel DetailPesanan
CREATE TABLE DetailPesanan (
    DetailPesananID INT AUTO_INCREMENT PRIMARY KEY,
    PesananID INT NOT NULL,
    ProdukID INT NOT NULL,
    Kuantitas INT NOT NULL,
    Harga DECIMAL(10, 2) NOT NULL,
    Subtotal DECIMAL(10, 2) AS (Kuantitas * Harga) STORED,
    FOREIGN KEY (PesananID) REFERENCES Pesanan(PesananID) ON DELETE CASCADE,
    FOREIGN KEY (ProdukID) REFERENCES Produk(ProdukID) ON DELETE CASCADE
);

-- 18. Buat Tabel KategoriResep
CREATE TABLE KategoriResep (
    ResepID INT, 
    AsalMasakanID INT,
    JenisHidanganID INT,
    WaktuMemasakID INT,
    PerkiraanHargaID INT,
    PRIMARY KEY (ResepID, AsalMasakanID, JenisHidanganID, WaktuMemasakID, PerkiraanHargaID), 
    FOREIGN KEY (ResepID) REFERENCES Resep(ResepID) ON DELETE CASCADE, 
    FOREIGN KEY (AsalMasakanID) REFERENCES AsalMasakan(AsalMasakanID) ON DELETE CASCADE,
    FOREIGN KEY (JenisHidanganID) REFERENCES JenisHidangan(JenisHidanganID) ON DELETE CASCADE,
    FOREIGN KEY (WaktuMemasakID) REFERENCES WaktuMemasak(WaktuMemasakID) ON DELETE CASCADE,
    FOREIGN KEY (PerkiraanHargaID) REFERENCES PerkiraanHarga(PerkiraanHargaID) ON DELETE CASCADE
);

-- 19. Buat Tabel AsalMasakan
CREATE TABLE AsalMasakan (
    AsalMasakanID INT AUTO_INCREMENT PRIMARY KEY,
    asalMasakan VARCHAR(255) NOT NULL
);

-- 20. Buat Tabel JenisHidangan
CREATE TABLE JenisHidangan (
    JenisHidanganID INT AUTO_INCREMENT PRIMARY KEY,
    jenisHidangan VARCHAR(255) NOT NULL
);

-- 21. Buat Tabel WaktuMemasak
CREATE TABLE WaktuMemasak (
    WaktuMemasakID INT AUTO_INCREMENT PRIMARY KEY,
    waktuMemasak VARCHAR(255) NOT NULL
);

-- 22. Buat Tabel PerkiraanHarga
CREATE TABLE PerkiraanHarga (
    PerkiraanHargaID INT AUTO_INCREMENT PRIMARY KEY,
    hargaMin INT NOT NULL,
    hargaMax INT NOT NULL
);


--Menampilkan Perkiraan Harga Semua Produk yang Diperlukan di Resep
SELECT 
    R.NamaResep,
    B.NamaBahanBaku,
    RB.JumlahBahan,
    S.NamaSatuan,
    (B.Harga * RB.JumlahBahan) AS PerkiraanHarga
FROM Resep R
JOIN 
    ResepBahan RB ON R.ResepID = RB.ResepID
JOIN 
    BahanBaku B ON RB.BahanBakuID = B.BahanBakuID
JOIN 
    Satuan S ON B.SatuanID = S.SatuanID
WHERE 
    R.NamaResep = 'Nasi Goreng';


--Menampilkan semua produk yang tersedia di warung tertentu:
SELECT P.NamaProduk, P.Harga, W.NamaWarung
FROM Produk P
JOIN Warung W ON P.WarungID = W.WarungID
WHERE W.NamaWarung = 'Warung Pak Slamet';

--Menampilkan resep beserta bahan-bahan yang dibutuhkan
SELECT R.NamaResep, B.NamaBahanBaku, RB.JumlahBahan
FROM Resep R
JOIN ResepBahan RB ON R.ResepID = RB.ResepID
JOIN BahanBaku B ON RB.BahanBakuID = B.BahanBakuID
WHERE R.NamaResep = 'Nasi Goreng';

-- Menampilkan Semua Produk yang Ada di Keranjang Pengguna
SELECT 
    P.NamaProduk, 
    K.Kuantitas, 
    P.Harga, 
    (K.Kuantitas * P.Harga) AS HargaTotal
FROM 
    Keranjang K
JOIN 
    Produk P ON K.ProdukID = P.ProdukID
WHERE 
    K.UserID = 1; 

-- 5. Menampilkan Semua Komentar untuk Produk atau Resep Tertentu
SELECT 
    K.NamaProduk, 
    R.NamaResep, 
    C.Rating, 
    C.KomentarText, 
    C.FotoKomentar
FROM 
    Komentar C
LEFT JOIN 
    Produk K ON C.ProdukID = K.ProdukID
LEFT JOIN 
    Resep R ON C.ResepID = R.ResepID
WHERE 
    C.UserID = 1;  