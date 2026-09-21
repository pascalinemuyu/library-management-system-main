<?php
/**
 * Database configuration.
 * Update these four values to match your MySQL/MariaDB setup.
 * Then import db/schema.sql to create the `users` table this file connects to.
 */
define('DB_HOST', 'localhost');
define('DB_NAME', 'library_db');
define('DB_USER', 'root');
define('DB_PASS', '');

try {
    $pdo = new PDO(
        'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4',
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );
} catch (PDOException $e) {
    die('Database connection failed: ' . $e->getMessage());
}

// One shared session for both Login.html and Sign Up.html flows.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
