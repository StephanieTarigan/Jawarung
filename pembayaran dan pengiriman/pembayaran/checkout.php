<?php
session_start();
require '../db_connection.php'; 


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_SESSION['user_id']; 
    $amount = $_POST['amount'];

   
    $stmt = $conn->prepare("INSERT INTO payments (user_id, amount) VALUES (?, ?)");
    $stmt->bind_param("id", $userId, $amount);
    if ($stmt->execute()) {
        header("Location: create_pembayaran.php?payment_id=" . $conn->insert_id);
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>
<!-- HTML Form -->
<form method="POST" action="">
    <label for="amount">Total Amount:</label>
    <input type="number" name="amount" required>
    <button type="submit">Checkout</button>
</form>
