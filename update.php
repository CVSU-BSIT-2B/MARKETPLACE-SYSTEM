<?php
include("config.php");

// Update logic
if (isset($_POST['update_product'])) {
    $update_product_id = $_POST['update_product_id'];
    $update_product_name = $_POST['update_product_name'];
    $update_product_price = $_POST['update_product_price'];
    $update_product_desc = $_POST['update_product_desc'];

    $update_product_image = $_FILES['update_product_image']['name'];
    $update_product_image_tmp_name = $_FILES['update_product_image']['tmp_name'];
    $update_product_image_folder = './images/' . $update_product_image;

    try {
        // Only update image if a new one was uploaded
        if (!empty($update_product_image)) {
            $sql = "UPDATE products SET name = ?, price = ?, description = ?, image = ? WHERE product_id = ?";
            $stmt = $pdo->prepare($sql);
            $updated = $stmt->execute([
                $update_product_name,
                $update_product_price,
                $update_product_desc,
                $update_product_image,
                $update_product_id
            ]);

            if ($updated) {
                move_uploaded_file($update_product_image_tmp_name, $update_product_image_folder);
                header('Location: view_products.php');
                exit;
            } else {
                $display_message = "Failed to update the product.";
            }
        } else {
            // Update without changing the image
            $sql = "UPDATE products SET name = ?, price = ?, description = ? WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            $updated = $stmt->execute([
                $update_product_name,
                $update_product_price,
                $update_product_desc,
                $update_product_id
            ]);

            if ($updated) {
                header('Location: view_products.php');
                exit;
            } else {
                $display_message = "Failed to update the product.";
            }
        }
    } catch (PDOException $e) {
        $display_message = "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Product</title>
    <link rel="stylesheet" href="css/update.css">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" />
</head>
<body>
    <?php include('addproduct-header.php'); ?>

    <?php if (isset($display_message)) : ?>
        <div class="display_message">
            <span><?= htmlspecialchars($display_message) ?></span>
            <i class="fas fa-times" onclick="this.parentElement.style.display='none'"></i>
        </div>
    <?php endif; ?>

    <section class="edit_container">
        <?php
        if (isset($_GET['edit'])) {
            $edit_id = $_GET['edit'];
            $stmt = $pdo->prepare("SELECT * FROM products WHERE product_id = ?");
            $stmt->execute([$edit_id]);
            $fetch_data = $stmt->fetch();

            if ($fetch_data) {
                ?>
                <form action="" method="post" enctype="multipart/form-data" class="update_product product_container_box">
                    <img src="./images/<?php echo htmlspecialchars($fetch_data['image']) ?>" alt="" width="150">
                    <input type="hidden" name="update_product_id" value="<?php echo htmlspecialchars($fetch_data['product_id']) ?>">
                    <input type="text" class="input_fields fields" value="<?php echo htmlspecialchars($fetch_data['name']) ?>" required name="update_product_name">
                    <input type="number" class="input_fields fields" value="<?php echo htmlspecialchars($fetch_data['price']) ?>" required name="update_product_price">
                    <input type="text" class="input_fields fields" value="<?php echo htmlspecialchars($fetch_data['description']) ?>" required name="update_product_desc">
                    <input type="file" class="input_fields fields" accept="image/png, image/jpg, image/jpeg" name="update_product_image">
                    <div class="btns">
                        <input type="submit" class="edit_btn" value="Update Product" name="update_product">
                        <input type="reset" id="close-edit" value="Cancel" class="cancel_btn">
                    </div>
                </form>
                <?php
            } else {
                echo "<p>Product not found.</p>";
            }
        } else {
            echo "<p>No product selected for editing.</p>";
        }
        ?>
    </section>

    <!-- User Profile Section -->
    <h2>Upload Your Valid ID</h2>
    <form method="POST" enctype="multipart/form-data">
        <label for="valid_id">Valid ID: </label>
        <input type="file" name="valid_id" required><br>
        <button type="submit" name="submit_valid_id">Submit Valid ID</button>
    </form>
</body>
</html>

<?php
// Handle valid ID upload
if (isset($_POST['submit_valid_id'])) {
    $valid_id = $_FILES['valid_id']['name'];
    $valid_id_tmp_name = $_FILES['valid_id']['tmp_name'];
    $valid_id_folder = './valid_ids/' . $valid_id;  // Store in 'valid_ids' folder

    // Check if valid ID is uploaded and process the file
    if (move_uploaded_file($valid_id_tmp_name, $valid_id_folder)) {
        // Update user profile with valid ID path and set the status to 'pending'
        $stmt = $pdo->prepare("UPDATE acc_list SET valid_id = ?, valid_id_status = 'pending' WHERE user_id = ?");
        $stmt->execute([$valid_id_folder, $_SESSION['user_id']]);

        echo "Valid ID uploaded and awaiting approval.";
    } else {
        echo "Error uploading the valid ID.";
    }
}
?>

