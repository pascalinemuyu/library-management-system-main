<?php
require_once __DIR__ . '/includes/config.php';

// Protect this page: only accessible once logged in (via Login.html or Sign Up.html)
if (empty($_SESSION['user_id'])) {
    header('Location: Login.html');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Library Account</title>
    <link rel="stylesheet" href="CSS/LIB.CSS"/>
    <style>
        body { font-family: Arial, Helvetica, sans-serif; padding: 40px; }
        .box { max-width: 480px; margin: 0 auto; padding: 20px; border: 1px solid #ccc; }
        a.button {
            display: inline-block; margin-top: 14px; padding: 10px 18px;
            background-color: #f44336; color: #fff; text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="box">
        <h2>Welcome, <?= htmlspecialchars($_SESSION['student_number']) ?>!</h2>
        <p>You are logged in as <?= htmlspecialchars($_SESSION['email']) ?>.</p>
        <p>You now have access to library resources.</p>
        <a class="button" href="logout.php">Log Out</a>
    </div>
</body>
</html>
