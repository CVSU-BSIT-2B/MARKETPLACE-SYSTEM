<?php
// Include the database connection file
require_once('config.php'); // Make sure this line is at the very top

// Check if the session is already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();  // Start the session only if it has not already been started
}


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
    <title>Index-Header</title>
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.1.3/assets/owl.carousel.min.css" />
    <link rel="stylesheet" type="text/css" href="css/bootstrap.css" />
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,700|Poppins:400,700&display=swap" rel="stylesheet">
    <link href="css/index-header.css" rel="stylesheet" />
    <link href="css/responsive.css" rel="stylesheet" />
    <link href="./css/index.css" rel="stylesheet">
    <link href="images/logo.png" rel="icon" type="image/svg+mgl">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" />
</head>
<body>
    <div class="hero_area">
    <!-- header section starts -->
    <header class="header_section" style="position: relative; z-index: 10;">
      <div class="container-fluid">
        <nav class="navbar navbar-expand-lg custom_nav-container">
          <a class="navbar-brand" href="index.php">
            <img src="images/logo.png" alt="" />
          </a>
          <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>

          <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ">
              <li class="nav-item active">
                <a class="nav-link" href="index.php">Home</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="about.php"> About</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="furniture.php"> Furniture </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="contact.php">Contact us</a>
              </li>
            </ul>

            
            <div class="user_option">
             <!-- Remove the anchor tag completely -->
                <div class="user_option">
                <a href="user_profile1.php">
                    <img src="images/user.png" alt="User Profile">
                </a>

                <?php if (isset($_SESSION['username'])): ?>
                    <!-- Shown after login -->
                    <span id="userProfile">
                    <span id="fullName">Welcome, <?= htmlspecialchars($_SESSION['username'], ENT_QUOTES) ?>! </span>
                    </span>
                    <!-- Cart icon with item count -->
                    <a href="cart.php" class="cart"><i class="fa-solid fa-bag-shopping"></i><span><sup><?php echo $row_count; ?></sup></span></a>
                    <br>
                    <a href="logout.php"><button>Logout</button></a>
                <?php else: ?>
                    <!-- Shown before login -->
                    <span id="authButtons">
                    <a href="login.php">Log In</a>
                    <a href="register.php">Sign Up</a>
                    </span>
                <?php endif; ?>
                </div>
                <div class="cart"></div>

              <form class="form-inline my-2 my-lg-0 ml-0 ml-lg-4 mb-3 mb-lg-0">
                <button class="btn  my-2 my-sm-0 nav_search-btn" type="submit"></button>
              </form>
            </div>
          </div>
          <div>
            <div class="custom_menu-btn ">
              <button>
                <span class=" s-1">

                </span>
                <span class="s-2">

                </span>
                <span class="s-3">

                </span>
              </button>
            </div>
          </div>

        </nav>
      </div>
    </header>
    <!-- end header section -->

  <script type="text/javascript" src="js/jquery-3.4.1.min.js"></script>
  <script type="text/javascript" src="js/bootstrap.js"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.2.1/owl.carousel.min.js"></script>
  <script type="text/javascript">
    $(".owl-carousel").owlCarousel({
      loop: true,
      margin: 10,
      nav: true,
      navText: [],
      autoplay: true,
      autoplayHoverPause: true,
      responsive: {
        0: {
          items: 1
        },
        420: {
          items: 2
        },
        1000: {
          items: 5
        }
      }

    });
  

    var nav = $("#navbarSupportedContent");
    var btn = $(".custom_menu-btn");
    btn.click
    btn.click(function (e) {

      e.preventDefault();
      nav.toggleClass("lg_nav-toggle");
      document.querySelector(".custom_menu-btn").classList.toggle("menu_btn-style")
    });
 
    $('.carousel').on('slid.bs.carousel', function () {
      $(".indicator-2 li").removeClass("active");
      indicators = $(".carousel-indicators li.active").data("slide-to");
      a = $(".indicator-2").find("[data-slide-to='" + indicators + "']").addClass("active");
      console.log(indicators);

    })

  </script>
</div>
</body>
</html>
