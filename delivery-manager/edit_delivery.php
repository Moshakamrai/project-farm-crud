<?php
// Include the database connection
require_once '../db/connection.php';

// Get the request_id from the URL parameter
$request_id = $_GET['id'];

// Fetch the delivery request details using the request_id
$query = "
    SELECT 
        dr.request_id,
        dr.quantity AS requested_quantity, 
        dr.status AS request_status, 
        dr.request_date, 
        p.product_name, 
        f.farm_name, 
        f.farm_id, 
        f.location_subdistrict AS farm_location, 
        w.location_subdistrict AS warehouse_location, 
        dt.status AS delivery_status,
        dt.delivery_date
    FROM 
        delivery_request dr
    LEFT JOIN farm_product fp ON dr.product_id = fp.farm_product_id
    LEFT JOIN product p ON fp.product_id = p.product_id
    LEFT JOIN farm f ON dr.farm_id = f.farm_id
    LEFT JOIN delivery_track dt ON dr.request_id = dt.delivery_request_id
    LEFT JOIN warehouse w ON dt.warehouse_id = w.warehouse_id
    WHERE dr.request_id = ?
";
$stmt = $pdo->prepare($query);
$stmt->execute([$request_id]);
$delivery_data = $stmt->fetch(PDO::FETCH_ASSOC);

// Check if the delivery data was fetched
if (!$delivery_data) {
    echo "No data found for this request.";
    exit;
}

// Handle form submission to update the status
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_status = $_POST['delivery_status'];

    // Update the delivery status in the delivery_request table
    $update_request = "
        UPDATE delivery_request
        SET status = ?
        WHERE request_id = ?
    ";
    $stmt_update_request = $pdo->prepare($update_request);
    $stmt_update_request->execute([$new_status, $request_id]);

    // Update the delivery status in the delivery_track table
    $update_track = "
        UPDATE delivery_track
        SET status = ?
        WHERE delivery_request_id = ?
    ";
    $stmt_update_track = $pdo->prepare($update_track);
    $stmt_update_track->execute([$new_status, $request_id]);

    // Redirect to the delivery manager page after update
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Delivery Status</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<h1>Edit Delivery Status</h1>
<link rel="stylesheet" href="../css/style.css">
<form method="POST">
    <label for="farm_name">Farm Name:</label>
    <input type="text" id="farm_name" value="<?php echo htmlspecialchars($delivery_data['farm_name']); ?>" disabled><br><br>

    <label for="product_name">Product Name:</label>
    <input type="text" id="product_name" value="<?php echo htmlspecialchars($delivery_data['product_name']); ?>" disabled><br><br>

    <label for="requested_quantity">Requested Quantity:</label>
    <input type="text" id="requested_quantity" value="<?php echo htmlspecialchars($delivery_data['requested_quantity']); ?>" disabled><br><br>

    <label for="warehouse_location">Warehouse Location:</label>
    <input type="text" id="warehouse_location" value="<?php echo htmlspecialchars($delivery_data['warehouse_location']); ?>" disabled><br><br>

    <label for="delivery_date">Delivery Date:</label>
    <input type="text" id="delivery_date" value="<?php echo htmlspecialchars($delivery_data['delivery_date']); ?>" disabled><br><br>

    <label for="delivery_status">Delivery Status:</label>
    <select name="delivery_status" id="delivery_status" required>
        <option value="Pending" <?php echo $delivery_data['delivery_status'] == 'Pending' ? 'selected' : ''; ?>>Pending</option>
        <option value="Dispatched" <?php echo $delivery_data['delivery_status'] == 'Dispatched' ? 'selected' : ''; ?>>Dispatched</option>
        <option value="Delivered" <?php echo $delivery_data['delivery_status'] == 'Delivered' ? 'selected' : ''; ?>>Delivered</option>
        
    </select><br><br>

    <button type="submit">Update Status</button>
</form>

</body>
</html>
