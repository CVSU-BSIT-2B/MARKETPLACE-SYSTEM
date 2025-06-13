<?php
include('config.php');

// Start session to get the logged-in user's ID
session_start();

// Get the product ID from the URL
if (isset($_GET['id'])) {
    $product_id = $_GET['id'];

    // Fetch product details from the database
    $stmt = $pdo->prepare("SELECT * FROM products WHERE product_id = :product_id");
    $stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
    $stmt->execute();
    $product = $stmt->fetch();

    // If the product is not found, redirect to the shop page
    if (!$product) {
        header("Location: shop_products.php");
        exit();
    }
} else {
    // If no product ID is passed, redirect to the shop page
    header("Location: shop_products.php");
    exit();
}

// Handle Add to Cart Action
if (isset($_POST['add_to_cart'])) {
    // Capture the logged-in user's ID
    $user_id = $_SESSION['user_id']; // Assuming user is logged in and user_id is stored in session
    $products_name = $_POST['product_name'];
    $products_price = $_POST['product_price'];
    $products_image = $_POST['product_image'];
    $products_quantity = $_POST['product_quantity'];

    // Check if product already exists in the cart for the user
    $select_cart_query = "SELECT * FROM cart WHERE name = :name AND user_id = :user_id";
    $stmt = $pdo->prepare($select_cart_query);
    $stmt->bindParam(':name', $products_name);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $display_message = "Product already added to cart";
    } else {
        // Insert the product into the cart with the user_id
        $insert_query = "INSERT INTO cart (user_id, name, price, image, quantity) VALUES (:user_id, :name, :price, :image, :quantity)";
        $stmt = $pdo->prepare($insert_query);

        // Bind parameters
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':name', $products_name);
        $stmt->bindParam(':price', $products_price);
        $stmt->bindParam(':image', $products_image);
        $stmt->bindParam(':quantity', $products_quantity, PDO::PARAM_INT);

        // Execute the statement
        if ($stmt->execute()) {
            $display_message = "Product added to cart!";
        } else {
            $display_message = "Failed to add product to cart.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($product['name']); ?> - Product Details</title>
    <link rel="stylesheet" href="css/product_details.css">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" />
</head>
<body>
    <?php include('index-header.php'); ?>

    <div class="container">
        <div class="product-details">
            <h2>Product Details</h2>
            
            <div class="product-info">
                <div class="product-image">
                    <img src="images/<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                </div>
                <div class="product-details-info">
                    <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                    <p><strong>Category:</strong> <?php echo htmlspecialchars($product['category']); ?></p>
                    <p><strong>Description:</strong> <?php echo nl2br(htmlspecialchars($product['description'])); ?></p>
                    <p><strong>Price:</strong> ₱<?php echo number_format($product['price'], 2); ?></p>

                    <form method="POST" action="product_details.php?id=<?php echo $product['product_id']; ?>">
                        <input type="hidden" name="product_name" value="<?php echo htmlspecialchars($product['name']); ?>">
                        <input type="hidden" name="product_price" value="<?php echo htmlspecialchars($product['price']); ?>">
                        <input type="hidden" name="product_image" value="<?php echo htmlspecialchars($product['image']); ?>">
                        <input type="number" name="product_quantity" value="1" min="1" max="<?php echo $product['quantity_product']; ?>" required>
                        <input type="submit" class="add-to-cart-btn" value="Add to Cart" name="add_to_cart">
                    </form>

                    <!-- Display message for Add to Cart -->
                    <?php if (isset($display_message)) { ?>
                        <div class="display_message">
                            <span><?php echo $display_message; ?></span>
                            <i class="fas fa-times" onclick="this.parentElement.style.display='none'"></i>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>

    <?php include('index-footer.php'); ?>
</body>
</html>
