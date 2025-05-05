<?php
// Include the database connection
include '../db/connection.php';

// Fetch the warehouse data for editing
if (isset($_GET['id'])) {
    $warehouse_id = $_GET['id'];
    $query = "SELECT * FROM warehouse WHERE warehouse_id = :warehouse_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':warehouse_id', $warehouse_id);
    $stmt->execute();
    $warehouse = $stmt->fetch(PDO::FETCH_ASSOC);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the form data
    $location_subdistrict = $_POST['location_subdistrict'];
    $storage_capacity = $_POST['storage_capacity'];
    $contact_info_email = $_POST['contact_info_email'];
    $contact_info_phone = $_POST['contact_info_phone'];

    // Update warehouse details
    $update_query = "UPDATE warehouse SET location_subdistrict = :location_subdistrict, storage_capacity = :storage_capacity, contact_info_email = :contact_info_email, contact_info_phone = :contact_info_phone WHERE warehouse_id = :warehouse_id";
    $stmt_update = $pdo->prepare($update_query);
    $stmt_update->bindParam(':location_subdistrict', $location_subdistrict);
    $stmt_update->bindParam(':storage_capacity', $storage_capacity);
    $stmt_update->bindParam(':contact_info_email', $contact_info_email);
    $stmt_update->bindParam(':contact_info_phone', $contact_info_phone);
    $stmt_update->bindParam(':warehouse_id', $warehouse_id);

    if ($stmt_update->execute()) {
        header('Location: index.php');
        exit();
    } else {
        echo 'Error updating warehouse information.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Warehouse</title>
</head>
<body>

    <h1>Edit Warehouse Information</h1>

    <form method="POST" action="">
        <label for="location_subdistrict">Location:</label>
        <input type="text" name="location_subdistrict" id="location_subdistrict" value="<?php echo htmlspecialchars($warehouse['location_subdistrict']); ?>" required><br><br>

        <label for="storage_capacity">Capacity:</label>
        <input type="number" name="storage_capacity" id="storage_capacity" value="<?php echo htmlspecialchars($warehouse['storage_capacity']); ?>" required><br><br>

        <label for="contact_info_email">Email:</label>
        <input type="email" name="contact_info_email" id="contact_info_email" value="<?php echo htmlspecialchars($warehouse['contact_info_email']); ?>" required><br><br>

        <label for="contact_info_phone">Phone:</label>
        <input type="text" name="contact_info_phone" id="contact_info_phone" value="<?php echo htmlspecialchars($warehouse['contact_info_phone']); ?>" required><br><br>

        <button type="submit">Update Warehouse</button>
    </form>

</body>
</html>
