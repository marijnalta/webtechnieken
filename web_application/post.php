<?php
    // Enable https
    include "enable_https.php";
    // Connect to database
    include "opendb.php";
    // Check if logged id
    include "login_check.php";

    // Check is user didn't exceed daily post limit
    $limit = 30;
    $user_id = $_SESSION['user_id'];
    $today = date('Y/m/d');
    $check = $db->query("SELECT COUNT(*) AS post_count FROM posts WHERE user_id=$user_id AND datetime LIKE '$today%'");

    while($row = $check->fetch(PDO::FETCH_ASSOC)) {
        // Ban user if more posts than allowed
        if ($row['post_count'] >= $limit) {
            // Ban
            $db->query("UPDATE users SET banned='1' WHERE user_id=$user_id");

            // Redirect
            header("Location: logout.php");
        } else {
            // Check if muted or banned
            $muted_check = $db->query("SELECT muted, banned FROM users WHERE user_id=$user_id");

            while($row_check = $muted_check->fetch(PDO::FETCH_ASSOC)) {
                if ($row_check['muted'] == 0 and $row_check['banned'] == 0) {
                    // Get data
                    $content = strip_tags($_POST['update']);
                    $date = date('Y/m/d H:i');

                    // Insert data
                    $db->query("INSERT INTO posts (user_id, datetime, content) VALUES ('$user_id', '$date', '$content')");

                    // Redirect
                    header("Location: home.php");
                } else {
                    // Redirect
                    header("Location: home.php");
                }
            }
        }
    }
?>
