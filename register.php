<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --bs-success: #4a7345; /* Custom green color */
            --bs-success-rgb: 74, 115, 69;
        }

        .btn-success {
            background-color: var(--bs-success) !important;
            border-color: var(--bs-success) !important;
        }

        .btn-success:hover {
            background-color: #3b5c36 !important;
            border-color: #3b5c36 !important;
        }

        .bg-success {
            background-color: var(--bs-success) !important;
        }

        .text-success {
            color: var(--bs-success) !important;
        }

        .border-success {
            border-color: var(--bs-success) !important;
        }
    </style>
</head>

<body class="bg-light">
    <div class="container d-flex align-items-center justify-content-center vh-100">
        <div class="row w-100 shadow-lg rounded overflow-hidden" style="max-width: 900px;">
            <!-- Left Side -->
            <div class="col-md-6 bg-success text-white p-4 d-flex flex-column justify-content-center">
                <h2 class="fw-bold">Join Us Today!</h2>
                <p>Create an account to get started on a journey to better manage your tasks and goals.</p>
                <img src="signup-image-placeholder.png" alt="Signup Info" class="img-fluid mt-3">
            </div>
            <!-- Right Side -->
            <div class="col-md-6 bg-white p-5">
                <h3 class="text-center mb-4 text-success">Sign Up</h3>
                <?php 
                include("dbconfig.php");
                if (isset($_POST['submit'])) {
                    $username = mysqli_real_escape_string($conn, $_POST['username']);
                    $email = mysqli_real_escape_string($conn, $_POST['email']);
                    $password = mysqli_real_escape_string($conn, $_POST['password']);

                    // Check for unique username and email
                    $username_check = mysqli_query($conn, "SELECT Username FROM users WHERE Username='$username'");
                    if (mysqli_num_rows($username_check) != 0) {
                        echo "<div class='alert alert-danger'>Username already taken, please choose another one.</div>";
                    } else {
                        $email_check = mysqli_query($conn, "SELECT Email FROM users WHERE Email='$email'");
                        if (mysqli_num_rows($email_check) != 0) {
                            echo "<div class='alert alert-danger'>Email already registered, please use another one.</div>";
                        } else {
                            // Insert user with "user" role
                            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                            $insert_query = "INSERT INTO users (username, email, password, role) VALUES ('$username', '$email', '$hashed_password', 'pelanggan')";
                            if (mysqli_query($conn, $insert_query)) {
                                echo "<div class='alert alert-success'>Registration successful! <a href='login.php'>Login Now</a></div>";
                            } else {
                                echo "<div class='alert alert-danger'>An error occurred. Please try again.</div>";
                            }
                        }
                    }
                } else {
                ?>
                <form action="" method="post">
                    <div class="mb-3">
                        <label for="username" class="form-label text-success">Username</label>
                        <input type="text" name="username" id="username" class="form-control border-success" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label text-success">Email</label>
                        <input type="email" name="email" id="email" class="form-control border-success" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label text-success">Password</label>
                        <input type="password" name="password" id="password" class="form-control border-success" required>
                    </div>
                    <div class="mb-3">
                        <button type="submit" name="submit" class="btn btn-success w-100 text-white">Register</button>
                    </div>
                    <div class="text-center">
                        <p>Already have an account? <a href="login.php" class="text-success">Sign In</a></p>
                    </div>
                </form>
                <?php } ?>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
