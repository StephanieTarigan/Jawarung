<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Jawarung</title>
  <style>
    /* Reset dasar */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: Arial, sans-serif;
  }
  
  body {
    background-color: #264E36; /* Hijau tua */
    color: #FFFFFF;
  }
  
  /* Header dan Navigasi */
  header {
    padding: 20px 0;
    border-bottom: 1px solid rgba(255, 255, 255, 0.2);
  }
  
  nav {
    display: flex;
    justify-content: flex-end;
    margin-right: 40px;
  }
  
  nav ul {
    display: flex;
    list-style: none;
    gap: 40px;
  }
  
  nav ul li a {
    color: #A5A5A5;
    font-size: 18px;
    font-weight: bold;
    transition: color 0.3s;
  }
  
  nav ul li a.active,
  nav ul li a:hover {
    color: #FFFFFF;
  }
  
  /* Konten Utama */
  .content {
    padding: 50px 30px 100px;
    display: flex;
    gap: 20px;
    align-items: center;
  }
  
  .text {
    flex: 1;
  }
  
  .image {
    flex: 1;
    display: flex;
    justify-content: center;
  }
  
  .image img {
    width: 500px;
    height: auto;
  }
  
  /* Pusat Bantuan 1 dan 3 */
  .pusatbantuan, .pusatbantuan3 {
    background-color: #f2ece0; /* Latar belakang krem */
    color: #264E36; /* Teks hijau tua */
    padding: 20px;
    line-height: 1.6;
  }
  
  .pusatbantuan h1, .pusatbantuan3 h1,
  .pusatbantuan h2, .pusatbantuan3 h2,
  .pusatbantuan h3, .pusatbantuan3 h3 {
    margin-bottom: 20px;
    font-weight: bold;
  }
  
  .pusatbantuan ul, .pusatbantuan3 ul {
    padding-left: 20px;
    margin: 20px 0;
  }
  
  .pusatbantuan ul li, .pusatbantuan3 ul li {
    margin-bottom: 10px;
  }
  
  .pusatbantuan a, .pusatbantuan3 a {
    color: #aad576;
    text-decoration: none;
  }
  
  .pusatbantuan a:hover, .pusatbantuan3 a:hover {
    text-decoration: underline;
  }
  
  /* Pusat Bantuan 2 */
  .pusatbantuan2 {
    background-color: #264E36; /* Sama dengan konten utama */
    color: #FFFFFF; /* Teks putih seperti konten utama */
    padding: 20px;
    line-height: 1.6;
  }
  
  .pusatbantuan2 h1, .pusatbantuan2 h2, .pusatbantuan2 h3 {
    margin-bottom: 20px;
    font-weight: bold;
  }
  
  .pusatbantuan2 ul {
    padding-left: 20px;
    margin: 20px 0;
  }
  
  .pusatbantuan2 ul li {
    margin-bottom: 10px;
  }
  
  .pusatbantuan2 a {
    color: #aad576;
    text-decoration: none;
  }
  
  .pusatbantuan2 a:hover {
    text-decoration: underline;
  }
  
  /* FAQ */
  .faq .question {
    font-weight: bold;
    margin-bottom: 5px;
  }
  
  </style>
</head>
<body>
  <header>
    <nav>
      <ul>
        <li><a href="#" class="active">Tentang Jawarung</a></li>
        <li><a href="#pusatbantuan">Pusat Bantuan</a></li>
        <li><a href="beranda/beranda.php">Belanja</a></li>
        <li><a href="#">Resep</a></li>
      </ul>
    </nav>
  </header>

  <main>
    <div class="content">
      <div class="text">
        <h2>Selamat Datang di Jawarung!</h2>
        <h3>Tentang Jawarung</h3>
        <p>Jawarung adalah platform untuk menemukan resep lezat dan membeli bahan makanan dengan mudah. Kami bertujuan untuk menyederhanakan pengalaman memasak Anda dengan menyediakan resep terperinci, cara mudah untuk berbelanja bahan-bahan, serta perkiraan harga dan informasi promo agar belanja lebih hemat.</p>
      </div>
      <div class="image">
        <img src="foto/lpage.png" alt="Soto Ayam">
      </div>
    </div>

<section class="pusatbantuan" id="pusatbantuan">
<h1>Pusat Bantuan</h1>

