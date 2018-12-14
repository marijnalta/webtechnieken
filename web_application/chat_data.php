<?php
    // Enable https
    include "enable_https.php";
    // Check if logged in
    include "login_check.php";
    // Connect to database
    include "opendb.php";

    // Get friend data
    $chat_id = $_SESSION['chat_id'];
    $_SESSION['chat_id'] = $chat_id;

    // Check if user is allowed to see chat
    $user_id = $_SESSION['user_id'];
    $friend_id = "";
    $result = $db->query("SELECT * FROM chats WHERE chat_id='$chat_id'");

    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        if ($row['user_1'] != $user_id and $row['user_2'] != $user_id) {
            header("Location: inbox.php");
            die();
        } else {
            if ($row['user_1'] == $user_id) {
                $friend_id = $row['user_2'];
            } else {
                $friend_id = $row['user_1'];
            }
        }
    }

    // Get friend data
    $result = $db->query("SELECT * FROM users WHERE user_id='$friend_id'");

    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        $name = $row['first_name'];
        $friend_img = $row['profile_picture'];
        echo "<h2>$name</h2>";
    }

    // Get user data
    $result = $db->query("SELECT profile_picture FROM users WHERE user_id='$user_id'");

    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        $user_img = $row['profile_picture'];
    }

    // Get data per post
    $result = $db->query("SELECT * FROM messages WHERE chat_id='$chat_id' ORDER BY message_id DESC LIMIT 20");
    echo "<div class='chat_messages'>";

    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        $content = $row['content'];
        $content = str_replace(":db:", "<img src='db.gif' />", $row['content']);

        // Display chat message in the correct way
        if ($row['user_id'] == $user_id) {
            echo "<div class='chat_message_container'>";
            echo "<div class='chat_img'>
            <img id = 'left_img' src='data:image/jpeg;base64,".base64_encode($user_img)."'/>
            <img id = 'right_img' src='filler.png'/>
            </div>";
            echo "<div class='chat_message'><p>$content</p></div>";
            echo "</div>";
        } else {
            echo "<div class='chat_message_container'>";
            echo "<div class='chat_img'>
            <img id = 'left_img' src='filler.png'/>
            <img id = 'right_img' src='data:image/jpeg;base64,".base64_encode($friend_img)."'/>

            </div>";
            echo "<div class='chat_message'><p>$content</p></div>";
            echo "</div>";
        }
    }

    echo "</div>";
?>
