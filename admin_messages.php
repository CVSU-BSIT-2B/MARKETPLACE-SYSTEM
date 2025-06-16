<?php
session_start();
require 'config.php';
include 'admin-header.php';

$stmt = $pdo->query("SELECT * FROM contact_messages ORDER BY submitted_at DESC");
$messages = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin - Contact Messages</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
  <style>
    body {
      background-color: #F7F4F0;
      font-family: 'Poppins', sans-serif;
      margin: 0;
      padding: 0;
    }

    .container {
      width: 90%;
      max-width: 1200px;
      margin: 50px auto;
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

    tbody td.message {
      text-align: left;
      white-space: pre-wrap;
    }

    tr:nth-child(even) {
      background-color: #f9f6f0;
    }

    .no-messages {
      text-align: center;
      padding: 20px;
      color: #999;
    }
  </style>
</head>
<body>

<div class="container">
  <div id="header">
    <h2>Submitted Contact Messages</h2>
  </div>

  <table>
    <thead>
      <tr>
        <th>#</th>
        <th>Name</th>
        <th>Email</th>
        <th>Phone</th>
        <th>Message</th>
        <th>Submitted At</th>
      </tr>
    </thead>
    <tbody>
      <?php if (count($messages) > 0): ?>
        <?php foreach ($messages as $index => $msg): ?>
          <tr>
            <td><?= $index + 1 ?></td>
            <td><?= htmlspecialchars($msg['name']) ?></td>
            <td><?= htmlspecialchars($msg['email']) ?></td>
            <td><?= htmlspecialchars($msg['phone']) ?></td>
            <td class="message"><?= nl2br(htmlspecialchars($msg['message'])) ?></td>
            <td><?= date("M d, Y h:i A", strtotime($msg['submitted_at'])) ?></td>
          </tr>
        <?php endforeach; ?>
      <?php else: ?>
        <tr>
          <td colspan="6" class="no-messages">No messages found.</td>
        </tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>

</body>
</html>
