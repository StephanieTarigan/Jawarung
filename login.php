<?php
session_start();
include("dbconfig.php");

if (isset($_SESSION['valid'])) {
    $role = $_SESSION['role'];
    if ($role === 'admin') {
        header("Location: ../admin/beranda.php");
    } else {
        header("Location: ../user/beranda.php");
    }
    exit();
}

if (isset($_POST['submit'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    $result = mysqli_query($conn, "SELECT * FROM users WHERE Email='$email'");
    $row = mysqli_fetch_assoc($result);

    if ($row) {
        if (password_verify($password, $row['Password'])) {
            $_SESSION['valid'] = $row['Email'];
            $_SESSION['UserID'] = $row['UserID'];
            $_SESSION['Username'] = $row['Username'];
            $_SESSION['role'] = $row['role'];

            // Redirect based on role
            if ($_SESSION['role'] === 'admin') {
                header("Location: admin/beranda.php");
            } else {
                header("Location: user/beranda.php");
            }
            exit();
        } else {
            $error_message = "Incorrect password. Please try again.";
        }
    } else {
        $error_message = "Email not found. Please register first.";
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .bg-custom-green {
            background-color: #4a7345 !important;
        }

        .text-custom-green {
            color: #4a7345 !important;
        }

        .border-custom-green {
            border-color: #4a7345 !important;
        }

        .btn-custom-green {
            background-color: #4a7345 !important;
            border-color: #4a7345 !important;
        }

        .btn-custom-green:hover {
            background-color: #3b5c36 !important;
            border-color: #3b5c36 !important;
        }
    </style>
</head>

<body class="bg-light">
    <div class="container d-flex align-items-center justify-content-center vh-100">
        <div class="row w-100 shadow-lg rounded overflow-hidden" style="max-width: 900px;">
            <!-- Left Side -->
            <div class="col-md-6 bg-custom-green text-white p-4 d-flex flex-column justify-content-center">
                <h2 class="fw-bold">Welcome to Jawarung</h2>
                <p>A balanced diet and smart stats will fill your life with happiness and joy.</p>
                <img src="stats-image-placeholder.png" alt="Stats Info" class="img-fluid mt-3">
            </div>
            <!-- Right Side -->
            <div class="col-md-6 bg-white p-5">
                <h3 class="text-center mb-4 text-custom-green">Hello Again!</h3>
                <?php if (isset($error_message)): ?>
                    <div class="alert alert-danger" role="alert">
                        <?= $error_message; ?>
                    </div>
                <?php endif; ?>
                <form method="post">
                    <div class="mb-3">
                        <label for="email" class="form-label text-custom-green">Email</label>
                        <input type="email" class="form-control border-custom-green" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label text-custom-green">Password</label>
                        <input type="password" class="form-control border-custom-green" id="password" name="password" required>
                    </div>
                    <div class="mb-3">
                        <button type="submit" name="submit" class="btn btn-custom-green w-100 text-white">Login</button>
                    </div>
                    <div class="text-center">
                        <p>Don't have an account? <a href="register.php" class="text-custom-green">Sign Up</a></p>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
