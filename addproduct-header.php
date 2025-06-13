<?php
// Include the database connection file
include('config.php'); // Assuming db.php sets up the PDO connection in $pdo

// PDO query to select all rows from the cart
$query = "SELECT * FROM cart";
$stmt = $pdo->prepare($query); // Prepare the query
$stmt->execute(); // Execute the query

// Get the number of rows (cart items)
$row_count = $stmt->rowCount(); // Get the count of rows in the result
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" />
</head>
<body>
    <header>
        <div class="header_body">
            <a class="navbar-brand" href="user_profile1.php">
            <img src="images/logo.png" alt="" />
          </a>
            <nav class="navbar">
                <a href="addproduct.php">Add Products</a>
                <a href="view_products.php">View Products</a>
            </nav>
        </div>
    </header>
</body>
</html>
