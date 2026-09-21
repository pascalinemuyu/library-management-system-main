<?php
require_once __DIR__ . '/includes/config.php';

$_SESSION = [];
session_destroy();
setcookie('remember_student', '', time() - 3600, '/');

header('Location: Login.html');
exit;
