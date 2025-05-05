<?php
require_once '../db/connection.php';

// Get the request ID from the URL
$id = $_GET['id'];

// Fetch the current delivery request details
$stmt = $pdo->prepare("SELECT * FROM delivery_request WHERE request_id = ?");
$stmt->execute([$id]);
$request = $stmt->fetch();

// If the form is submitted, update the delivery status
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $status = $_POST['delivery_status'];

    // Update the status in the delivery_track table
    $update = $pdo->prepare("UPDATE delivery_track SET status = ? WHERE delivery_request_id = ?");
    $update->execute([$status, $id]);

    header("Location: index.php");  // Redirect back to the delivery manager dashboard
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Delivery Status</title>
</head>
<body>

<h1>Edit Delivery Status</h1>

<form method="post">
    <label for="delivery_status">Delivery Status:</label>
    <select name="delivery_status" id="delivery_status" required>
        <option value="Pending" <?php echo ($request['status'] == 'Pending') ? 'selected' : ''; ?>>Pending</option>
        <option value="In Progress" <?php echo ($request['status'] == 'In Progress') ? 'selected' : ''; ?>>In Progress</option>
        <option value="Delivered" <?php echo ($request['status'] == 'Delivered') ? 'selected' : ''; ?>>Delivered</option>
    </select><br><br>

    <button type="submit">Update Status</button>
</form>

</body>
</html>
