<?php
require_once('config.php');

// Fetch the top products from the database (you can modify this query to get the latest or most popular products)
$stmt = $pdo->query("SELECT * FROM products LIMIT 8");  // Display top 8 products
$products = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <meta name="keywords" content="" />
  <meta name="description" content="" />
  <meta name="author" content="" />
  <title>DigitF</title>
  <link rel="stylesheet" type="text/css" href="css/bootstrap.css" />
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,700|Poppins:400,700&display=swap" rel="stylesheet">
  <link href="css/style.css" rel="stylesheet" />
  <link href="css/index.css" rel="stylesheet">
</head>

<body>
  <?php include 'index-header.php' ?>

  <!-- Trending Section -->
  <section class="trending_section layout_padding">
    <div class="container">
      <div class="heading_container">
        <h2>Trending Products</h2>
      </div>

      <div class="row">
        <?php foreach ($products as $product): ?>
          <div class="col-md-3 col-sm-6 mb-4">
            <div class="product-box">
              <!-- Make the product clickable -->
              <a href="product_details.php?id=<?php echo $product['product_id']; ?>" class="product-link">
                <img src="images/<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" class="img-fluid">
              </a>
              <div class="product-info">
                <h5><?php echo htmlspecialchars($product['name']); ?></h5>
                <p class="price">₱<?php echo number_format($product['price'], 2); ?></p>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <?php include 'index-footer.php' ?>
</body>
</html>
