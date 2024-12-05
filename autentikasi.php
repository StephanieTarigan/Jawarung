<?php
session_start();
include("dbconfig.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Query untuk mendapatkan data pengguna berdasarkan email
    $query = "SELECT * FROM users WHERE Email='$email'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);

        // Verifikasi password
        if (password_verify($password, $row['Password'])) {
            // Simpan data sesi
            $_SESSION['valid'] = $row['Email'];
            $_SESSION['username'] = $row['Username'];
            $_SESSION['UserID'] = $row['UserID'];
            $_SESSION['role'] = $row['role']; // Menyimpan peran pengguna

            // Redirect ke halaman sesuai dengan peran
            if ($row['role'] === 'admin') {
                header("Location: admin/index.php");
            } else {
                header("Location: pelanggan/index.php");
            }
            exit;
        } else {
            // Jika password salah
            header("Location: login.php?errMsg=Kata sandi salah. Coba lagi.");
            exit;
        }
    } else {
        // Jika email tidak ditemukan
        header("Location: login.php?errMsg=Email tidak ditemukan. Daftar terlebih dahulu.");
        exit;
    }
}
?>
