<?php
// Include the database connection
include '../db/connection.php';

// Fetch all products and their quantities from farm_product
$query = "SELECT p.product_id, p.product_name, p.product_type, fp.farm_product_id, fp.quantity, fp.grade, p.shelf_life, p.ideal_temp, p.humidity, p.mrp
          FROM product p
          INNER JOIN farm_product fp ON p.product_id = fp.product_id";
$stmt = $pdo->prepare($query);
$stmt->execute();

$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch warehouses for the delivery form
$query_warehouses = "SELECT warehouse_id, location_subdistrict FROM warehouse";
$stmt_warehouses = $pdo->prepare($query_warehouses);
$stmt_warehouses->execute();
$warehouses = $stmt_warehouses->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Farmer Dashboard</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

    <h1>Farmer Dashboard</h1>

    <!-- Add New Product Button -->
    <a href="add.php"><button>Add New Product</button></a><br><br>

    <!-- Delivery Form Button -->
    <a href="add_delivery.php"><button>Request Product Delivery to Warehouse</button></a><br><br>

    <!-- Product Table -->
    <h2>Product List</h2>
    <table border="1">
        <thead>
            <tr>
                <th>Product ID</th> <!-- New column for Product ID -->
                <th>Product Name</th>
                <th>Product Type</th>
                <th>Shelf Life (days)</th>
                <th>Ideal Temperature (°C)</th>
                <th>Humidity (%)</th>
                <th>MRP</th>
                <th>Quantity</th>
                <th>Grade</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($products as $product): ?>
                <tr>
                    <td><?php echo htmlspecialchars($product['product_id']); ?></td> <!-- Display Product ID -->
                    <td><?php echo htmlspecialchars($product['product_name']); ?></td>
                    <td><?php echo htmlspecialchars($product['product_type']); ?></td>
                    <td><?php echo htmlspecialchars($product['shelf_life']); ?></td>
                    <td><?php echo htmlspecialchars($product['ideal_temp']); ?></td>
                    <td><?php echo htmlspecialchars($product['humidity']); ?></td>
                    <td><?php echo htmlspecialchars($product['mrp']); ?></td>
                    <td><?php echo htmlspecialchars($product['quantity']); ?></td>
                    <td><?php echo htmlspecialchars($product['grade']); ?></td>
                    <td>
                        <a href="edit.php?id=<?php echo $product['farm_product_id']; ?>">Edit</a> | 
                        <a href="delete.php?id=<?php echo $product['farm_product_id']; ?>" onclick="return confirm('Are you sure you want to delete this product?')">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <br><br>

</body>
</html>
