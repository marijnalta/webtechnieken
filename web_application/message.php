<?php
    // Connect to database
    include "opendb.php";

    // Start session
    session_start();

    // Get data from session
    $chat_id = $_SESSION['chat_id'];
    $user_id = $_SESSION['user_id'];
    $content = strip_tags($_POST['update']);
    $date = date('Y/m/d H:i');

    // Insert message in database
    $db->query("INSERT INTO messages (chat_id, user_id, datetime, content) VALUES ('$chat_id', '$user_id', '$date', '$content')");

    // Redirect
    header("Location: chat.php?id=$chat_id");
?>
