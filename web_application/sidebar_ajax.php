<?php
    // Enable https
    include "enable_https.php";
    // Check if logged in
    include 'login_check.php';

    // Create query
    $q = $_GET["q"];

    // Split in array
    $search_array = explode(" ", $q);
    $first_name = $search_array[0];
    $last_name = "";
    if (count($search_array) > 1) {
        $last_name = $search_array[1];
    }

    // Save in session
    if (empty($q)) {
        $_SESSION['sidebar_search'] = "SELECT * FROM users WHERE banned='0' ORDER BY user_id DESC LIMIT 100";
    } elseif (empty($last_name)) {
        $_SESSION['sidebar_search'] = "SELECT user_id, first_name, last_name, birthdate, sex, location, profile_content, profile_picture FROM users WHERE first_name LIKE '%$first_name%' AND banned=0 ORDER BY first_name LIMIT 100";
    } else {
        $_SESSION['sidebar_search'] = "SELECT user_id, first_name, last_name, birthdate, sex, location, profile_content, profile_picture FROM users WHERE (first_name LIKE '%$first_name%' OR last_name LIKE '%$last_name%') AND banned=0 ORDER BY first_name LIMIT 100";
    }

    if (!empty($_SESSION['sidebar_search'])) {
        // User list
        $result = $db->query($_SESSION['sidebar_search']);

        // Get data from result
        while($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $user_id = $row['user_id'];
            $first_name = $row['first_name'];
            $last_name = $row['last_name'];
            $age = date_diff(date_create($row['birthdate']), date_create('today'))->y;;
            $sex = $row['sex'];
            $location = $row['location'];
            $content = $row['profile_content'];
            $img = $row['profile_picture'];

            // Get post count
            $post_count = $db->query("SELECT COUNT(*) AS posts FROM posts WHERE user_id=$user_id");
            while($post_count_row = $post_count->fetch(PDO::FETCH_ASSOC)) {
                $posts = $post_count_row['posts'];
            }

            // Get friends count.
            $friends_count = $db->query("SELECT COUNT(*) AS friends FROM friends WHERE user_id=$user_id AND status='1'");
            while($friends_count_row = $friends_count->fetch(PDO::FETCH_ASSOC)) {
                $friends = $friends_count_row['friends'];
            }

            // Print to screen
            echo "<div class = 'profile_field_sb'>";
            echo "<div class = 'profile_picture_sb'><a href='profile.php?id=$user_id'><img src='data:image/jpeg;base64,".base64_encode($img)."'/></a></div>";
            echo "<div class = 'profile_container_sb'>";
            echo "<div class = 'profile_name_sb'><p>$first_name $last_name</p></div>";
            echo "<div class = 'profile_content_sb'>$content</div>";
            echo "<div class = 'profile_info_sb'>";
            echo "<p>$age years old ($sex), located in $location<br>";
            echo "$friends friends, $posts posts</p>";
            echo "</div>";
            echo "</div></div>";
        }
    }
?>
