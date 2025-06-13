<?php
require 'config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $phone = $_POST["phone"];
    $address = $_POST["address"];
    
    // Handle profile picture upload
    $profile_pic_path = null;
    if (isset($_FILES["profile_pic"]) && $_FILES["profile_pic"]["error"] === UPLOAD_ERR_OK) {
        $allowed_ext = ['jpg', 'jpeg', 'png', 'gif'];
        $upload_dir = 'images/';
        $file_ext = strtolower(pathinfo($_FILES["profile_pic"]["name"], PATHINFO_EXTENSION));
        
        if (in_array($file_ext, $allowed_ext)) {
            $new_filename = uniqid("profile_", true) . "." . $file_ext;
            $target_file = $upload_dir . $new_filename;
            if (move_uploaded_file($_FILES["profile_pic"]["tmp_name"], $target_file)) {
                $profile_pic_path = $target_file;
                // Store the uploaded profile picture path in session
                $_SESSION['profile_pic'] = $profile_pic_path;
            }
        }
    }

    // Handle valid ID upload
    $valid_id_path = null;
    $valid_id_dir = 'valid_ids/';
    
    // Check if the valid_ids directory exists; if not, create it
    if (!is_dir($valid_id_dir)) {
        mkdir($valid_id_dir, 0777, true); // Create the directory with full permissions
    }

    if (isset($_FILES["valid_id"]) && $_FILES["valid_id"]["error"] === UPLOAD_ERR_OK) {
        $valid_id_ext = ['jpg', 'jpeg', 'png', 'gif'];
        $valid_id_file_ext = strtolower(pathinfo($_FILES["valid_id"]["name"], PATHINFO_EXTENSION));

        if (in_array($valid_id_file_ext, $valid_id_ext)) {
            $valid_id_filename = uniqid("valid_id_", true) . "." . $valid_id_file_ext;
            $valid_id_target_file = $valid_id_dir . $valid_id_filename;
            if (move_uploaded_file($_FILES["valid_id"]["tmp_name"], $valid_id_target_file)) {
                $valid_id_path = $valid_id_target_file;
            }
        }
    }

    // Prepare the update query
    if ($profile_pic_path && $valid_id_path) {
        $stmt = $pdo->prepare("UPDATE acc_list SET username = ?, phone = ?, address = ?, profile_pic = ?, valid_id = ?, valid_id_status = 'pending' WHERE user_id = ?");
        $stmt->execute([$username, $phone, $address, $profile_pic_path, $valid_id_path, $user_id]);
    } elseif ($profile_pic_path) {
        $stmt = $pdo->prepare("UPDATE acc_list SET username = ?, phone = ?, address = ?, profile_pic = ?, valid_id_status = 'pending' WHERE user_id = ?");
        $stmt->execute([$username, $phone, $address, $profile_pic_path, $user_id]);
    } elseif ($valid_id_path) {
        $stmt = $pdo->prepare("UPDATE acc_list SET username = ?, phone = ?, address = ?, valid_id = ?, valid_id_status = 'pending' WHERE user_id = ?");
        $stmt->execute([$username, $phone, $address, $valid_id_path, $user_id]);
    } else {
        $stmt = $pdo->prepare("UPDATE acc_list SET username = ?, phone = ?, address = ? WHERE user_id = ?");
        $stmt->execute([$username, $phone, $address, $user_id]);
    }

    echo "<p>Profile updated successfully. <a href='user_profile1.php'>Go back</a></p>";
}

// Fetch user data
$stmt = $pdo->prepare("SELECT * FROM acc_list WHERE user_id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
</head>
<body>
<h2>Edit Profile</h2>

<form method="POST" enctype="multipart/form-data">
    Username: <input type="text" name="username" value="<?= htmlspecialchars($user['username']) ?>" required><br>
    Phone: <input type="text" name="phone" value="<?= htmlspecialchars($user['phone']) ?>"><br>
    Address: <textarea name="address"><?= htmlspecialchars($user['address']) ?></textarea><br>
    
    Profile Picture: <input type="file" name="profile_pic"><br>
    Valid ID: <input type="file" name="valid_id"><br>

    <?php if (!empty($user['profile_pic'])): ?>
        <img src="<?= htmlspecialchars($user['profile_pic']) ?>" alt="Profile Picture" width="100%"><br>
    <?php endif; ?>

    <?php if (!empty($user['valid_id'])): ?>
        <img src="<?= htmlspecialchars($user['valid_id']) ?>" alt="Valid ID" width="100%"><br>
    <?php endif; ?>

    <button type="submit">Save Changes</button>
    <a href="user_profile1.php">Cancel</a>
</form>
</body>
</html>
