<?php
require '../db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $shipmentId = $_POST['shipment_id'];
    $newStatus = $_POST['status'];

    $stmt = $conn->prepare("UPDATE shipments SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $newStatus, $shipmentId);
    if ($stmt->execute()) {
        echo "Shipment status updated.";
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>
<!-- HTML Form -->
<form method="POST" action="">
    <input type="hidden" name="shipment_id" value="1"> <!-- Example ID -->
    <label for="status">Update Status:</label>
    <select name="status">
        <option value="processing">Processing</option>
        <option value="shipped">Shipped</option>
        <option value="delivered">Delivered</option>
    </select>
    <button type="submit">Update</button>
</form>
