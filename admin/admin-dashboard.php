<?php
// admin-dashboard.php

// Start session and check if the user is logged in and an admin
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../auth/login.php');
    exit();
}

require_once 'includes/_header.php';
?>

<body id="dashboard">
    <div class="wrapper">
        <?php
        require_once 'includes/_navbar.php';
        require_once 'includes/_sidebar.php';
        ?>
        <div class="content-page px-3">
            <!-- dynamic content here -->
        </div>
    </div>
    <?php
    require_once 'includes/_footer.php';
    ?>
</body>

</html>