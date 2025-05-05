<?php
// Include the database connection
include '../db/connection.php';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the form data
    $location_subdistrict = $_POST['location_subdistrict'];
    $storage_capacity = $_POST['storage_capacity'];
    $contact_info_email = $_POST['contact_info_email'];
    $contact_info_phone = $_POST['contact_info_phone'];

    // Insert new warehouse information
    $query = "INSERT INTO warehouse (location_subdistrict, storage_capacity, contact_info_email, contact_info_phone) 
              VALUES (:location_subdistrict, :storage_capacity, :contact_info_email, :contact_info_phone)";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':location_subdistrict', $location_subdistrict);
    $stmt->bindParam(':storage_capacity', $storage_capacity);
    $stmt->bindParam(':contact_info_email', $contact_info_email);
    $stmt->bindParam(':contact_info_phone', $contact_info_phone);
    
    if ($stmt->execute()) {
        header('Location: index.php');  // Redirect back to index page after successful insertion
        exit();
    } else {
        echo 'Error: Could not add the warehouse.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Warehouse</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

    <h1>Add New Warehouse Information</h1>

    <form method="POST" action="">
        <label for="location_subdistrict">Location:</label>
        <input type="text" name="location_subdistrict" id="location_subdistrict" required><br><br>

        <label for="storage_capacity">Capacity:</label>
        <input type="number" name="storage_capacity" id="storage_capacity" required><br><br>

        <label for="contact_info_email">Email:</label>
        <input type="email" name="contact_info_email" id="contact_info_email" required><br><br>

        <label for="contact_info_phone">Phone:</label>
        <input type="text" name="contact_info_phone" id="contact_info_phone" required><br><br>

        <button type="submit">Add Warehouse</button>
    </form>

</body>
</html>
