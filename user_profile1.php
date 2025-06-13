<?php
require 'config.php';


if (isset($_GET['logout'])) {
    header("Location: index.php"); 
    exit();
}

session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];


$stmt = $pdo->prepare("SELECT * FROM acc_list WHERE user_id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

if (!$user) {
    echo "User not found.";
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Dashboard</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
    margin: 0;
    font-family: Arial, sans-serif;
    background: #e6d3b3;
    }



    .nav-links a,
    .nav-icons a {
    color: white;
    margin: 0 10px;
    text-decoration: none;
    font-weight: bold;
    }

    .container {
    display: flex;
    margin-top: 20px;
    padding: 20px;
    }

    .sidebar {
    width: 250px;
    padding: 20px;
    background: rgba(210, 180, 140, 0.5); 
    backdrop-filter: blur(10px);
    border-radius: 15px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    margin-right: 20px; 
    }

    .profile {
    text-align: center;
    margin-bottom: 20px;
    }

    .profile img {
    border-radius: 50%;
    border: 3px solid #8b4513;
    background-color: white;
    }

    .profile p {
    font-weight: bold;
    margin-top: 10px;
    }

    .main-content {
    flex: 1;
    padding: 20px;
    background: rgba(255, 248, 220, 0.5); 
    border-radius: 15px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .menu li {
    list-style: none;
    margin: 10px 0;
    cursor: pointer;
    transition: all 0.2s;
    }

    .menu li:hover {
    color: #8b4513;
    transform: translateX(5px);
    }

    .menu li.active {
    font-weight: bold;
    color: #8b4513;
    }
    .sidebar-button {
    display: inline-block;
    margin-top: 10px;
    padding: 8px 12px;
    background-color: #8b4513;
    color: white;
    text-decoration: none;
    border-radius: 8px;
    font-size: 14px;
    transition: background-color 0.2s;
    text-align: center;
    }

    .sidebar-button:hover {
    background-color: #a0522d;
    }

    .sidebar-button.pending {
    background-color: #999;
    cursor: default;
    }

    .sidebar-button.rejected {
    background-color: #b22222;
    }

    .sidebar-button.approved {
        background-color: #228b22;
    }

    </style>
</head>
<body>


<?php include 'index-header.php'; ?>



<div class="container">

<div class="sidebar">
    <div class="profile">
        <?php if (!empty($user['profile_pic']) && file_exists($user['profile_pic'])): ?>
            <img src="<?= htmlspecialchars($user['profile_pic']) ?>" alt="Profile Picture" width="80">
        <?php else: ?>
            <img src="images/default_user.png" alt="Default User" width="80">
        <?php endif; ?>
        <p><?= htmlspecialchars($user['username']) ?></p>
        <a href="edit_profile.php" class="sidebar-button">Edit Profile</a>

        <?php if ($user['valid_id_status'] == 'approved'): ?>
            <a class="sidebar-button approved" href="addproduct.php">Add Products</a>
        <?php elseif ($user['valid_id_status'] == 'rejected'): ?>
            <a class="sidebar-button rejected" href="upload_valid_id.php">Re-upload Valid ID</a>
        <?php else: ?>
            <div class="sidebar-button pending">Valid ID Pending</div>
        <?php endif; ?>
    </div>
    <ul class="menu">
        <li><b>My Account</b></li>
        <li>Profile</li>
        <li>Bag</li>
        <li><b>My Purchases</b></li>
        <li class="active">To Ship</li>
        <li>To Receive</li>
        <li>Completed</li>
    </ul>
    </div>



    <div class="main-content">
        <h2>To Ship</h2>
        <p>No Orders Yet</p>

        <hr>

        <h3>Personal Details</h3>
        <p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
        <p><strong>Phone:</strong> <?= htmlspecialchars($user['phone']) ?></p>
        <p><strong>Address:</strong> <?= nl2br(htmlspecialchars($user['address'])) ?></p>

        <hr>


        <?php if ($user['valid_id_status'] == 'approved'): ?>
            <p>Your valid ID has been approved! You can now <a href="addproduct.php">add products</a>.</p>
        <?php elseif ($user['valid_id_status'] == 'rejected'): ?>
            <p>Your valid ID has been rejected. Please re-upload your valid ID.</p>
            <a href="upload_valid_id.php">Re-upload Valid ID</a>
        <?php else: ?>
            <p>Your valid ID is still pending approval. You cannot add products until it is approved.</p>
        <?php endif; ?>
    </div>
</div>

</body>
</html>
