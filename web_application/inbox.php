<?php
    // Enable https
    include "enable_https.php";
    // Check if logged in
    include "login_check.php";
?>

<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" type="text/css" href="stylesheet.css"/>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Inbox</title>
        <link rel="shortcut icon" href="logo.ico" />
    </head>
    <body>
        <div class="navigation_bar">
            <p><a href="home.php"><img src="homeweight.png" class="homeweight"></a></p>
            <p><a href="settings.php"><img src="settingsnav.png" class="settingsnav"></a></p>
            <p><a href="inbox.php"><img src="inbox.png" class="inboxnav"></a></p>
            <p><a href="profile.php">
                <?php
                    $result = $db->query("SELECT * FROM users WHERE user_id=$_SESSION[user_id]");

                    while($row = $result->fetch(PDO::FETCH_ASSOC)) {
                        $first_name = $row['first_name'];
                        echo "<div class = 'profilenav'><p>$first_name</p></div>";
                    }
                ?>
            </a></p>
            <p><a href="matching.php">Matching</a></p>
            <p><a href="friends.php">Friends</a></p>
        </div>
        <p class="logout"><a href="logout.php"><img src="logoutnav.png" class="logoutnav"></a></p>

        <div class="inbox_container">
            <h1>Inbox</h1>
            <div class="inbox_chats">
                <?php
                    // Get chats
                    include "opendb.php";

                    // Get all chat_id
                    $user_id = $_SESSION['user_id'];
                    $chat_count = 0;

                    $count_result = $db->query("SELECT COUNT(*) as chat_count FROM chats WHERE user_1='$user_id' OR user_2='$user_id'");
                    while ($count_row = $count_result->fetch(PDO::FETCH_ASSOC)) {
                        $chat_count = $count_row['chat_count'];
                    }

                    if ($chat_count == 0) {
                        echo "<p>You have no chats yet, go to your friends profile page to send a message!</p>";
                    } else {
                        $result = $db->query("SELECT * FROM chats WHERE user_1='$user_id' OR user_2='$user_id' ORDER BY chat_id DESC");

                        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                            // Get content of last message
                            $chat_id = $row['chat_id'];
                            $chat_result = $db->query("SELECT * FROM messages WHERE chat_id='$chat_id' ORDER BY message_id DESC LIMIT 1");

                            // Get last messages
                            $id_last_post = $user_id;
                            $last_message = "";
                            $content_last_post = "";
                            while ($chat_row = $chat_result->fetch(PDO::FETCH_ASSOC)) {
                                $id_last_post = $chat_row['user_id'];
                                $content_last_post = $chat_row['content'];
                                $last_message = $chat_row['datetime'];
                            }

                            // Get first name of last post
                            $user_result = $db->query("SELECT first_name FROM users WHERE user_id='$id_last_post'");

                            while ($user_row = $user_result->fetch(PDO::FETCH_ASSOC)) {
                                $first_name_last_post = $user_row['first_name'];
                            }

                            // Check who is the other user
                            $friend_id = "";

                            if ($row['user_1'] == $user_id) {
                                $friend_id = $row['user_2'];
                            } else {
                                $friend_id = $row['user_1'];
                            }

                            // Get friend data
                            $friend_result = $db->query("SELECT * FROM users WHERE user_id='$friend_id'");

                            while ($friend_row = $friend_result->fetch(PDO::FETCH_ASSOC)) {
                                $friend_img = $friend_row['profile_picture'];
                                $friend_name = $friend_row['first_name'];
                            }

                            echo "<div class='inbox_chat'>";
                            echo "<div class='inbox_img'><a href = 'chat.php?id=$chat_id'><img src='data:image/jpeg;base64,".base64_encode($friend_img)."'/></a></div>";
                            echo "<div class='inbox_name'><p>$friend_name</p></div>";
                            echo "<div class='inbox_last_message'><p>$first_name_last_post on $last_message</p></div>";
                            echo "</div>";
                        }
                    }
                ?>
            </div>
        </div>
    </body>
</html>
