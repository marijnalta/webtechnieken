<?php
    // Enable https
    include "enable_https.php";
    // Connect to database
    include 'opendb.php';

    // Start session
    session_start();

    // Check user_id
    $user_id = $_SESSION['user_id'];

    // Remove post
    $post_id = $_GET['post_id'];
    $db->query("DELETE FROM posts WHERE post_id='$post_id' AND user_id='$user_id'");

    // Redirect
    header("Location: home.php");
?>
