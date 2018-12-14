<?php
    // Enable https
    include "enable_https.php";
    // Check if logged in
    include 'login_check.php';
    // Connect to database
    include 'opendb.php';

    // Get post_id
    $post_id = $_GET['post_id'];

    // Remove post
    $db->query("DELETE FROM posts WHERE post_id='$post_id'");

    // Redirect
    header("Location: feed.php");
?>
