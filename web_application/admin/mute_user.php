<?php
    // Enable https
    include "enable_https.php";
    // Check if logged in
    include 'login_check.php';
    // Connect to database
    include 'opendb.php';

    // Get user_id
    $user_id = $_GET['id'];

    // Check if muted
    $result = $db->query("SELECT muted FROM users WHERE user_id='$user_id'");

    while($row = $result->fetch(PDO::FETCH_ASSOC)) {
        if ($row['muted'] == 0) {
            // Mute
            $db->query("UPDATE users SET muted='1' WHERE user_id='$user_id'");
        } else {
            // Unmute
            $db->query("UPDATE users SET muted='0' WHERE user_id='$user_id'");
        }
    }

    // Redirect
    header("Location: users.php");
?>
