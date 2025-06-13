<?php

// Check if the form was submitted via POST method and if "email" is provided
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST["email"]) && !empty($_POST["email"])) {
        // Retrieve the email from the POST request
        $email = $_POST["email"];

        // Generate reset token and its hash
        $token = bin2hex(random_bytes(16));
        $token_hash = hash("sha256", $token);

        // Set token expiration time (5 minutes from now)
        $expiry = date("Y-m-d H:i:s", time() + 60 * 5);

        // Database connection settings using PDO
        require __DIR__ . "/config.php";
        $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Throw exceptions on errors
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Return associative arrays
            PDO::ATTR_EMULATE_PREPARES   => false,                  // Use real prepared statements
        ];

        try {
            $pdo = new PDO($dsn, $user, $pass, $options);
        } catch (\PDOException $e) {
            die('Connection failed: ' . $e->getMessage());
        }

        // Prepare the SQL query to update the reset token and expiration
        $sql = "UPDATE acc_list SET reset_token_hash = ?, reset_token_expires_at = ? WHERE email = ?";

        $stmt = $pdo->prepare($sql);

        // Bind parameters and execute the query
        $stmt->execute([$token_hash, $expiry, $email]);

        // Check if the row was updated
        if ($stmt->rowCount()) {
            // Prepare and send the email
            $mail = require __DIR__ . "/mailer.php";
            $mail->setFrom("rodriguezbeia908@gmail.com");
            $mail->addAddress($email);
            $mail->Subject = "Password Reset";
            $mail->Body = <<<END
            Click <a href="http://localhost:3000/online_marketplace_system/reset-password.php">here</a> 
            to reset your password.
            END;

            try {
                $mail->send();
                echo "Message sent, please check your inbox.";
            } catch (Exception $e) {
                echo "Message could not be sent. Mailer error: {$mail->ErrorInfo}";
            }
        } else {
            echo "Email address not found in the system.";
        }
    } else {
        echo "Email is required.";
    }
} else {
    echo "Invalid request method.";
}

?>
