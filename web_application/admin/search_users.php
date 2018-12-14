<?php
    // Enable https
    include "enable_https";
    // Check if logged in
    include 'login_check.php';

    // Get query
    $sql = $_POST['sql'];

    // Save in session
    if (empty($sql)) {
        $_SESSION['sql'] = "SELECT * FROM users ORDER BY user_id DESC LIMIT 50";
    } else {
        $_SESSION['sql'] = "SELECT user_id, first_name, last_name, birthdate, sex, email, location, registration_date, banned, muted, profile_picture FROM users $sql LIMIT 50";
    }

    // Refresh admin_panel.php
    header("Location: admin_panel.php");
?>
