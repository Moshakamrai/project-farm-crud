<?php
// Include the database connection
include '../db/connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the values from the form
    $farm_product_id = $_POST['farm_product_id'];
    $quantity = $_POST['quantity'];
    $warehouse_name = $_POST['warehouse_name']; // Name of the warehouse
    $delivery_date = $_POST['delivery_date'];

    // Step 1: Fetch the warehouse_id from the warehouse name
    $query_warehouse = "SELECT warehouse_id FROM warehouse WHERE location_subdistrict = ?";
    $stmt_warehouse = $pdo->prepare($query_warehouse);
    $stmt_warehouse->execute([$warehouse_name]);
    $warehouse_id = $stmt_warehouse->fetchColumn();

    // Step 2: Fetch the farm_id associated with the farm_product_id
    $query_farm_id = "SELECT farm_id FROM farm_product WHERE farm_product_id = ?";
    $stmt_farm_id = $pdo->prepare($query_farm_id);
    $stmt_farm_id->execute([$farm_product_id]);
    $farm_id = $stmt_farm_id->fetchColumn();

    // Step 3: Insert the delivery request into the delivery_request table
    $delivery_request_query = "INSERT INTO delivery_request (product_id, quantity, status, request_date, farm_id) 
                               VALUES (?, ?, ?, ?, ?)";
    $stmt_delivery_request = $pdo->prepare($delivery_request_query);
    $stmt_delivery_request->execute([ 
        $farm_product_id, 
        $quantity, 
        'Pending', // Default status
        $delivery_date,
        $farm_id // Include farm_id in the request
    ]);
    
    // Get the last inserted request_id
    $delivery_request_id = $pdo->lastInsertId();

    // Step 4: Insert the delivery track into the delivery_track table
    $delivery_track_query = "INSERT INTO delivery_track (quantity, delivery_request_id, warehouse_id, 
                            product_id, status, delivery_date, farm_id) 
                            VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt_delivery_track = $pdo->prepare($delivery_track_query);
    $stmt_delivery_track->execute([
        $quantity,
        $delivery_request_id,
        $warehouse_id,
        $farm_product_id,
        'Pending', // Default status
        $delivery_date,
        $farm_id // Include farm_id in the delivery track
    ]);

    // Step 5: Update the quantity in the farm_product table (reduce by the quantity delivered)
    $update_quantity_query = "UPDATE farm_product SET quantity = quantity - :quantity WHERE farm_product_id = :farm_product_id";
    $stmt_update_quantity = $pdo->prepare($update_quantity_query);
    $stmt_update_quantity->bindParam(':quantity', $quantity);
    $stmt_update_quantity->bindParam(':farm_product_id', $farm_product_id);
    $stmt_update_quantity->execute();

    // Redirect after success
    header("Location: index.php");
    exit;
}

// Fetch all products and their quantities from farm_product
$query = "SELECT p.product_id, p.product_name, p.product_type, fp.farm_product_id, fp.quantity, fp.grade, 
          p.shelf_life, p.ideal_temp, p.humidity, p.mrp
          FROM product p
          INNER JOIN farm_product fp ON p.product_id = fp.product_id";
$stmt = $pdo->prepare($query);
$stmt->execute();

$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch all warehouses for the warehouse dropdown
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
    <title>Farmer - Request Product Delivery</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<h1>Request Product Delivery to Warehouse</h1>

<form method="POST">
    <!-- Product Selection -->
    <label for="farm_product_id">Product:</label>
    <select name="farm_product_id" id="farm_product_id" required>
        <option value="">Select Product</option>
        <?php foreach ($products as $product): ?>
            <option value="<?php echo $product['farm_product_id']; ?>">
                <?php echo $product['product_name'] . ' (' . $product['grade'] . ')'; ?>
            </option>
        <?php endforeach; ?>
    </select><br><br>

    <!-- Quantity -->
    <label for="quantity">Quantity:</label>
    <input name="quantity" type="number" min="1" required><br><br>

    <!-- Warehouse Selection -->
    <label for="warehouse_name">Warehouse:</label>
    <select name="warehouse_name" id="warehouse_name" required>
        <option value="">Select Warehouse</option>
        <?php foreach ($warehouses as $warehouse): ?>
            <option value="<?php echo $warehouse['location_subdistrict']; ?>">
                <?php echo $warehouse['location_subdistrict']; ?>
            </option>
        <?php endforeach; ?>
    </select><br><br>

    <!-- Delivery Date -->
    <label for="delivery_date">Delivery Date:</label>
    <input name="delivery_date" type="date" required><br><br>

    <button type="submit">Request Delivery</button>
</form>

</body>
</html>
