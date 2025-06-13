<?php
include('config.php');

// Check if the user is logged in and the valid ID is approved
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");  // Redirect to login if the user is not logged in
    exit();
}

$user_id = $_SESSION['user_id'];  // Get the user_id from session

// Fetch the user's valid_id_status from the database
$stmt = $pdo->prepare("SELECT valid_id_status FROM acc_list WHERE user_id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

if ($user['valid_id_status'] !== 'approved') {
    echo "You need to upload and have your valid ID approved before adding products.";
    exit();
}

// Handle form submission for adding products
if (isset($_POST['add_product'])) {
    $product_name = $_POST['product_name'];
    $product_price = $_POST['product_price'];
    $product_desc = $_POST['product_desc'];
    $product_category = $_POST['product_category'];  // Category field
    $product_quantity = $_POST['product_quantity'];  // Quantity field
    $product_image = $_FILES['product_image']['name'];
    $product_image_temp_name = $_FILES['product_image']['tmp_name'];
    $product_image_folder = './images/' . basename($product_image);

    try {
        // Insert product into DB with user_id
        $insert_query = $pdo->prepare("INSERT INTO products (user_id, name, price, description, category, quantity_product, image) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $insert_query->execute([$user_id, $product_name, $product_price, $product_desc, $product_category, $product_quantity, $product_image]);

        // Move uploaded image to the images folder
        if (move_uploaded_file($product_image_temp_name, $product_image_folder)) {
            $display_message = "Product inserted successfully!";
        } else {
            $display_message = "Product added but failed to upload image.";
        }
    } catch (PDOException $e) {
        echo "Error inserting product: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
    <link rel="stylesheet" href="./css/addproducts.css">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" />
</head>
<body>
    <?php include('./addproduct-header.php'); ?>

    <!-- Display message appears below the header and above the form container -->
    <?php if (isset($display_message)) : ?>
        <div class="display_message">
            <span><?= $display_message ?></span>
            <i class="fas fa-times" onclick="this.parentElement.style.display='none'"></i>
        </div>
    <?php endif; ?>

    <!-- Main form container -->
    <div class="container">
        <section>
            <form action="" method="post" class="add_product" enctype="multipart/form-data">
                <!-- Left: Image Upload -->
                <div class="image-upload-area">
                    <div class="image-box">
                        <i class="fa-regular fa-image"></i>
                        <p>Insert Image</p>
                        <input type="file" name="product_image" required accept="image/png, image/jpg, image/jpeg" />
                    </div>
                </div>

                <!-- Right: Product Fields -->
                <div class="form-fields">
                    <label for="product">Enter Product Name:</label>
                    <input type="text" id="product" name="product_name" placeholder="Product Name" class="input_fields" required>

                    <label for="price">Product Price:</label>
                    <input type="number" id="price" name="product_price" placeholder="Product Price" class="input_fields" required>

                    <label for="desc">Enter Product Description:</label>
                    <input type="text" id="desc" name="product_desc" placeholder="Product Description" class="input_fields" required>

                    <!-- Category Field -->
                    <label for="product_category">Category:</label>
                    <input type="text" id="product_category" name="product_category" placeholder="Category (e.g., chair, table, bed)" class="input_fields" required>

                    <!-- Quantity Field -->
                    <label for="product_quantity">Quantity:</label>
                    <input type="number" id="product_quantity" name="product_quantity" value="1" min="1" placeholder="Quantity" class="input_fields" required>

                    <input type="submit" name="add_product" class="submit_btn" value="Add Item">
                </div>
            </form>
        </section>
    </div>

    <script src="js/script-home.js"></script>
</body>
</html>
