<?php
// Include the database connection
include '../db/connection.php';

// Fetch all products available in the warehouse
$query = "SELECT p.farm_product_id, p.grade, w.warehouse_id, wi.quantity, pr.product_name, pr.product_type, w.location_subdistrict
          FROM warehouse_inventory wi
          INNER JOIN farm_product p ON wi.farm_product_id = p.farm_product_id
          INNER JOIN product pr ON p.product_id = pr.product_id
          INNER JOIN warehouse w ON wi.warehouse_id = w.warehouse_id";

$stmt = $pdo->prepare($query);
$stmt->execute();

// Fetch all rows
$products_in_warehouse = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Warehouse Manager Dashboard</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

    <h1>Warehouse Manager Dashboard</h1>

    <h2>Warehouse Inventory</h2>
    <table border="1">
        <thead>
            <tr>
                <th>Product Name</th>
                <th>Product Type</th>
                <th>Grade</th>
                <th>Warehouse Location</th>
                <th>Quantity in Warehouse</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($products_in_warehouse): ?>
                <?php foreach ($products_in_warehouse as $product): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($product['product_name']); ?></td>
                        <td><?php echo htmlspecialchars($product['product_type']); ?></td>
                        <td><?php echo htmlspecialchars($product['grade']); ?></td>
                        <td><?php echo htmlspecialchars($product['location_subdistrict']); ?></td>
                        <td><?php echo htmlspecialchars($product['quantity']); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5">No products available in the warehouse.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <h2>Manage Warehouse</h2>
    
    <a href="add_inventory.php">Add New Warehouse</a><br><br>

    <h3>Warehouse Details</h3>
    <table border="1">
        <thead>
            <tr>
                <th>Warehouse Location</th>
                <th>Capacity</th>
                <th>Contact Email</th>
                <th>Contact Phone</th>
            </tr>
        </thead>
        <tbody>
            <?php
                // Fetch all warehouse details
                $warehouse_query = "SELECT * FROM warehouse";
                $stmt_warehouse = $pdo->prepare($warehouse_query);
                $stmt_warehouse->execute();
                $warehouses = $stmt_warehouse->fetchAll(PDO::FETCH_ASSOC);

                if ($warehouses):
                    foreach ($warehouses as $warehouse): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($warehouse['location_subdistrict']); ?></td>
                            <td><?php echo htmlspecialchars($warehouse['storage_capacity']); ?></td>
                            <td><?php echo htmlspecialchars($warehouse['contact_info_email']); ?></td>
                            <td><?php echo htmlspecialchars($warehouse['contact_info_phone']); ?></td>
                        </tr>
                    <?php endforeach; 
                else: ?>
                    <tr>
                        <td colspan="4">No warehouse data available.</td>
                    </tr>
                <?php endif; ?>
        </tbody>
    </table>

</body>
</html>
