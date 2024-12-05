<?php
session_start();
include("dbconfig.php");

// Mengecek jika sudah login, jika sudah redirect ke halaman sesuai peran
if (isset($_SESSION['valid'])) {
    // Mengecek peran pengguna
    $role = $_SESSION['role'];
    if ($role === 'admin') {
        header("Location: admin/index.php");
    } else {
        header("Location: pelanggan/index.php");
    }
    exit();
}

if (isset($_POST['submit'])) {
    // Menangani input
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Query untuk mengambil data user berdasarkan email
    $result = mysqli_query($conn, "SELECT * FROM users WHERE Email='$email'") or die("Database query error");
    $row = mysqli_fetch_assoc($result);

    if ($row) {
        // Mengecek password menggunakan password_verify jika password disimpan dengan hash
        if (password_verify($password, $row['Password'])) {
            // Menyimpan informasi session jika login berhasil
            $_SESSION['valid'] = $row['Email'];
            $_SESSION['UserID'] = $row['UserID'];  // Make sure this line exists in your login logic
            $_SESSION['Username'] = $row['Username'];
            $_SESSION['role'] = $row['Role'];


            // Mengarahkan berdasarkan peran
            if ($_SESSION['role'] === 'admin') {
                header("Location: admin/index.php");
            } else {
                header("Location: pelanggan/index.php");
            }
            exit();
        } else {
            $error_message = "Password salah, coba lagi.";
        }
    } else {
        $error_message = "Email tidak ditemukan. Silakan daftar terlebih dahulu.";
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Login</title>
</head>

<body>
    <div class="container">
        <div class="box form-box">
            <header>Login</header>
            <?php
            if (isset($error_message)) {
                echo "<div class='message'><p>{$error_message}</p></div>";
            }
            ?>
            <form action="" method="post">
                <div class="field input">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" autocomplete="off" required>
                </div>

                <div class="field input">
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" autocomplete="off" required>
                </div>

                <div class="field">
                    <input type="submit" class="btn" name="submit" value="Login">
                </div>

                <div class="links">
                    Don't have an account? <a href="register.php">Sign Up Now</a>
                </div>
            </form>
        </div>
    </div>
</body>

</html>