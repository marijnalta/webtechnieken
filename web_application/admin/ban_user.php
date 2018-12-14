<?php
    // Enable https
    include "enable_https.php";
    // Check if logged in
    include 'login_check.php';
    // Connect to database
    include 'opendb.php';

    // Get user_id
    $user_id = $_GET['id'];

    // Check if banned
    $result = $db->query("SELECT banned FROM users WHERE user_id='$user_id'");

    while($row = $result->fetch(PDO::FETCH_ASSOC)) {
        if ($row['banned'] == 0) {
            // Ban
            $db->query("UPDATE users SET banned='1' WHERE user_id='$user_id'");
        } else {
          // Unban
          $db->query("UPDATE users SET banned='0' WHERE user_id='$user_id'");
        }
    }

    // Redirect
    header("Location: users.php");
?>