<h3>Akun</h3>

<h2>Membuat akun</h2>
<ul>
  <li><p>Klik tombol "Daftar" di sudut kanan atas halaman utama</p></li>
  <li><p>Isi formulir pendaftaran dengan informasi yang diperkukan</p></li>
  <li><p>Verifikasi email anda melalui tautan yang dikirimkan ke kontak masuk anda</p></li>
</ul>

<h2>Mengelola akun</h2>
<p>anda daoat mengelola informasi anda dengan mengunjungi halaman "profil saya". DI sini anda bisa memperbarui inforasi pribadi</p>

<h2>FAQ Akun</h2>
<div class="faq">
    <p class="question">Q: Bagaimana cara mengatur ulang kata sandi saya?</p>
    <p>A: Klik "Lupa Kata Sandi" di halaman login dan ikuti petunjuk untuk mengatur ulang kata sandi Anda.</p>
</div>
<div class="faq">
    <p class="question">Q: Bagaimana cara menghapus akun saya?</p>
    <p>A: Jika Anda ingin menghapus akun Anda, silakan hubungi tim dukungan pelanggan kami di <a href="mailto:support@jawarung.com">support@jawarung.com</a>.</p>
</div>
</section>

<section class="pusatbantuan2">
  <h1>Pusat Bantuan</h1>
  <h2>Pembayaran</h2>
  <h3>Metode Pembayaran yang Tersedia</h3>
  <p>Kami menerima berbagai metode pembayaran untuk memudahkan transaksi Anda:</p>
  <ul>
      <li>Transfer bank</li>
      <li>Pembayaran digital (e-wallet) seperti OVO, GoPay, dan Dana</li>
  </ul>

  <h3>Cara Melakukan Pembayaran</h3>
  <p>Setelah memilih resep dan menambahkan bahan-bahan ke keranjang belanja, ikuti langkah-langkah berikut:</p>
  <ol>
      <li>Lanjutkan ke halaman checkout.</li>
      <li>Pilih metode pembayaran yang diinginkan.</li>
      <li>Ikuti instruksi yang diberikan untuk menyelesaikan pembayaran.</li>
  </ol>

  <h3>FAQ Pembayaran</h3>
  <p><strong>Q: Apakah saya bisa membayar saat barang diterima (COD)?</strong><br>
  A: Saat ini, kami belum menyediakan opsi pembayaran Cash on Delivery (COD). Semua pembayaran dilakukan secara online.</p>

  <p><strong>Q: Apa yang harus saya lakukan jika pembayaran saya gagal?</strong><br>
  A: Jika pembayaran gagal, coba lagi dengan metode pembayaran lain atau hubungi tim dukungan pelanggan kami di <a href="mailto:support@jawarung.com">support@jawarung.com</a>.</p>
</section>

<section class="pusatbantuan3">
  <h1>Pusat Bantuan</h1>
  <h2>Pengantaran</h2>

  <h3>Proses Pengantaran</h3>
  <p>Setelah pembayaran Anda selesai, kami akan memproses pesanan dan mengirimkan bahan makanan ke alamat yang Anda tentukan. Anda akan menerima konfirmasi dan nomor pelacakan untuk memantau status pengantaran.</p>

  <h3>Pelacakan Pesanan</h3>
  <p>Setelah pesanan dikirim, Anda akan menerima nomor pelacakan. Gunakan nomor ini untuk memantau status pengantaran melalui website atau aplikasi layanan kurir yang bekerja sama dengan Jawarung.</p>

  <h3>FAQ Pengantaran</h3>
  <p><strong>Q: Apa yang harus saya lakukan jika tidak berada di rumah saat pesanan diantar?</strong><br>
  A: Kurir kami akan menghubungi Anda untuk mengatur pengantaran ulang. Pastikan nomor telepon yang Anda berikan aktif dan dapat dihubungi.</p>

  <p><strong>Q: Bagaimana jika ada masalah dengan pengantaran?</strong><br>
  A: Hubungi tim dukungan pelanggan kami di <a href="mailto:support@jawarung.com">support@jawarung.com</a> atau melalui fitur live chat di website kami. Kami akan membantu menyelesaikan masalah secepat mungkin.</p>
</section>

  </main>
</body>
</html>
