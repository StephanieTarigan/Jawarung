<?php
require '../dbconfig.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['payment_id'])) {
    $paymentId = $_GET['payment_id'];

    $stmt = $conn->prepare("SELECT * FROM payments WHERE id = ?");
    $stmt->bind_param("i", $paymentId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $payment = $result->fetch_assoc();
        echo "Payment ID: " . $payment['id'] . "<br>";
        echo "Status: " . $payment['status'] . "<br>";
    } else {
        echo "No payment found.";
    }
}
?>
