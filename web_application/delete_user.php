<?php
    // Enable https
    include "enable_https.php";
    // Check if logged in
    include 'login_check.php';
    // Connect to database
    include 'opendb.php';

    // Delete all entries of user in database
    $user_id = $_SESSION['user_id'];
    $db->query("DELETE FROM users WHERE user_id='$user_id'");
    $db->query("DELETE FROM posts WHERE user_id='$user_id'");
    $db->query("DELETE FROM friends WHERE user_id='$user_id'");
    $db->query("DELETE FROM friends WHERE user_id_friend='$user_id'");
    $db->query("DELETE FROM chats WHERE user_1='$user_id'");
    $db->query("DELETE FROM chats WHERE user_2='$user_id'");
    $db->query("DELETE FROM messages WHERE user_id='$user_id'");

    // Redirect
    header("Location: index.php");
?>
