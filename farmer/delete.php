<?php
// Include the database connection
include '../db/connection.php';

// Check if the 'id' parameter is present in the URL
if (isset($_GET['id'])) {
    $farm_product_id = $_GET['id'];

    // Step 1: Check if there are any delivery requests related to this product
    $query_check_delivery = "SELECT request_id FROM delivery_request WHERE product_id = ?";
    $stmt_check_delivery = $pdo->prepare($query_check_delivery);
    $stmt_check_delivery->execute([$farm_product_id]);
    $delivery_requests = $stmt_check_delivery->fetchAll(PDO::FETCH_ASSOC);

    if (!empty($delivery_requests)) {
        // Step 2: If there are any delivery requests, delete them from the delivery_request and delivery_track tables
        
        try {
            // Start transaction
            $pdo->beginTransaction();

            // Delete related rows in the delivery_track table
            $query_delete_delivery_track = "DELETE FROM delivery_track WHERE delivery_request_id IN (SELECT request_id FROM delivery_request WHERE product_id = ?)";
            $stmt_delete_delivery_track = $pdo->prepare($query_delete_delivery_track);
            $stmt_delete_delivery_track->execute([$farm_product_id]);

            // Delete the related rows in the delivery_request table
            $query_delete_delivery_request = "DELETE FROM delivery_request WHERE product_id = ?";
            $stmt_delete_delivery_request = $pdo->prepare($query_delete_delivery_request);
            $stmt_delete_delivery_request->execute([$farm_product_id]);

            // Step 3: Delete the product from farm_product table
            $query_delete_product = "DELETE FROM farm_product WHERE farm_product_id = ?";
            $stmt_delete_product = $pdo->prepare($query_delete_product);
            $stmt_delete_product->execute([$farm_product_id]);

            // Commit transaction
            $pdo->commit();

            // Redirect back to the index page after successful deletion
            header("Location: index.php");
            exit();

        } catch (Exception $e) {
            // Rollback transaction in case of an error
            $pdo->rollBack();
            echo "<script>
                    alert('Error occurred while deleting the product and related delivery records: " . $e->getMessage() . "');
                    window.location.href = 'index.php';
                  </script>";
        }
    } else {
        // If no delivery requests exist, proceed with just deleting the product
        try {
            // Start transaction
            $pdo->beginTransaction();

            // Delete the product from farm_product table
            $query_delete_product = "DELETE FROM farm_product WHERE farm_product_id = ?";
            $stmt_delete_product = $pdo->prepare($query_delete_product);
            $stmt_delete_product->execute([$farm_product_id]);

            // Commit transaction
            $pdo->commit();

            // Redirect back to the index page after successful deletion
            header("Location: index.php");
            exit();

        } catch (Exception $e) {
            // Rollback transaction in case of an error
            $pdo->rollBack();
            echo "<script>
                    alert('Error occurred while deleting the product: " . $e->getMessage() . "');
                    window.location.href = 'index.php';
                  </script>";
        }
    }
} else {
    // If no ID is provided in the URL, redirect to the index page
    header("Location: index.php");
    exit();
}
?>
