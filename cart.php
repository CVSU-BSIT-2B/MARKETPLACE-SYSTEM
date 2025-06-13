<?php
include('config.php');

// Start session to get the logged-in user's ID
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");  // Redirect to login if the user is not logged in
    exit();
}

$user_id = $_SESSION['user_id'];  // Get the user_id from session

// Handle Add to Cart Action
if (isset($_POST['add_to_cart'])) {
    $product_name = $_POST['product_name'];
    $product_price = $_POST['product_price'];
    $product_image = $_POST['product_image'];
    $product_quantity = $_POST['product_quantity'];

    // Insert the product into the cart with the user_id
    $insert_query = "INSERT INTO cart (user_id, name, price, image, quantity) VALUES (:user_id, :name, :price, :image, :quantity)";
    $stmt = $pdo->prepare($insert_query);

    // Bind parameters
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->bindParam(':name', $product_name);
    $stmt->bindParam(':price', $product_price);
    $stmt->bindParam(':image', $product_image);
    $stmt->bindParam(':quantity', $product_quantity, PDO::PARAM_INT);

    // Execute the statement
    if ($stmt->execute()) {
        $display_message = "Product added to cart!";
    } else {
        $display_message = "Failed to add product to cart.";
    }
}

// Update query using PDO (update product quantity)
if (isset($_POST['update_product_quantity'])) {
    if (isset($_POST['update_quantity_id']) && isset($_POST['update_quantity'])) {
        $update_value = $_POST['update_quantity'];
        $update_id = $_POST['update_quantity_id'];

        // PDO query to update quantity in the cart table
        $update_quantity_query = $pdo->prepare("UPDATE cart SET quantity = :quantity WHERE id = :id AND user_id = :user_id");
        $update_quantity_query->bindParam(':quantity', $update_value, PDO::PARAM_INT);
        $update_quantity_query->bindParam(':id', $update_id, PDO::PARAM_INT);
        $update_quantity_query->bindParam(':user_id', $user_id, PDO::PARAM_INT);

        if ($update_quantity_query->execute()) {
            header("Location: cart.php"); // Redirect back to the cart
            exit(); // Stop further execution
        }
    } else {
        // Display a message if quantity or cart ID is missing
        echo "<div class='empty_text'>No Product or Quantity</div>";
    }
}

// Remove item from cart
if (isset($_GET['remove'])) {
    $remove_id = $_GET['remove'];

    // Delete the product from the cart using PDO
    $remove_query = $pdo->prepare("DELETE FROM cart WHERE id = :id AND user_id = :user_id");
    $remove_query->bindParam(':id', $remove_id, PDO::PARAM_INT);
    $remove_query->bindParam(':user_id', $user_id, PDO::PARAM_INT);

    if ($remove_query->execute()) {
        header("Location: cart.php"); // Redirect back to cart after removal
        exit(); // Stop further execution
    }
}

// Remove all items from cart (if needed)
if (isset($_POST['delete_all'])) {
    // Delete all items in the cart for the logged-in user
    $delete_all_query = $pdo->prepare("DELETE FROM cart WHERE user_id = :user_id");
    $delete_all_query->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    if ($delete_all_query->execute()) {
        header("Location: cart.php"); 
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
    <link rel="stylesheet" href="./css/cart.css">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" />
</head>
<body>
    <div class="container">
        <section class="shopping_cart">
            <table>
                <thead>
                    <?php 
                    // Fetch cart products using PDO for the logged-in user
                    $select_cart_products = $pdo->prepare("SELECT * FROM cart WHERE user_id = ?");
                    $select_cart_products->execute([$user_id]);
                    $num = 1;
                    $grand_total = 0;
                    
                    if ($select_cart_products->rowCount() > 0) {
                        echo "<th>No</th>
                              <th>Name</th>
                              <th>Image</th>
                              <th>Price</th>
                              <th>Quantity</th>
                              <th>Total</th>
                              <th>Action</th>
                            </thead>
                            <tbody>";
                        
                        while ($fetch_cart_products = $select_cart_products->fetch()) {
                    ?>
                        <tr>
                            <td><?php echo $num ?></td>
                            <td><?php echo htmlspecialchars($fetch_cart_products['name']); ?></td>
                            <td><img src="images/<?php echo htmlspecialchars($fetch_cart_products['image']); ?>" alt="Product Image"></td>
                            <td>₱<?php echo number_format($fetch_cart_products['price'], 2); ?></td>
                            <td>
                                <form action="" method="POST">
                                    <input type="hidden" value="<?php echo $fetch_cart_products['id']; ?>" name="update_quantity_id">
                                    <div class="quantity_box">
                                        <input type="number" min="1" value="<?php echo $fetch_cart_products['quantity']; ?>" name="update_quantity">
                                        <input type="submit" class="update_quantity" value="Update" name="update_product_quantity">
                                    </div>
                                </form>
                            </td>
                            <td>₱<?php echo number_format($fetch_cart_products['price'] * $fetch_cart_products['quantity'], 2); ?></td>
                            <td>
                                <a href="cart.php?remove=<?php echo $fetch_cart_products['id'] ?>" onclick="return confirm('Are you sure you want to delete this item?')">
                                    <i class="fa-solid fa-xmark"></i>
                                </a>
                            </td>
                        </tr>
                    <?php
                        $grand_total += ($fetch_cart_products['price'] * $fetch_cart_products['quantity']);
                        $num++;
                        }
                    } else {
                        echo "<div class='empty_text'>Cart is empty</div>";
                    }
                    ?>
                </tbody>
            </table>

            <?php 
            if ($grand_total > 0) {
                echo "<div class='table_bottom'>
                        <a href='index.php' class='bottom_btn'>
                            <i class='fa-solid fa-arrow-left'></i> Back to Shop
                        </a>
                        <h3 class='bottom_btn'>Grand total: <span>₱" . number_format($grand_total, 2) . "</span></h3>
                        <a href='checkout.php' class='botton_btn'>Checkout</a>
                    </div>";

                // Delete All functionality
                echo "<form action='' method='POST'>
                        <button type='submit' name='delete_all' class='delete_all_btn'>
                            <i class='fas fa-trash'></i> Delete All
                        </button>
                    </form>";
            }
            ?>

        </section>
    </div>
</body>
</html>
