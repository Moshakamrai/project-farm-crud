<?php
// Include the database connection
include '../db/connection.php';

// Get the warehouse_inventory_id from the URL
$warehouse_inventory_id = $_GET['id'];

// Prepare the delete query
$query = "DELETE FROM warehouse_inventory WHERE warehouse_inventory_id = :warehouse_inventory_id";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':warehouse_inventory_id', $warehouse_inventory_id);

// Execute the query
if ($stmt->execute()) {
    // Redirect to the index page after successful deletion
    header('Location: index.php');
    exit();
} else {
    echo 'Error: Could not delete the product from the warehouse inventory.';
}
