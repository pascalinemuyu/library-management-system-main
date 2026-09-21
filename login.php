<?php
require_once __DIR__ . '/includes/config.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $studentNumber = trim($_POST['uname'] ?? '');
    $password      = $_POST['psw'] ?? '';

    if ($studentNumber === '' || $password === '') {
        $errors[] = 'Student Number and Password are required.';
    } else {
        $stmt = $pdo->prepare('SELECT id, student_number, email, password_hash FROM users WHERE student_number = ?');
        $stmt->execute([$studentNumber]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password_hash'])) {
            $_SESSION['user_id']        = $user['id'];
            $_SESSION['student_number'] = $user['student_number'];
            $_SESSION['email']          = $user['email'];

            if (!empty($_POST['remember'])) {
                // Simple 30-day "remember me" cookie tied to this login
                setcookie('remember_student', $user['student_number'], time() + 30 * 24 * 60 * 60, '/');
            }

            header('Location: dashboard.php');
            exit;
        } else {
            $errors[] = 'Incorrect Student Number or Password. New here? Please Sign Up first.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Result</title>
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
        <h2>Login</h2>
        <?php if (!empty($errors)): ?>
            <ul class="error">
                <?php foreach ($errors as $e): ?>
                    <li><?= htmlspecialchars($e) ?></li>
                <?php endforeach; ?>
            </ul>
            <a class="button" href="Login.html">Back to Login</a>
            <a class="button" href="Sign Up.html">Sign Up</a>
        <?php else: ?>
            <p>Please log in.</p>
            <a class="button" href="Login.html">Back to Login</a>
        <?php endif; ?>
    </div>
</body>
</html>
