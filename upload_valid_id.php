<?php
require 'config.php';

// Start session to get the logged-in user's ID
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Handle the file upload and updating the valid_id_status
if (isset($_POST['upload'])) {
    // Validate and upload the new ID image
    $valid_id = $_FILES['valid_id']['name'];
    $valid_id_temp = $_FILES['valid_id']['tmp_name'];
    $valid_id_folder = 'images/' . basename($valid_id);

    if (move_uploaded_file($valid_id_temp, $valid_id_folder)) {
        // Update the valid_id status to 'pending'
        $stmt = $pdo->prepare("UPDATE acc_list SET valid_id = ?, valid_id_status = 'pending' WHERE user_id = ?");
        $stmt->execute([$valid_id_folder, $user_id]);
        echo "Your valid ID has been re-uploaded and is now pending approval.";
    } else {
        echo "Failed to upload the valid ID.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Re-upload Valid ID</title>
</head>
<body>

<h2>Re-upload Valid ID</h2>

<form method="POST" enctype="multipart/form-data">
    <label for="valid_id">Choose Valid ID Image:</label>
    <input type="file" name="valid_id" required>
    <button type="submit" name="upload">Upload</button>
</form>

</body>
</html>
