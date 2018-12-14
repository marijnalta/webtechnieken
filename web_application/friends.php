<?php
    // Enable https
    include "enable_https.php";
    // Check if logged in
    include "login_check.php";
    // Includes database
    include "opendb.php";
?>

<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" type="text/css" href="friends.css"/>
        <link rel="shortcut icon" href="logo.ico"/>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <script src="jquery-3.3.1.min.js"></script>
        <title>Friends</title>
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

        <div class="wrap">
            <div class="requests">
                <h2> Friends requests </h2>
                    <div class="box">
                        <?php
                            // Connect to database
                            include "opendb.php";

                            // Count requests
                            $user_id = $_SESSION['user_id'];
                            $friends_count = $db->query("SELECT COUNT(*) AS friends_count FROM friends WHERE user_id='$user_id' AND status='2' AND action_user_id!='$user_id'");

                            while($row = $friends_count->fetch(PDO::FETCH_ASSOC)) {
                                if ($row['friends_count'] == 0) {
                                    echo "<h3>No friend requests yet</h3>";
                                    echo "<script src='friends_fix.js'></script>";
                                } else {
                                    // Get user data
                                    $result = $db->query("SELECT user_id_friend FROM friends WHERE user_id='$user_id' AND status='2' AND action_user_id!='$user_id' ORDER BY user_id");

                                    while($row = $result->fetch(PDO::FETCH_ASSOC)) {
                                        $friend_id = $row['user_id_friend'];
                                        $result_friend = $db->query("SELECT * FROM users WHERE user_id='$friend_id' ORDER BY user_id");

                                        while($row_friend = $result_friend->fetch(PDO::FETCH_ASSOC)) {
                                            $user_id = $row_friend['user_id'];
                                            $first_name = $row_friend['first_name'];
                                            $last_name = $row_friend['last_name'];
                                            $age = date_diff(date_create($row_friend['birthdate']), date_create('today'))->y;;
                                            $sex = $row_friend['sex'];
                                            $location = $row_friend['location'];
                                            $img = $row_friend['profile_picture'];

                                            $post_count = $db->query("SELECT COUNT(*) AS posts FROM posts WHERE user_id=$user_id");
                                            while($post_count_row = $post_count->fetch(PDO::FETCH_ASSOC)) {
                                                $posts = $post_count_row['posts'];
                                            }

                                            $friends_count = $db->query("SELECT COUNT(*) AS friends FROM friends WHERE user_id=$user_id");
                                            while($friends_count_row = $friends_count->fetch(PDO::FETCH_ASSOC)) {
                                                $friends = $friends_count_row['friends'];
                                            }

                                            // Print friends to screen
                                            echo "<div class = 'grid'>";
                                            echo "<div class = 'picture_grid'>";
                                            echo "<a href = 'profile.php?id=$user_id'><img class = 'picture' src='data:image/jpeg;base64,".base64_encode($img)."'/></a></div>";
                                            echo "<div class = 'info'>";
                                            echo "<div><p class = 'poster'>$first_name $last_name <a href='accept_friend.php?id=$user_id'>+</a></p>";
                                            echo "<p class= 'poster_info'> $age years old ($sex), located in $location</p></div>";
                                            echo "</div></div>";
                                        }
                                    }
                                }
                            }
                        ?>
                </div>
            </div>

            <div class="friends">
                <h2> Friends </h2>
                    <div class="box">
                    <?php
                        // Connect to database
                        include "opendb.php";

                        // Get user data
                        $user_id = $_SESSION['user_id'];
                        $result = $db->query("SELECT user_id_friend FROM friends WHERE user_id='$user_id' AND status='1' ORDER BY user_id");

                        while($row = $result->fetch(PDO::FETCH_ASSOC)) {
                            $friend_id = $row['user_id_friend'];
                            $result_friend = $db->query("SELECT * FROM users WHERE user_id='$friend_id' ORDER BY user_id");

                            while($row_friend = $result_friend->fetch(PDO::FETCH_ASSOC)) {
                                $user_id = $row_friend['user_id'];
                                $first_name = $row_friend['first_name'];
                                $last_name = $row_friend['last_name'];
                                $age = date_diff(date_create($row_friend['birthdate']), date_create('today'))->y;;
                                $sex = $row_friend['sex'];
                                $location = $row_friend['location'];
                                $img = $row_friend['profile_picture'];

                                $post_count = $db->query("SELECT COUNT(*) AS posts FROM posts WHERE user_id='$user_id'");
                                while($post_count_row = $post_count->fetch(PDO::FETCH_ASSOC)) {
                                    $posts = $post_count_row['posts'];
                                }

                                $friends_count = $db->query("SELECT COUNT(*) AS friends FROM friends WHERE user_id='$user_id'");
                                while($friends_count_row = $friends_count->fetch(PDO::FETCH_ASSOC)) {
                                    $friends = $friends_count_row['friends'];
                                }

                                // Print to screen
                                echo "<div class = 'grid'>";
                                echo "<div class = 'picture_grid'>";
                                echo "<a href = 'profile.php?id=$user_id'><img class = 'picture' src='data:image/jpeg;base64,".base64_encode($img)."'/></a></div>";
                                echo "<div class = 'info'>";
                                echo "<div><p class = 'poster'>$first_name $last_name</p>";
                                echo "<p class= 'poster_info'>$age years old ($sex), located in $location</p></div>";
                                echo "</div></div>";
                            }
                        }
                    ?>
                </div>
            </div>
        </div>
    </body>
</html>
