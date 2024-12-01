<?php

// Database configuration
define('DB_HOST', 'localhost');        // Database host
define('DB_NAME', 'lost_and_found');   // Database name
define('DB_USER', 'root');             // Database username
define('DB_PASS', '');                 // Database password (use your own)

// PDO connection setup
try {
    $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8';
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];
    $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
    // echo "Connection successful!";
} catch (PDOException $e) {
    // Handle connection error
    die("Database connection failed: " . $e->getMessage());
}

?>
