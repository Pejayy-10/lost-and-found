<?php
// Start session
session_start();

// Check if the user is logged in and is a regular user
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    // If not, redirect to login page
    header('Location: ../auth/login.php');
    exit();
}

// User Home Page content here
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Home</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>
    <div class="home-container">
        <h1>Welcome to the User Home Page</h1>
        <!-- User content goes here -->
    </div>

    <div class="logout-button"></div>
        <form action="../auth/logout.php" method="post">
            <button type="submit">Logout</button>
        </form>
    </div>
    
</body>
</html>
