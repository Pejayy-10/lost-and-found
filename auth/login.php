<?php
session_start();
require_once '../config/database.php'; // Include the DB connection

// This part is for handling login logic if needed, else we'll handle it via AJAX
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Validate form inputs
    if (empty($username) || empty($password)) {
        echo json_encode(['status' => 'error', 'message' => 'Please fill in both fields.']);
        exit;
    } else {
        // Check if user exists and password matches
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->execute(['username' => $username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            // Store user details in session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role']; // Store the user's role (admin or user)

            // Redirect based on role
            if ($user['role'] == 'admin') {
                echo json_encode(['status' => 'success', 'message' => 'Login successful', 'redirect' => '/admin/admin-dashboard.php']);
            } else {
                echo json_encode(['status' => 'success', 'message' => 'Login successful', 'redirect' => '/user/home.php']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Invalid credentials.']);
        }
    }
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/auth-style.css">  <!-- Link to custom CSS -->
</head>
<body>

<div class="container">
    <div class="card">
        <div class="card-header">
            Login to Your Account
        </div>

        <div id="error-message" class="alert alert-danger" style="display:none;"></div>

        <form id="login-form">
            <input type="text" class="form-control" name="username" id="username" placeholder="Username" required>
            <input type="password" class="form-control" name="password" id="password" placeholder="Password" required>

            <button type="submit" class="btn btn-primary">Login</button>
        </form>

        <div class="text-center mt-3">
            <p>Don't have an account? <a href="register.php">Register here</a></p>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>

<script>
// AJAX Login Form Submission
$('#login-form').on('submit', function(e) {
    e.preventDefault(); // Prevent form submission
    const username = $('#username').val();
    const password = $('#password').val();

    // AJAX request
    $.ajax({
        url: 'login.php',
        type: 'POST',
        data: { username: username, password: password },
        dataType: 'json',
        success: function(response) {
            if (response.status === 'success') {
                // Redirect on success
                window.location.href = response.redirect; // Redirect based on role
            } else {
                // Display error message
                $('#error-message').text(response.message).show();
            }
        },
        error: function() {
            $('#error-message').text('An error occurred, please try again later.').show();
        }
    });
});
</script>

</body>
</html>
