<?php
session_start();
require 'config.php';


$error = '';
$debug = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Check if the email belongs to an admin first
    $adminStmt = $pdo->prepare("SELECT id, password, fullname FROM admin WHERE email = ?");
    $adminStmt->execute([$email]);
    $admin = $adminStmt->fetch();

    if ($admin) {
        // Compare admin password directly (no hashing)
        if ($password === $admin['password']) {
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_email'] = $email;
            $_SESSION['admin_name'] = $admin['fullname'];

            // Log admin activity
            $logStmt = $pdo->prepare("INSERT INTO user_activity_log (name, email, role, status) VALUES (?, ?, ?, ?)");
            $logStmt->execute([ 
                $admin['fullname'],
                $email,
                'Admin',
                'Active'
            ]);

            echo "<script>
                alert('Welcome Admin!');
                window.location.href = 'admin.php';
            </script>";
            exit;
        } else {
            echo "<script>alert('Incorrect admin password.'); window.history.back();</script>";
            exit;
        }
    }

    // If not an admin, check in acc_list for regular user
    $stmt = $pdo->prepare("SELECT * FROM acc_list WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user) {
        if (password_verify($password, $user["password"])) {
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['username'] = $user['username'];
            $debug = "Login successful. Redirecting...";
            header("Location: index.php"); // Redirect to index.php
            exit();
        } else {
            $error = "Incorrect password.";
            $debug = "Password verification failed.";
        }
    } else {
        $error = "Email not found.";
        $debug = "No user found with that email.";
    }
}
?>

<?php if (isset($_GET['reset']) && $_GET['reset'] === 'success'): ?>
    <p style="color: green;">Your password has been reset successfully. Please log in.</p>
<?php endif; ?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<h2>Login</h2>

<?php if (!empty($error)): ?>
    <div class="error" style="color: red;"><?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>

<?php if (!empty($debug)): ?>
    <div class="debug" style="color: blue;"><?php echo htmlspecialchars($debug); ?></div>
<?php endif; ?>

<form method="POST">
    Email: <input type="email" name="email" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    <a href="forgot-password.php">Forgot Password</a>
    <button type="submit">Login</button>
</form>
</body>
</html>
