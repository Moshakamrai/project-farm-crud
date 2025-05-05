<?php
require_once '../db/connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Insert the new product data into the product table
    $insert_product = $pdo->prepare("INSERT INTO product (product_name, product_type, shelf_life, ideal_temp, humidity, mrp) 
                                      VALUES (?, ?, ?, ?, ?, ?)");
    $insert_product->execute([$_POST['product_name'], $_POST['product_type'], $_POST['shelf_life'], $_POST['ideal_temp'], $_POST['humidity'], $_POST['mrp']]);

    // Fetch the product_id of the newly added product
    $product_id = $pdo->lastInsertId();

    // Insert product data into the farm_product table, including all necessary fields
    $stmt = $pdo->prepare("INSERT INTO farm_product (product_id, quantity, grade, shelf_life, ideal_temp, humidity) 
                           VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([
        $product_id, 
        $_POST['quantity'], 
        $_POST['grade'], 
        $_POST['shelf_life'], // Add shelf_life
        $_POST['ideal_temp'], // Add ideal_temp
        $_POST['humidity']    // Add humidity
    ]);

    header("Location: index.php");
    exit;
}

// Fetch products for the select field
$query = "SELECT product_id, product_name FROM product";
$stmt = $pdo->prepare($query);
$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head><title>Add Product</title></head>
<body>
<h1>Add New Product</h1>
<link rel="stylesheet" href="../css/style.css">
<form method="post">
    <label for="product_name">Product Name:</label>
    <input name="product_name" type="text" required><br><br>

    <label for="product_type">Product Type:</label>
    <input name="product_type" type="text" required><br><br>

    <label for="shelf_life">Shelf Life (days):</label>
    <input name="shelf_life" type="number" required><br><br>

    <label for="ideal_temp">Ideal Temperature (°C):</label>
    <input name="ideal_temp" type="number" required><br><br>

    <label for="humidity">Humidity (%):</label>
    <input name="humidity" type="number" required><br><br>

    <label for="mrp">MRP (Price):</label>
    <input name="mrp" type="number" step="0.01" required><br><br>

    <label for="quantity">Quantity:</label>
    <input name="quantity" type="number" required><br><br>

    <label for="grade">Grade:</label>
    <input name="grade" type="text" required><br><br>

    <button type="submit">Add Product</button>
</form>

</body>
</html>
