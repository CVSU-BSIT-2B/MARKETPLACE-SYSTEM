<?php
require 'config.php';
session_start();  // Start the session to access session variables

// Check if the user is logged in as an admin
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php"); // Redirect to login page if admin is not logged in
    exit();
}

if (isset($_GET['approve_id'])) {
    $user_id = $_GET['approve_id'];

    // Update the valid_id status to 'approved'
    $stmt = $pdo->prepare("UPDATE acc_list SET valid_id_status = 'approved' WHERE user_id = ?");
    $stmt->execute([$user_id]);

    // Set the session for the user
    $_SESSION['user_id'] = $user_id;  // Automatically log in the user after approval

    // Redirect to add product page
    header("Location: add_product.php");
    exit();
}

if (isset($_GET['reject_id'])) {
    $user_id = $_GET['reject_id'];

    // Update the valid_id status to 'rejected'
    $stmt = $pdo->prepare("UPDATE acc_list SET valid_id_status = 'rejected' WHERE user_id = ?");
    $stmt->execute([$user_id]);

    echo "Valid ID rejected!";
}

// Fetch users with pending valid ID approvals
$stmt = $pdo->query("SELECT * FROM acc_list WHERE valid_id_status = 'pending'");
$users = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin - Pending Valid ID Approvals</title>
</head>
<body>

<h2>Pending Valid ID Approvals</h2>

<table>
    <tr>
        <th>User ID</th>
        <th>Username</th>
        <th>Valid ID</th>
        <th>Actions</th>
    </tr>
    <?php foreach ($users as $user): ?>
        <tr>
            <td><?= htmlspecialchars($user['user_id']) ?></td>
            <td><?= htmlspecialchars($user['username']) ?></td>
            <td><img src="<?= htmlspecialchars($user['valid_id']) ?>" alt="Valid ID" width="100"></td>
            <td>
                <a href="?approve_id=<?= $user['user_id'] ?>">Approve</a> |
                <a href="?reject_id=<?= $user['user_id'] ?>">Reject</a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

</body>
</html>
