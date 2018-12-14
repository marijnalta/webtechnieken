<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" type="text/css" href="stylesheet_admin.css"/>
    </head>
</html>

<?php
    // Enable https
    include "enable_https.php";
    // Check if logged in
    include 'login_check.php';
    // Connect to database
    include 'opendb.php';

    if (!empty($_SESSION['sql'])) {
        // Get user data
        $result = $db->query($_SESSION['sql']);

        // Get data from result
        while($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $user_id = $row['user_id'];
            $first_name = $row['first_name'];
            $last_name = $row['last_name'];
            $email = $row['email'];
            $location = $row['location'];
            $registration_date = $row['registration_date'];
            $age = date_diff(date_create($row['birthdate']), date_create('today'))->y;;
            $sex = $row['sex'];
            $img = $row['profile_picture'];

            // Get post count
            $post_count = $db->query("SELECT COUNT(*) AS posts FROM posts WHERE user_id='$user_id'");
            while($post_count_row = $post_count->fetch(PDO::FETCH_ASSOC)) {
                $posts = $post_count_row['posts'];
            }

            // Get friends count.
            $friends_count = $db->query("SELECT COUNT(*) AS friends FROM friends WHERE user_id='$user_id' AND status='1'");
            while($friends_count_row = $friends_count->fetch(PDO::FETCH_ASSOC)) {
                $friends = $friends_count_row['friends'];
            }

            // Check if banned
            if ($row['banned'] == 0) {
                $ban = "Ban";
            } else {
                $ban = "Unban";
            }

            // Check if muted
            if ($row['muted'] == 0) {
                $mute = "Mute";
            } else {
                $mute = "Unmute";
            }

            // Print to screen
            echo "<div class = 'post_field'>";
            echo "<div class = 'profile_picture'><img src='data:image/jpeg;base64,".base64_encode($img)."'/></div>";
            echo "<div class = 'post_container'>";
            echo "<div class = 'poster'><p>$first_name $last_name ($user_id)</p></div>";
            echo "<div class = 'post_content'><p>$friends friends, $posts posts<br>";
            echo "$age years old ($sex)<br>";
            echo "Located in $location</p></div>";
            echo "<div class = 'post_date'><p>Registered on: $registration_date<br>Contact: $email</p></div>";
            echo "<div class = 'post_delete'><a href = 'delete_user.php?id=$user_id'>Delete user</a>   ";
            echo "<a href = 'ban_user.php?id=$user_id'>$ban user</a>   ";
            echo "<a href = 'mute_user.php?id=$user_id'>$mute user</a></div>";
            echo "</div></div>";
        }
    }
?>
