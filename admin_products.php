<?php
require 'config.php';
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

// Delete product
if (isset($_GET['delete_product'])) {
    $product_id = $_GET['delete_product'];
    $pdo->prepare("DELETE FROM products WHERE product_id = ?")->execute([$product_id]);
}


// Sales by seller
$sales_stmt = $pdo->query("
    SELECT a.username, a.fullname, SUM(o.total_price) AS total_sales
    FROM acc_list a
    JOIN products p ON a.user_id = p.user_id
    JOIN order_items oi ON oi.product_id = p.product_id
    JOIN orders o ON o.id = oi.order_id
    GROUP BY a.user_id
    ORDER BY total_sales DESC
");
$sales_data = $sales_stmt->fetchAll(PDO::FETCH_ASSOC);

// All products
$products = $pdo->query("SELECT * FROM products")->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html>
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
<head>
    <head>
    <title>Admin Products</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            background: #f4efe8;
            color: #4b3e2d;
        }

        .wrapper {
            display: flex;
            justify-content: center;
            padding: 30px;
        }

        .content {
            width: 100%;
            max-width: 1200px;
            background: rgba(255, 255, 255, 0.25);
            border-radius: 20px;
            backdrop-filter: blur(10px);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.15);
            padding: 30px;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            border-radius: 12px;
            overflow: hidden;
            background: #fff8f0;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.1);
            margin-bottom: 40px;
        }

        th, td {
            padding: 14px;
            text-align: center;
            border-bottom: 1px solid #e0d1be;
        }

        th {
            background: #a67c52;
            color: white;
            text-transform: uppercase;
            font-size: 14px;
            letter-spacing: 1px;
        }

        tr:hover td {
            background-color: #f3e5d0;
            transition: background-color 0.3s ease;
        }

        .action {
            display: inline-block;
            padding: 6px 12px;
            background: rgba(12, 1, 3, 0.8);
            color: white;
            border-radius: 8px;
            text-decoration: none;
            font-size: 14px;
            transition: background 0.3s ease;
        }

        .action:hover {
            background: rgba(201, 8, 17, 0.9);
        }

        @media (max-width: 768px) {
            .content {
                padding: 20px;
            }

            table, th, td {
                font-size: 14px;
            }
        }
    </style>
</head>

<body>
<?php include 'admin-header.php'; ?>


        <h2 id="products">All Products</h2>
        <table>
            <tr>
                <th>Product ID</th>
                <th>Name</th>
                <th>Price</th>
                <th>Seller ID</th>
                <th>Actions</th>
            </tr>
            <?php foreach ($products as $product): ?>
                <tr>
                    <td><?= $product['product_id'] ?></td>
                    <td><?= htmlspecialchars($product['name']) ?></td>
                    <td>₱<?= number_format($product['price'], 2) ?></td>
                    <td><?= $product['user_id'] ?></td>
                    <td><a class="action" href="?delete_product=<?= $product['product_id'] ?>" onclick="return confirm('Delete this product?')">Delete</a></td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</div>

</body>
</html>
