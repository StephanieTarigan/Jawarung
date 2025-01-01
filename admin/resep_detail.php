<?php
include "../dbconfig.php";

$resepID = $_GET['ResepID'];
$query = "
    SELECT r.NamaResep, r.DeskripsiResep, r.LinkVideoTutorial, fr.FotoPath 
    FROM resep r
    LEFT JOIN fotoresep fr ON r.ResepID = fr.ResepID
    WHERE r.ResepID = '$resepID'
";
$result = $conn->query($query);
$resep = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($resep['NamaResep']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h2><?= htmlspecialchars($resep['NamaResep']) ?></h2>
        <p><?= htmlspecialchars($resep['DeskripsiResep']) ?></p>
        <?php if (!empty($resep['FotoPath'])): ?>
            <img src="../uploads/foto_resep/<?= $resep['FotoPath'] ?>" alt="Foto Resep" style="width: 100%;">
        <?php endif; ?>
        <a href="<?= $resep['LinkVideoTutorial'] ?>" target="_blank" class="btn btn-primary">Tonton Video</a>
    </div>
</body>
</html>
