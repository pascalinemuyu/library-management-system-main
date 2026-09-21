<?php
require_once __DIR__ . '/includes/config.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $studentNumber = trim($_POST['uname'] ?? '');
    $email         = trim($_POST['email'] ?? '');
    $password      = $_POST['psw'] ?? '';
    $passwordRepeat = $_POST['psw-repeat'] ?? '';

    // --- Validate ---
    if ($studentNumber === '') {
        $errors[] = 'Student Number is required.';
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'A valid email is required.';
    }
    if (strlen($password) < 6) {
        $errors[] = 'Password must be at least 6 characters.';
    }
    if ($password !== $passwordRepeat) {
        $errors[] = 'Passwords do not match.';
    }

    if (empty($errors)) {
        // Make sure the student number / email isn't already registered
        $check = $pdo->prepare('SELECT id FROM users WHERE student_number = ? OR email = ?');
        $check->execute([$studentNumber, $email]);

        if ($check->fetch()) {
            $errors[] = 'An account with that Student Number or Email already exists. Please log in instead.';
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);

            $insert = $pdo->prepare(
                'INSERT INTO users (student_number, email, password_hash) VALUES (?, ?, ?)'
            );
            $insert->execute([$studentNumber, $email, $hash]);

            // Log the new user straight in, using the same session Login.html/login.php relies on
            $_SESSION['user_id']        = $pdo->lastInsertId();
            $_SESSION['student_number'] = $studentNumber;
            $_SESSION['email']          = $email;

            header('Location: dashboard.php');
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - Result</title>
    <link rel="stylesheet" href="CSS/LIB.CSS"/>
    <style>
        body { font-family: Arial, Helvetica, sans-serif; padding: 40px; }
        .msg-box { max-width: 480px; margin: 0 auto; padding: 20px; border: 1px solid #ccc; }
        .error { color: #f44336; }
        a.button {
            display: inline-block; margin-top: 14px; padding: 10px 18px;
            background-color: #4CAF50; color: #fff; text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="msg-box">
        <h2>Sign Up</h2>
        <?php if (!empty($errors)): ?>
            <ul class="error">
                <?php foreach ($errors as $e): ?>
                    <li><?= htmlspecialchars($e) ?></li>
                <?php endforeach; ?>
            </ul>
            <a class="button" href="Sign Up.html">Back to Sign Up</a>
        <?php else: ?>
            <p>Please fill in the sign up form.</p>
            <a class="button" href="Sign Up.html">Back to Sign Up</a>
        <?php endif; ?>
    </div>
</body>
</html>
