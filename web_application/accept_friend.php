<?php
    // Enable https
    include "enable_https.php";
    // Connect to database
    include "opendb.php";
    // Check if logged id
    include "login_check.php";

    // Query
    $friend_id = $_GET['id'];
    $user_id = $_SESSION['user_id'];

    $db->query("UPDATE friends SET status='1' WHERE user_id='$user_id' AND user_id_friend='$friend_id' AND status='2' AND action_user_id='$friend_id'");
    $db->query("UPDATE friends SET status='1' WHERE user_id='$friend_id' AND user_id_friend='$user_id' AND status='2' AND action_user_id='$friend_id'");

    // Redirect
    header("Location: friends.php");
?>
