<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" type="text/css" href="stylesheet_admin.css"/>
        <meta http-equiv="refresh" content="30">
    </head>
</html>

<?php
    // Enable https
    include "enable_https.php";
    // Check if logged in
    include 'login_check.php';
    // Connect to database
    include 'opendb.php';

    // Get post data
    if (!empty($_SESSION['query'])) {
        $result = $db->query($_SESSION['query']);

        // Print all posts
        while($row = $result->fetch(PDO::FETCH_ASSOC)) {
            // Get user data
            $user_id = $row['user_id'];
            $user_data = $db->query("SELECT first_name, last_name, profile_picture FROM users WHERE user_id='$user_id'");

            while($row_user = $user_data->fetch(PDO::FETCH_ASSOC)) {
                $first_name = $row_user['first_name'];
                $last_name = $row_user['last_name'];
                $img = $row_user['profile_picture'];
            }

            // Edit content
            if (!empty($_SESSION['search'])) {
                $text = strtolower($row['content']);
                $search = $_SESSION['search'];
                $edit = '<b>'.$search.'</b>';
                $content = str_replace($search, $edit, $text);
                $content = str_replace(":db:", "<img src='db.gif' />", $content);
            } else {
                $content = $row['content'];
                $content = str_replace(":db:", "<img src='db.gif' />", $content);
            }

            // Print to screen
            echo "<div class = 'post_field'>";
            echo "<div class = 'profile_picture'><img src='data:image/jpeg;base64,".base64_encode($img)."'/></div>";
            echo "<div class = 'post_container'>";
            echo "<div class = 'poster'><p>$first_name $last_name ($user_id)</p></div>";
            echo "<div class = 'post_content'><p>$content</p></div>";
            echo "<div class = 'post_date'><p>".$row['datetime']."</p></div>";
            echo "<div class = 'post_delete'><a href = 'delete_post.php?post_id=".$row['post_id']."'>Delete</a></div>";
            echo "</div></div>";
        }
    }
?>
