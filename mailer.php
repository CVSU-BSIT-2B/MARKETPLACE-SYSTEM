<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . "/vendor/autoload.php";

$mail = new PHPMailer(true);

// Enable debug output
//$mail->SMTPDebug = SMTP::DEBUG_SERVER;

$mail->isSMTP();
$mail->SMTPAuth = true;

$mail->Host = "smtp.gmail.com";
$mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; // <-- FIXED
$mail->Port = 465;                               // <-- FIXED
$mail->Username = "rodriguezbeia908@gmail.com";
$mail->Password = "phcumlmolbunvzfu"; // <-- App password, not Gmail password

$mail->isHTML(true);

return $mail;
?>
