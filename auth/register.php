<?php
session_start();
require_once '../config/database.php'; // Include the DB connection

// Handle the registration via AJAX
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $password2 = $_POST['password2'];
    $contact_number = $_POST['contact_number'];

    // Validate form inputs
    if (empty($username) || empty($password) || empty($password2) || empty($contact_number)) {
        echo json_encode(['status' => 'error', 'message' => 'Please fill in all fields.']);
        exit;
    } elseif ($password !== $password2) {
        echo json_encode(['status' => 'error', 'message' => 'Passwords do not match.']);
        exit;
    } elseif (strlen($password) < 6) {
        echo json_encode(['status' => 'error', 'message' => 'Password must be at least 6 characters long.']);
        exit;
    } else {
        // Check if username already exists
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->execute(['username' => $username]);
        if ($stmt->fetch()) {
            echo json_encode(['status' => 'error', 'message' => 'Username already exists.']);
            exit;
        } else {
            // Insert new user into database
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (username, password, contact_number) VALUES (?, ?, ?)");
            $stmt->execute([$username, $hashed_password, $contact_number]);

            // Return success
            echo json_encode(['status' => 'success', 'message' => 'Registration successful!', 'redirect' => 'login.php']);
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
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/auth-style.css">  <!-- Link to custom CSS -->
</head>
<body>

<div class="container">
    <div class="card">
        <div class="card-header">
            Create an Account
        </div>

        <div id="error-message" class="alert" style="display:none;"></div>

        <form id="register-form">
            <input type="text" class="form-control" name="username" id="username" placeholder="Username" required>
            <input type="password" class="form-control" name="password" id="password" placeholder="Password" required>
            <input type="password" class="form-control" name="password2" id="password2" placeholder="Confirm Password" required>
            <input type="text" class="form-control" name="contact_number" id="contact_number" placeholder="Contact Number" required>

            <button type="submit" class="btn btn-primary">Register</button>
        </form>

        <div class="text-center mt-3">
            <p>Already have an account? <a href="login.php">Login here</a></p>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>

<script>
// AJAX Registration Form Submission
$('#register-form').on('submit', function(e) {
    e.preventDefault(); // Prevent form submission
    const username = $('#username').val();
    const password = $('#password').val();
    const password2 = $('#password2').val();
    const contact_number = $('#contact_number').val();

    // AJAX request
    $.ajax({
        url: 'register.php',
        type: 'POST',
        data: { username: username, password: password, password2: password2, contact_number: contact_number },
        dataType: 'json',
        success: function(response) {
            if (response.status === 'success') {
                // Redirect on success
                window.location.href = response.redirect;
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
