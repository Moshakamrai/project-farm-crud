<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select User Dashboard</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        /* General page layout */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            color: #333;
        }

        header {
            background-color: #4CAF50;
            color: white;
            padding: 20px;
            text-align: center;
        }

        /* Style for the container */
        .container {
            max-width: 900px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            text-align: center;
        }

        /* Heading style */
        h1 {
            font-size: 2.5em;
            color: #333;
            margin-bottom: 20px;
        }

        /* Navigation list */
        ul {
            list-style-type: none;
            padding: 0;
        }

        ul li {
            margin: 15px 0;
        }

        /* Links */
        a {
            font-size: 1.2em;
            color: #4CAF50;
            text-decoration: none;
            padding: 10px 20px;
            border: 2px solid #4CAF50;
            border-radius: 5px;
            transition: all 0.3s ease;
        }

        /* Hover effect for links */
        a:hover {
            background-color: #4CAF50;
            color: white;
            border-color: #45a049;
        }

        /* Responsive adjustments */
        @media (max-width: 600px) {
            h1 {
                font-size: 2em;
            }

            a {
                font-size: 1.1em;
            }
        }

    </style>
</head>
<body>

    <header>
        <h1>Welcome to the Farm Ecosystem</h1>
    </header>

    <div class="container">
        <ul>
            <li><a href="farmer/index.php?farmer_id=1">Farmer Dashboard</a></li>
            <li><a href="warehouse-manager/index.php">Warehouse Manager Dashboard</a></li>
            <li><a href="delivery-manager/index.php">Delivery Manager Dashboard</a></li>
        </ul>
    </div>

</body>
</html>
