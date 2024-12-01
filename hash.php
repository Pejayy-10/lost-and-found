<?php
require_once 'config/database.php'; // Include the database connection

// Admin credentials
$username = 'admin';
$password = 'admin123';
$role = 'admin'; // Set the role as admin

// Hash the password before inserting
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Prepare the SQL query
$sql = "INSERT INTO users (username, password, role) VALUES (:username, :password, :role)";
$stmt = $pdo->prepare($sql);

// Bind parameters and execute the query
$stmt->bindParam(':username', $username);
$stmt->bindParam(':password', $hashed_password);
$stmt->bindParam(':role', $role);

// Execute the query and check if it was successful
if ($stmt->execute()) {
    echo "Admin account created successfully!";
} else {
    echo "Failed to create admin account.";
}
?>
