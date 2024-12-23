<?php
include "../template/main_header.html";
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Gomak Resep</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <!-- Main Content Container -->
    <div class="container bg-white rounded shadow-sm p-4 my-4">
        <div class="row">
            <!-- Left: Image and Ingredients -->
            <div class="col-md-4">
                <img src="https://www.masakapahariini.com/wp-content/uploads/2020/10/mie-gomak.jpg"
                    class="img-fluid rounded mb-3" alt="Mi Gomak">
                <div>
                    <h5 class="text-center mb-3">Bahan-Bahan</h5>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="fw-bold">4 Porsi</span>
                        <div class="d-flex align-items-center">
                            <button class="btn btn-sm btn-success me-2" id="btn-minus">-</button>
                            <span class="fw-bold" id="porsiValue">4</span>
                            <button class="btn btn-sm btn-success ms-2" id="btn-plus">+</button>
                        </div>
                    </div>
                    <ul class="list-group mb-3">
                        <li class="list-group-item d-flex justify-content-between">Mi Lidi<span>300g</span></li>
                        <li class="list-group-item d-flex justify-content-between">Sereh<span>3 batang</span></li>
                        <li class="list-group-item d-flex justify-content-between">Daging Sapi<span>500g</span></li>
                    </ul>
                    <button class="btn btn-success w-100" data-bs-toggle="modal"
                        data-bs-target="#detailHargaModal">Lihat Detail Harga</button>
                </div>
            </div>

            <!-- Right: Steps and Rating -->
            <div class="col-md-8">
                <h2>Mi Gomak Lezat dari Sumatra Utara</h2>
                <span class="badge bg-success">Sarapan</span>
                <span class="badge bg-secondary">Indonesia</span>
                <p class="mt-2"><strong>Total:</strong> 1 jam 30 menit</p>
                <h5>Cara Membuat</h5>
                <ol>
                    <li>Panaskan minyak, tumis bumbu halus, serai, dan daun jeruk.</li>
                    <li>Masukkan susu rendah lemak, daging, santan, dan bahan lainnya.</li>
                    <li>Masukkan daun bawang dan tomat, aduk rata, lalu angkat.</li>
                </ol>
                <div class="d-flex justify-content-between my-3">
                    <a href="https://youtu.be/oyTI-M-FrzQ?si=W8MqoGFpBznOSjy-" target="_blank" class="btn btn-success">Tonton Resep Masakan</a>
                    <button class="btn btn-success">Mulai Memasak →</button>
                </div>

                <!-- Rating -->
                <div class="text-center mb-4">
                    <div class="fs-1 text-success fw-bold">5.0 / 5.0</div>
                    <p class="text-success">100% pengguna merasa puas</p>
                </div>

                <!-- Ulasan -->
                <div>
                    <h5>Ulasan Pilihan</h5>
                    <div class="d-flex align-items-start mb-3 border-bottom pb-2">
                        <img src="https://i.pinimg.com/736x/3e/e3/f0/3ee3f0399f0b03e072670d75648a8450.jpg" alt="Foto Komentar" class="me-3" style="border-radius: 10px; width: 60px; height: 60px; object-fit: cover;">
                        <div>
                            <strong>Thalia</strong>
                            <p class="mb-0">Resepnya mudah banget diikutin!</p>
                        </div>
                    </div>
                    <div class="d-flex align-items-start mb-3 border-bottom pb-2">
                        <img src="https://i.pinimg.com/736x/47/87/cb/4787cbed2cfcaaf9933ca1f588bb32a0.jpg" alt="Foto Komentar" class="me-3" style="border-radius: 10px; width: 60px; height: 60px; object-fit: cover;">
                        <div>
                            <strong>Budi</strong>
                            <p class="mb-0">Hasilnya enak, keluarga suka banget!</p>
                        </div>
                    </div>
                    <div class="bg-light p-3 rounded">
                        <h6>Tambahkan Ulasan Anda</h6>
                        <form>
                            <div class="mb-3">
                                <label for="reviewText" class="form-label">Ulasan</label>
                                <textarea class="form-control" id="reviewText" rows="3" placeholder="Tulis ulasan Anda..."></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="reviewImage" class="form-label">Upload Foto</label>
                                <input type="file" class="form-control" id="reviewImage">
                            </div>
                            <button type="submit" class="btn btn-success">Kirim Ulasan</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Popup Detail Harga -->
    <div class="modal fade" id="detailHargaModal" tabindex="-1" aria-labelledby="detailHargaModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="detailHargaModalLabel">Detail Harga</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="d-flex justify-content-between align-items-center border-bottom py-2">
                        <label class="form-check-label">
                            <input type="checkbox" class="form-check-input"> Mi Lidi <small>(300 gr)</small>
                        </label>
                        <span>1 × Rp10.000</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center border-bottom py-2">
                        <label class="form-check-label">
                            <input type="checkbox" class="form-check-input"> Sereh <small>(100 gr)</small>
                        </label>
                        <span>1 × Rp2.000</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center border-bottom py-2">
                        <label class="form-check-label">
                            <input type="checkbox" class="form-check-input"> Daging Sapi <small>(500 gr)</small>
                        </label>
                        <span>1 × Rp50.000</span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-success">Beli Sekarang</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Porsi Tambah/Kurang
        const btnMinus = document.getElementById('btn-minus');
        const btnPlus = document.getElementById('btn-plus');
        const porsiValue = document.getElementById('porsiValue');

        let porsi = 4;

        btnMinus.addEventListener('click', () => {
            if (porsi > 1) {
                porsi--;
                porsiValue.textContent = porsi;
            }
        });

        btnPlus.addEventListener('click', () => {
            porsi++;
            porsiValue.textContent = porsi;
        });
    </script>
</body>

</html>
