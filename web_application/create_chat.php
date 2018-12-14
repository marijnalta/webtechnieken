<?php
    // Enable https
    include "enable_https.php";
    // Connect to database
    include "opendb.php";
    // Check if logged id
    include "login_check.php";

    // Check if chat already exists
    $user_id = $_SESSION['user_id'];
    $friend_id = $_GET['id'];
    $result = $db->query("SELECT * FROM chats WHERE (user_1='$user_id' AND user_2='$friend_id') OR (user_1='$friend_id' AND user_2='$user_id')");

    // Create chat if otherwise
    $exists = 0;
    $chat_id = 0;
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        $exists = 1;
        $chat_id = $row['chat_id'];
    }

    if ($exists == 0) {
        // Create chat
        $db->query("INSERT INTO chats (user_1, user_2) VALUES ('$user_id', '$friend_id')");

        // Get chat id
        $result = $db->query("SELECT chat_id FROM chats WHERE user_1='$user_id' AND user_2='$friend_id'");

        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $chat_id = $row['chat_id'];
            header("Location: chat.php?id=$chat_id");
        }
    } else {
        header("Location: chat.php?id=$chat_id");
    }
?>
