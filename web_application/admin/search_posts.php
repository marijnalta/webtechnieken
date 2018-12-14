<?php
    // Check if logged in
    include 'login_check.php';

    // Get search input
    $user_search = $_POST['user_search'];
    $post_search = $_POST['post_search'];

    // Save search input for highlighting in feed.php
    $_SESSION['search'] = $_POST['post_search'];

    // Save in session
    if (empty($user_search) and empty($post_search)) {
        $_SESSION['query'] = "SELECT * FROM posts ORDER BY post_id DESC LIMIT 50";
    } elseif (!empty($user_search) and empty($post_search)) {
        $_SESSION['query'] = "SELECT * FROM posts WHERE user_id='$user_search%' ORDER BY post_id DESC LIMIT 50";
    } elseif (empty($user_search) and !empty($post_search)) {
        $_SESSION['query'] = "SELECT * FROM posts WHERE content LIKE '%$post_search%' ORDER BY post_id DESC LIMIT 50";
    } else {
        $_SESSION['query'] = "SELECT * FROM posts WHERE content LIKE '%$post_search%' AND user_id='$user_search%' ORDER BY post_id DESC LIMIT 50";
    }

    // Refresh admin_panel.php
    header("Location: admin_panel.php");
?>
