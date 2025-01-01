<?php
require '../db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $paymentId = $_POST['payment_id'];
    $newStatus = $_POST['status'];

    $stmt = $conn->prepare("UPDATE payments SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $newStatus, $paymentId);
    if ($stmt->execute()) {
        echo "Payment status updated.";
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>
<!-- HTML Form -->
<form method="POST" action="">
    <input type="hidden" name="payment_id" value="1"> <!-- Example ID -->
    <label for="status">Update Status:</label>
    <select name="status">
        <option value="pending">Pending</option>
        <option value="completed">Completed</option>
        <option value="failed">Failed</option>
    </select>
    <button type="submit">Update</button>
</form>
