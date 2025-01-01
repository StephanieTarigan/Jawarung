<?php
require '../db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $paymentId = $_POST['payment_id'];

    $stmt = $conn->prepare("INSERT INTO shipments (payment_id) VALUES (?)");
    $stmt->bind_param("i", $paymentId);
    if ($stmt->execute()) {
        echo "Shipment created with ID: " . $conn->insert_id;
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>
<!-- HTML Form -->
<form method="POST" action="">
    <input type="hidden" name="payment_id" value="1"> 
    <button type="submit">Create Shipment</button>
</form>
