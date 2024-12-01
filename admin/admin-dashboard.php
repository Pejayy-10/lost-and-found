<?php
// Start session
session_start();

// Check if the user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    // If not, redirect to login page
    header('Location: /login.php');
    exit();
}

// Admin Dashboard content here
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>
    <div class="dashboard-container">
        <h1>Welcome to the Admin Dashboard</h1>
        <!-- Admin content goes here -->
    </div>
</body>
</html>
