<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Register</title>
</head>
<body>
    <div class="container">
        <div class="box form-box">

        <?php 
        include("dbconfig.php");
        
        if (isset($_POST['submit'])) {
            $username = mysqli_real_escape_string($conn, $_POST['username']);
            $email = mysqli_real_escape_string($conn, $_POST['email']);
            $password = mysqli_real_escape_string($conn, $_POST['password']);
            $role = mysqli_real_escape_string($conn, $_POST['role']);
            
            // Validasi username unik
            $username_check = mysqli_query($conn, "SELECT Username FROM users WHERE Username='$username'");
            if (mysqli_num_rows($username_check) != 0) {
                echo "<div class='message'>
                          <p>Username sudah digunakan, silakan pilih username lain!</p>
                      </div><br>";
                echo "<a href='javascript:self.history.back()'><button class='btn'>Kembali</button></a>";
            } 
            // Validasi email unik
            else {
                $verify_query = mysqli_query($conn, "SELECT Email FROM users WHERE Email='$email'");
                if (mysqli_num_rows($verify_query) != 0) {
                    echo "<div class='message'>
                              <p>Email sudah digunakan, silakan gunakan email lain!</p>
                          </div><br>";
                    echo "<a href='javascript:self.history.back()'><button class='btn'>Kembali</button></a>";
                } else {
                    // Insert user ke database
                    $hashed_password = password_hash($password, PASSWORD_DEFAULT); // Hashing password
                    $insert_query = "INSERT INTO users (username, email, password, role) VALUES ('$username', '$email', '$hashed_password', '$role')";
                    
                    if (mysqli_query($conn, $insert_query)) {
                        echo "<div class='message'>
                                  <p>Registrasi berhasil!</p>
                              </div><br>";
                        echo "<a href='login.php'><button class='btn'>Login Sekarang</button></a>";
                    } else {
                        echo "<div class='message'>
                                  <p>Terjadi kesalahan saat registrasi. Silakan coba lagi.</p>
                              </div><br>";
                    }
                }
            }
        } else {
        ?>

        <header>Sign Up</header>
        <form action="" method="post">
            <div class="field input">
                <label for="username">Username</label>
                <input type="text" name="username" id="username" autocomplete="off" required>
            </div>

            <div class="field input">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" autocomplete="off" required>
            </div>

            <div class="field input">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" autocomplete="off" required>
            </div>

            <div class="field input">
                <label for="role">Role</label>
                <select name="role" id="role" required>
                    <option value="admin">Admin</option>
                    <option value="pelanggan">Pelanggan</option>
                </select>
            </div>

            <div class="field">
                <input type="submit" class="btn" name="submit" value="Register">
            </div>
            <div class="links">
                Sudah punya akun? <a href="login.php">Sign In</a>
            </div>
        </form>
        <?php } ?>
        </div>
    </div>
</body>
</html>
