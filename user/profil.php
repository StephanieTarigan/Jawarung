<?php
session_start();
include "../dbconfig.php";

// Periksa apakah sesi valid
if (!isset($_SESSION['valid']) || !isset($_SESSION['UserID'])) {
    // Redirect ke login jika sesi tidak valid
    header("Location: ../login.php");
    exit();
}

include "../template/main_layout.php";

// Ambil ID pengguna dari session
$id = $_SESSION['UserID'];

// Proses upload foto
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['profile_image'])) {
    // Ambil data file
    $file_name = $_FILES['profile_image']['name'];
    $file_tmp = $_FILES['profile_image']['tmp_name'];
    $file_size = $_FILES['profile_image']['size'];
    $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
    $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];

    // Validasi ekstensi file
    if (in_array($file_ext, $allowed_extensions)) {
        // Generate nama unik untuk file
        $file_new_name = uniqid('', true) . "." . $file_ext;
        $file_destination = "uploads/profile_pics/" . $file_new_name;

        // Pastikan folder uploads ada
        if (!is_dir("../template/uploads/profile_pics")) {
            mkdir("../template/uploads/profile_pics", 0777, true);
        }

        // Pindahkan file ke folder uploads
        if (move_uploaded_file($file_tmp, $file_destination)) {
            // Simpan path gambar di database
            $query = "UPDATE users SET ProfilePicture='$file_destination' WHERE UserID='$id'";
            mysqli_query($conn, $query);
        } else {
            echo "<p>Gagal mengupload file.</p>";
        }
    } else {
        echo "<p>File tidak valid. Hanya jpg, jpeg, png yang diizinkan.</p>";
    }
}

// Proses update data pengguna dan pelanggan
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    // Ambil dan sanitasi data input
    $Username = mysqli_real_escape_string($conn, $_POST['Username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $role = mysqli_real_escape_string($conn, $_POST['Role']);
    $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);
    $kabupaten = mysqli_real_escape_string($conn, $_POST['kabupaten']);
    $kota = mysqli_real_escape_string($conn, $_POST['kota']);
    $kecamatan = mysqli_real_escape_string($conn, $_POST['kecamatan']);
    $kodepos = mysqli_real_escape_string($conn, $_POST['kodepos']);

    // Update data pengguna di tabel users
    $stmt = $conn->prepare("UPDATE users SET Username=?, Email=?, role=?, Alamat=?, Kabupaten=?, Kota=?, Kecamatan=?, KodePos=? WHERE UserID=?");
    $stmt->bind_param("ssssssssi", $Username, $email, $role, $alamat, $kabupaten, $kota, $kecamatan, $kodepos, $id);

    if ($stmt->execute()) {
        echo "<div class='messrole'>
                <p>Profile Updated!</p>
              </div> <br>";
        echo "<a href='beranda.php'><button class='btn'>Go to Beranda</button></a>";
    } else {
        echo "<p>Error: Unable to update profile.</p>";
    }
    $stmt->close();
} else {
    // Ambil data pengguna untuk diisi di form
    $stmt = $conn->prepare("SELECT Username, Email, role, Alamat, Kabupaten, Kota, Kecamatan, KodePos FROM users WHERE UserID=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $res_Uname = $row['Username'];
        $res_Email = $row['Email'];
        $res_role = $row['role'];
        $res_Alamat = $row['Alamat'];
        $res_Kabupaten = $row['Kabupaten'];
        $res_Kota = $row['Kota'];
        $res_Kecamatan = $row['Kecamatan'];
        $res_KodePos = $row['KodePos'];
    } else {
        echo "<p>Error: User data not found.</p>";
        exit();
    }
    $stmt->close();
}
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

    <div class="container">
        <div class="box form-box">
            <header>Change Profile</header>
            <form action="" method="post" enctype="multipart/form-data">
                <div class="field input">
                    <label for="Username">Username</label>
                    <input type="text" name="Username" id="Username" value="<?php echo htmlspecialchars($res_Uname); ?>" autocomplete="off" required>
                </div>

                <div class="field input">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($res_Email); ?>" autocomplete="off" required>
                </div>

                <div class="field input">
                    <label for="alamat">Alamat</label>
                    <input type="text" name="alamat" id="alamat" value="<?php echo htmlspecialchars($res_Alamat); ?>" required>
                </div>

                <div class="field input">
                    <label for="kabupaten">Kabupaten</label>
                    <input type="text" name="kabupaten" id="kabupaten" value="<?php echo htmlspecialchars($res_Kabupaten); ?>" required>
                </div>

                <div class="field input">
                    <label for="kota">Kota</label>
                    <input type="text" name="kota" id="kota" value="<?php echo htmlspecialchars($res_Kota); ?>" required>
                </div>

                <div class="field input">
                    <label for="kecamatan">Kecamatan</label>
                    <input type="text" name="kecamatan" id="kecamatan" value="<?php echo htmlspecialchars($res_Kecamatan); ?>" required>
                </div>

                <div class="field input">
                    <label for="kodepos">Kode Pos</label>
                    <input type="text" name="kodepos" id="kodepos" value="<?php echo htmlspecialchars($res_KodePos); ?>" required>
                </div>

                <div class="field input">
                    <label for="profile_image">Foto Profil</label>
                    <input type="file" name="profile_image" id="profile_image">
                </div>

                <div class="field">
                    <input type="submit" class="btn" name="submit" value="Update">
                </div>

                <!-- Tombol Log Out -->
                <div class="field">
                    <a href="logout.php" class="logout-btn">Log Out</a>
                </div>

            </form>

        </div>
    </div>
</body>

</html>