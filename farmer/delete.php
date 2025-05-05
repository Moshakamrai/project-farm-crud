<?php
// Include the database connection
include '../db/connection.php';

// Get the farm_product_id from the URL
$id = $_GET['id'];

// Prepare the delete statement for farm_product table
$delete_farm_product = $pdo->prepare("DELETE FROM farm_product WHERE farm_product_id = ?");
$delete_farm_product->execute([$id]);

// After deleting from farm_product, delete from product table
$delete_product = $pdo->prepare("DELETE FROM product WHERE product_id = (SELECT product_id FROM farm_product WHERE farm_product_id = ?)");
$delete_product->execute([$id]);

// Redirect back to index.php after successful deletion
header("Location: index.php");
exit();
?>
