<?php
// Include the database connection
include '../db/connection.php';

// Get the farm_product_id from the URL
$id = $_GET['id'];

// Fetch data from product and farm_product tables based on the farm_product_id
$query = "
    SELECT p.product_name, p.product_type, p.shelf_life, p.ideal_temp, p.humidity, p.mrp, fp.quantity, fp.grade, fp.product_id
    FROM farm_product fp
    INNER JOIN product p ON fp.product_id = p.product_id
    WHERE fp.farm_product_id = ?";
$stmt = $pdo->prepare($query);
$stmt->execute([$id]);

$product = $stmt->fetch(PDO::FETCH_ASSOC);

// If the product doesn't exist, redirect to index
if (!$product) {
    header("Location: index.php");
    exit();
}

// Fetch all products for the select dropdown
$query_products = "SELECT product_id, product_name FROM product";
$stmt_products = $pdo->prepare($query_products);
$stmt_products->execute();
$products = $stmt_products->fetchAll(PDO::FETCH_ASSOC);

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the form data
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];
    $grade = $_POST['grade'];

    // Update product and quantity in farm_product table
    $update = $pdo->prepare("
        UPDATE farm_product
        SET product_id = ?, quantity = ?, grade = ?
        WHERE farm_product_id = ?");
    $update->execute([$product_id, $quantity, $grade, $id]);

    // Redirect back to index page
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<h1>Edit Product</h1>

<form method="POST">
    <label for="product_name">Product Name:</label>
    <select name="product_id" required>
        <?php foreach ($products as $option): ?>
            <option value="<?php echo $option['product_id']; ?>" <?php echo $option['product_id'] == $product['product_id'] ? 'selected' : ''; ?>>
                <?php echo htmlspecialchars($option['product_name']); ?>
            </option>
        <?php endforeach; ?>
    </select><br><br>

    <label for="product_type">Product Type:</label>
    <input name="product_type" type="text" value="<?php echo htmlspecialchars($product['product_type']); ?>" disabled><br><br>

    <label for="shelf_life">Shelf Life (days):</label>
    <input name="shelf_life" type="number" value="<?php echo htmlspecialchars($product['shelf_life']); ?>" disabled><br><br>

    <label for="ideal_temp">Ideal Temperature (°C):</label>
    <input name="ideal_temp" type="number" value="<?php echo htmlspecialchars($product['ideal_temp']); ?>" disabled><br><br>

    <label for="humidity">Humidity (%):</label>
    <input name="humidity" type="number" value="<?php echo htmlspecialchars($product['humidity']); ?>" disabled><br><br>

    <label for="mrp">MRP (Price):</label>
    <input name="mrp" type="number" step="0.01" value="<?php echo htmlspecialchars($product['mrp']); ?>" disabled><br><br>

    <label for="quantity">Quantity:</label>
    <input name="quantity" type="number" value="<?php echo htmlspecialchars($product['quantity']); ?>" required><br><br>

    <label for="grade">Grade:</label>
    <input name="grade" type="text" value="<?php echo htmlspecialchars($product['grade']); ?>" required><br><br>

    <button type="submit">Update Product</button>
</form>

</body>
</html>
