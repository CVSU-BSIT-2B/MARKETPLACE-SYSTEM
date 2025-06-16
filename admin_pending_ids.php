<?php
require 'config.php';
include 'admin-header.php';

// Approve ID
if (isset($_GET['approve_id'])) {
    $user_id = $_GET['approve_id'];
    $stmt = $pdo->prepare("UPDATE acc_list SET valid_id_status = 'approved' WHERE user_id = :user_id");
    $stmt->execute(['user_id' => $user_id]);
    header("Location: admin_pending_ids.php");
    exit();
}

// Reject ID
if (isset($_GET['reject_id'])) {
    $user_id = $_GET['reject_id'];
    $stmt = $pdo->prepare("UPDATE acc_list SET valid_id_status = 'rejected' WHERE user_id = :user_id");
    $stmt->execute(['user_id' => $user_id]);
    header("Location: admin_pending_ids.php");
    exit();
}

// Fetch all pending users
$stmt = $pdo->query("SELECT * FROM acc_list WHERE valid_id_status = 'pending'");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin - Pending Valid ID Approvals</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
    <style>
        .container {
            width: 90%;
            max-width: 1000px;
            margin: 40px auto;
            font-family: 'Poppins', sans-serif;
        }

        #header {
            background-color: #9B7554;
            padding: 1px;
            text-align: center;
            color: white;
            border-radius: 20px;
        }

        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            background-color: white;
            border-radius: 10px;
            overflow: hidden;
        }

        thead th {
            padding: 12px 15px;
            background-color: #DFD7CC;
            text-align: center;
            color: #5c4b3b;
        }

        tbody td {
            padding: 12px 15px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }

        a {
            margin: 0 5px;
            font-weight: 500;
            text-decoration: none;
        }

        .approve {
            background-color: #A1D998;
            padding: 8px 16px;
            border-radius: 5px;
            color: white;
        }

        .approve:hover {
            background-color: #8AC17F;
        }

        .reject {
            background-color: #F46D75;
            padding: 8px 16px;
            border-radius: 5px;
            color: white;
        }

        .reject:hover {
            background-color: #D85C62;
        }

        img {
            border-radius: 6px;
        }
    </style>
</head>
<body>

<div class="container">
    <div id="header">
        <h2>Pending Valid ID Approvals</h2>
    </div>

    <table>
        <thead>
            <tr>
                <th>User ID</th>
                <th>Username</th>
                <th>Valid ID</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($users) > 0): ?>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?= htmlspecialchars($user['user_id']) ?></td>
                        <td><?= htmlspecialchars($user['username']) ?></td>
                        <td>
                            <?php if (!empty($user['valid_id'])): ?>
                                <img src="<?= htmlspecialchars($user['valid_id']) ?>" alt="Valid ID" width="100">
                            <?php else: ?>
                                No image
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="?approve_id=<?= $user['user_id'] ?>" class="approve">Approve</a>
                            <a href="?reject_id=<?= $user['user_id'] ?>" class="reject">Reject</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4" style="padding: 20px;">No pending IDs</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

</body>
</html>
