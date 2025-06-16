<?php
require 'config.php';
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

// Delete user
if (isset($_GET['delete_user'])) {
    $user_id = $_GET['delete_user'];
    $pdo->prepare("DELETE FROM acc_list WHERE user_id = ?")->execute([$user_id]);
}

// Total sellers
$total_sellers = $pdo->query("SELECT COUNT(DISTINCT user_id) FROM products")->fetchColumn();

// Total users
$total_users = $pdo->query("SELECT COUNT(*) FROM acc_list")->fetchColumn();

// Total customers
$total_customers = $pdo->query("SELECT COUNT(*) FROM acc_list WHERE email NOT IN (SELECT email FROM admin)")->fetchColumn();

// All users
$users = $pdo->query("SELECT * FROM acc_list")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Users</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            margin: 0;
            padding: 0;
            background: #f5ede2;
        }

        .wrapper {
            padding: 30px;
        }

        .content {
            max-width: 1100px;
            margin: auto;
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.1);
            padding: 30px;
        }

        h1  {
            text-align: center;
            color:rgb(175, 156, 120);
        }

        .stats {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }

        .card {
            flex: 1;
            margin: 0 10px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 15px;
            padding: 20px;
            text-align: center;
            color: #4e342e;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: rgba(255, 255, 255, 0.25);
            backdrop-filter: blur(5px);
            border-radius: 15px;
            overflow: hidden;
        }

        table th, table td {
            padding: 12px 15px;
            border-bottom: 1px solid rgba(0,0,0,0.1);
            color: #3e2723;
            text-align: center;
        }

        table th {
            background-color: rgba(238, 224, 200, 0.6);
        }

        .action {
            padding: 6px 12px;
            background-color:rgb(17, 6, 2);
            color: white;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
            transition: 0.2s ease-in-out;
        }

        .action:hover {
            background-color:rgb(231, 10, 25);
        }
    </style>
</head>
<body>
<?php include 'admin-header.php'; ?>

        <h2 id="users">All Users</h2>
        <table>
            <tr>
                <th>User ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>Actions</th>
            </tr>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?= $user['user_id'] ?></td>
                    <td><?= htmlspecialchars($user['username']) ?></td>
                    <td><?= htmlspecialchars($user['email']) ?></td>
                    <td><a class="action" href="?delete_user=<?= $user['user_id'] ?>" onclick="return confirm('Delete this user?')">Delete</a></td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</div>

</body>
</html>
