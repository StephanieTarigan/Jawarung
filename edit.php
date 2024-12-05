<?php 
session_start();
include("dbconfig.php");

// Cek apakah pengguna sudah login
if (!isset($_SESSION['valid'])) {
    header("Location: login.php");
    exit();
}

// Ambil ID pengguna dari session
$id = $_SESSION['UserID'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Change Profile</title>
</head>
<body>
        <div class="right-links">
            <a href="#">Change Profile</a>
            <a href="logout.php"><button class="btn">Log Out</button></a>
        </div>
    </div>

    <div class="container">
        <div class="box form-box">
            <?php 
            if (isset($_POST['submit'])) {
                // Ambil dan sanitasi data input
                $username = mysqli_real_escape_string($conn, $_POST['username']);
                $email = mysqli_real_escape_string($conn, $_POST['email']);
                $role = mysqli_real_escape_string($conn, $_POST['role']);

                // Update data pengguna
                $stmt = $conn->prepare("UPDATE users SET Username=?, Email=?, role=? WHERE UserID=?");
                $stmt->bind_param("sssi", $username, $email, $role, $id);

                if ($stmt->execute()) {
                    echo "<div class='messrole'>
                            <p>Profile Updated!</p>
                          </div> <br>";
                    echo "<a href='beranda.php'><button class='btn'>Go beranda</button></a>";
                } else {
                    echo "<p>Error: Unable to update profile.</p>";
                }
                $stmt->close();
            } else {
                // Ambil data pengguna untuk diisi di form
                $stmt = $conn->prepare("SELECT Username, Email, role FROM users WHERE UserID=?");
                $stmt->bind_param("i", $id);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($row = $result->fetch_assoc()) {
                    $res_Uname = $row['Username'];
                    $res_Email = $row['Email'];
                    $res_role = $row['role'];
                } else {
                    echo "<p>Error: User data not found.</p>";
                    exit();
                }
                $stmt->close();
            ?>
            <header>Change Profile</header>
            <form action="" method="post">
                <div class="field input">
                    <label for="username">Username</label>
                    <input type="text" name="username" id="username" value="<?php echo htmlspecialchars($res_Uname); ?>" autocomplete="off" required>
                </div>

                <div class="field input">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($res_Email); ?>" autocomplete="off" required>
                </div>

                <div class="field input">
                    <label for="role">Role</label>
                    <select name="role" id="role" required>
                        <option value="admin" <?php if ($res_role == 'admin') echo 'selected'; ?>>Admin</option>
                        <option value="user" <?php if ($res_role == 'pelanggan') echo 'selected'; ?>>Pelanggan</option>
                    </select>
                </div>
                
                <div class="field">
                    <input type="submit" class="btn" name="submit" value="Update">
                </div>
            </form>
            <?php } ?>
        </div>
    </div>
</body>
</html>
