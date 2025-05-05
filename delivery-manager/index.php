<?php
// Include the database connection
require_once '../db/connection.php';

// Fetch all delivery requests along with the farm and warehouse details
$query = "
    SELECT 
        dr.request_id, 
        dr.quantity AS requested_quantity, 
        dr.status AS request_status,
        dr.request_date, 
        p.product_name, 
        p.product_type, 
        f.farm_name, 
        f.location_subdistrict AS farm_location, 
        w.location_subdistrict AS warehouse_location,
        dt.quantity AS delivered_quantity, 
        dt.status AS delivery_status, 
        dt.delivery_date 
    FROM 
        delivery_request dr
    INNER JOIN product p ON dr.product_id = p.product_id
    INNER JOIN farm_product fp ON fp.product_id = p.product_id
    INNER JOIN farm f ON f.farm_id = fp.farm_id
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
                    <a href="edit_delivery.php?id=<?php echo $data['request_id']; ?>">Edit Status</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

</body>
</html>
