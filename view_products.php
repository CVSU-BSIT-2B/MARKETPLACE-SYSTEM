<?php include("config.php"); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Products</title>
    <link rel="stylesheet" href="css/viewproducts.css">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" />
</head>
<body>
    <?php include('addproduct-header.php'); ?>

    <div class="container">
        <section class="display_product">
            <?php

            session_start();
            if (!isset($_SESSION['user_id'])) {
                header("Location: login.php");
                exit();
            }
            $user_id = $_SESSION['user_id'];

            // Fetch products uploaded by the logged-in user
            $stmt = $pdo->prepare("SELECT * FROM products WHERE user_id = ?");
            $stmt->execute([$user_id]);
            $products = $stmt->fetchAll();
            $num = 1;

            if (count($products) > 0) {
                echo "<table>
                    <thead>
                        <tr>
                            <th>S.No</th>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Price</th>
                            <th>Category</th>
                            <th>Quantity</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>";

                foreach ($products as $row) {
            ?>
                    <tr>
                        <td data-label="S.No"><?php echo $num; ?></td>
                        <td data-label="Image"><img src="./images/<?php echo htmlspecialchars($row['image']); ?>" alt="<?php echo htmlspecialchars($row['name']); ?>"></td>
                        <td data-label="Name"><?php echo htmlspecialchars($row['name']); ?></td>
                        <td data-label="Price">₱<?php echo htmlspecialchars($row['price']); ?></td>
                        <td data-label="Category"><?php echo htmlspecialchars($row['category']); ?></td>
                        <td data-label="Quantity"><?php echo htmlspecialchars($row['quantity_product']); ?></td>
                        <td data-label="Action">
                            <a href="delete.php?delete=<?php echo $row['product_id']; ?>" class="delete_product_btn" onclick="return confirm('Are you sure you want to delete this product?')"><i class="fas fa-trash"></i></a>
                            <a href="update.php?edit=<?php echo $row['product_id']; ?>" class="update_product_btn"><i class="fas fa-edit"></i></a>
                        </td>
                    </tr>

            <?php
                    $num++;
                }
                echo "</tbody></table>";
            } else {
                echo "<div class='empty_text'>No Products Available</div>";
            }
            ?>
        </section>
    </div>
</body>
</html>
