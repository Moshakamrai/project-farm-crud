<?php
// Include the database connection
require_once '../db/connection.php';

// Fetch all delivery requests along with the farm and product details
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
        dt.quantity AS delivered_quantity, 
        dt.status AS delivery_status,  -- Delivery status from delivery_track
        dt.delivery_date  -- Delivery date from delivery_track
    FROM 
        delivery_request dr
    LEFT JOIN farm_product fp ON dr.product_id = fp.farm_product_id
    LEFT JOIN product p ON fp.product_id = p.product_id
    LEFT JOIN farm f ON dr.farm_id = f.farm_id
    LEFT JOIN delivery_track dt ON dr.request_id = dt.delivery_request_id
    LEFT JOIN warehouse w ON dt.warehouse_id = w.warehouse_id
";

$stmt = $pdo->prepare($query);
$stmt->execute();
$delivery_data = $stmt->fetchAll(PDO::FETCH_ASSOC);


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delivery Manager Dashboard</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<h1>Delivery Manager Dashboard</h1>

<!-- Check if the delivery data is fetched successfully -->
<?php if (!empty($delivery_data)): ?>
    <p>Delivery data fetched successfully.</p>
<?php else: ?>
    <p>No delivery data found. Check the database and query.</p>
<?php endif; ?>

<!-- Delivery Requests Table -->
<table border="1">
    <thead>
        <tr>
            <th>Farm Name</th>
            <th>Product Name</th>
            <th>Requested Quantity</th>
            <th>Warehouse Location</th>
            <th>Delivery Date</th>
            <th>Delivery Status</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($delivery_data as $data): ?>
            <tr>
                <td><?php echo htmlspecialchars($data['farm_name']); ?></td>
                <td><?php echo htmlspecialchars($data['product_name']); ?></td>
                <td><?php echo htmlspecialchars($data['requested_quantity']); ?></td>
                <td><?php echo htmlspecialchars($data['warehouse_location']); ?></td>
                <td><?php echo htmlspecialchars($data['delivery_date']); ?></td>
                <td><?php echo htmlspecialchars($data['delivery_status']); ?></td>
                <td>
                    <?php if ($data['delivery_status'] == 'Pending' || $data['delivery_status'] == 'Dispatched'): ?>
                        <a href="edit_delivery.php?id=<?php echo $data['request_id']; ?>">Edit Status</a>
                    <?php else: ?>
                        <!-- If status is Delivered or Cancelled, don't show the button -->
                        Status Finalized
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

</body>
</html>