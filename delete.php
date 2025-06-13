<?php
include("config.php");

if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM products WHERE product_id = ?");
    $success = $stmt->execute([$delete_id]);

    if ($success) {
        header("Location: view_products.php");
        exit;
    } else {
        echo "Product not deleted";
    }
}
?>
