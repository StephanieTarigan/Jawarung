CREATE DATABASE IF NOT EXISTS resep_db;

USE resep_db;

CREATE TABLE resep (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_resep VARCHAR(255) NOT NULL,
    kategori VARCHAR(255),
    waktu_total VARCHAR(50),
    waktu_persiapan VARCHAR(50),
    waktu_memasak VARCHAR(50),
    bahan TEXT,
    cara_membuat TEXT,
    rating FLOAT,
    ulasan TEXT
);
