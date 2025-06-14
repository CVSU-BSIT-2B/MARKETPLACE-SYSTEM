<?php
include('config.php');  // Include your database connection file

// Check if the form is submitted
if (isset($_POST['submit'])) {
    $key = $_POST['key'];  // Get the search term

    // Prepare the SQL query with placeholders
    $query = $pdo->prepare("SELECT * FROM products WHERE category LIKE :keyword OR name LIKE :keyword ORDER BY name");

    // Add wildcards to the search term for partial matching
    $keyword = "%" . $key . "%";  // This will match anything containing the search term

    // Bind the keyword to the query
    $query->bindValue(':keyword', $keyword, PDO::PARAM_STR);

    // Execute the query
    $query->execute();

    // Fetch all results
    $result = $query->fetchAll();
    
    // Get the number of rows returned
    $rows = $query->rowCount();  // This will give the number of rows that match the search
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Products</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" />
    <link rel="stylesheet" href="css/search.css">
</head>
<body>
    <?php include('index-header.php'); ?>

    <!-- Search Form -->
    <div class="container nt-5">
        <form action="search.php" method="post">
            <input type="text" placeholder="Search products...." name="key" required>
            <input type="submit" value="submit" name="submit">
        </form>
    </div>

    <div class="container">
        <?php 
            // Check if there are results
            if (isset($rows) && $rows > 0) {
                foreach ($result as $r) {
                    echo '<h4>' . htmlspecialchars($r['name']) . ' - ' . htmlspecialchars($r['price']) . '</h4><br>';
                    echo '<img src="images/' . htmlspecialchars($r['image']) . '" alt="' . htmlspecialchars($r['name']) . '" /><br>';
                }
            } else {
                echo "<h4 class='text-danger'>No result was found for your search</h4>";
            }
        ?>
    </div>

    <?php include('index-footer.php'); ?>

</body>
</html>
