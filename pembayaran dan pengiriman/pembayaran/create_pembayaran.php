<?php
require '../db_connection.php';

if (isset($_GET['payment_id'])) {
    $paymentId = $_GET['payment_id'];
    echo "Payment created successfully. Payment ID: " . $paymentId;
} else {
    echo "Invalid payment ID.";
}
?>
