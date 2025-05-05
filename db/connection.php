<?php
$host = 'localhost';  // Database host
$dbname = 'farm_ecosystem';  // Database name
$username = 'root';  // Database username
$password = '';  // Database password (default for XAMPP)

try {
    // Create a new PDO instance
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    
    // Set PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Optionally set charset to UTF-8
    $pdo->exec("set names utf8mb4");

} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
}
?>
