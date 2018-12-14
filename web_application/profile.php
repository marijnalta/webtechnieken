<?php
    // Enable https
    include "enable_https.php";
    // Make sure user is logged in
    include "login_check.php";
    // Connect to database
    include "opendb.php";

    // Get user id
    if (empty($_GET['id'])) {
        $user_id = $_SESSION['user_id'];
    } else {
        $user_id = $_GET['id'];
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" type="text/css" href="profile.css"/>
        <link rel="shortcut icon" href="logo.ico" />
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Profile</title>
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
            <div class="profile_bar">
                <div class="banner">
                    <div class="dropdown">
                        <?php
                            // Check if friends or not
                            $real_id = $_SESSION['user_id'];
                            $result = $db->query("SELECT * FROM friends WHERE user_id='$real_id' AND user_id_friend='$user_id' LIMIT 1");

                            while($row = $result->fetch(PDO::FETCH_ASSOC)) {
                                if ($row['status'] == 1) {
                                    // Unfriend
                                    echo "<a href='add_friend.php?id=$user_id'><button class='dropbtn'>Unfriend</button></a>";
                                } elseif ($row['status'] == 2 and $row['action_user_id'] == $real_id) {
                                    // Cancel friend request
                                    echo "<a href='add_friend.php?id=$user_id'><button class='dropbtn'>Cancel friend request</button></a>";
                                } elseif ($row['status'] == 2 and $row['action_user_id'] == $user_id) {
                                    // Accept request
                                    echo "<a href='add_friend.php?id=$user_id'><button class='dropbtn'>Accept friend request</button></a>";
                                }
                            }

                            if ($user_id != $real_id) {
                                // Befriend
                                $result = $db->query("SELECT COUNT(*) AS count FROM friends WHERE user_id='$real_id' AND user_id_friend='$user_id'");

                                while($row = $result->fetch(PDO::FETCH_ASSOC)) {
                                    if ($row['count'] == 0) {
                                        echo "<a href='add_friend.php?id=$user_id'><button class='dropbtn'>Add friend</button></a>";
                                    }
                                }

                                // Chat
                                echo "<a href='create_chat.php?id=$user_id'><button id='chat_button' class='dropbtn'>Chat!</button></a>";
                            } else {
                                echo "<a href='friends.php'><button class='dropbtn'>View friends!</button></a>";
                            }
                        ?>
                    </div>
                    <div class="profile_picture">
                        <?php
                            // Get data from database
                            $result = $db->query("SELECT * FROM users WHERE user_id=$user_id");

                            while($row = $result->fetch(PDO::FETCH_ASSOC)) {
                                $profile_picture = $row['profile_picture'];

                                echo "<img src='data:image/jpeg;base64,".base64_encode($profile_picture)."'/>";
                            }
                        ?>
                    </div>

                    <div class="banner_content">
                        <?php
                            $result = $db->query("SELECT * FROM users WHERE user_id=$user_id");

                            while($row = $result->fetch(PDO::FETCH_ASSOC)) {
                                $first_name = $row['first_name'];
                                $last_name = $row['last_name'];

                                echo "<div class = 'banner_name'><p>$first_name $last_name</p></div>";
                            }
                        ?>
                    </div>
                </div>

                <div class="bio">
                    <h1><a href="settings.php"><img src="bioicon.png" class="bioicon"></a></h1>
                    <div class="bio_content">
                        <?php
                            $result = $db->query("SELECT * FROM users WHERE user_id=$user_id");

                            while($row = $result->fetch(PDO::FETCH_ASSOC)) {
                                $age = date_diff(date_create($row['birthdate']), date_create('today'))->y;;
                                $sex = $row['sex'];
                                $location = $row['location'];
                                $content = $row['profile_content'];

                                echo "<div class = 'bio_text'>$content</div>";
                                echo "<div class = 'bio_i'><p>$age years old ($sex), located in $location</p><br></div>";
                            }
                        ?>
                    </div>
                </div>

                <div class="timeline">
                    <?php
                        // Check if friends
                        $real_id = $_SESSION['user_id'];
                        $friend_check = $db->query("SELECT * FROM friends WHERE user_id='$user_id' AND user_id_friend='$real_id' AND status='1'");
                        $friends = 0;

                        while($check = $friend_check->fetch(PDO::FETCH_ASSOC)) {
                            $friends = 1;
                        }

                        // Check if private
                        $profile_check = $db->query("SELECT profile_private FROM users WHERE user_id='$user_id'");
                        $private = 0;

                        while($check = $profile_check->fetch(PDO::FETCH_ASSOC)) {
                            if ($check['profile_private'] == 1) {
                                $private = 1;
                            }
                        }

                        if ($private == 1 and $friends == 0 and $user_id != $real_id) {
                            echo "<div class = 'post_field'>Befriend user to view their posts!</div>";
                        } else {
                            // Get post data
                            $result = $db->query("SELECT * FROM posts WHERE user_id='$user_id' ORDER BY post_id DESC LIMIT 20");

                            // Print all posts
                            while($row = $result->fetch(PDO::FETCH_ASSOC)) {
                                // Get user data
                                $user_data = $db->query("SELECT first_name, last_name, profile_picture FROM users WHERE user_id='$user_id' AND banned='0'");

                                while($row_user = $user_data->fetch(PDO::FETCH_ASSOC)) {
                                    $first_name = $row_user['first_name'];
                                    $last_name = $row_user['last_name'];
                                    $img = $row_user['profile_picture'];
                                    $content = str_replace(":db:", "<img src='db.gif' />", $row['content']);

                                    // Print to screen
                                    echo "<div class = 'post_field'>";
                                    echo "<div class = 'profile_img'><img src='data:image/jpeg;base64,".base64_encode($img)."'/></div>";
                                    echo "<div class = 'post_container'>";
                                    echo "<div class = 'poster'><p>$first_name $last_name</p></div>";
                                    echo "<div class = 'post_content'><p>$content</p></div>";
                                    echo "<div class = 'post_date'><p>".$row['datetime']."</p></div>";

                                    if ($user_id == $_SESSION['user_id']) {
                                        echo "<div class = 'post_delete'><a href = 'delete_profilepost.php?post_id=".$row['post_id']."'>Delete</a></div>";
                                    }

                                    echo "</div></div>";
                                }
                            }
                        }
                    ?>
                </div>
                <div class="friends">
                    <h1><a href="friends.php"><img src="friendicon.png" class="friendicon"></a></h1>

                    <?php
                        // Check if allowed to view profile
                        if ($private == 1 and $friends == 0 and $user_id != $real_id) {
                            echo "<div class = 'grid'>Befriend user to view their friends!</div>";
                        } else {
                            // User list
                            $result = $db->query("SELECT user_id_friend FROM friends WHERE user_id='$user_id' AND status='1'");

                            echo "<div class = 'profile_friends'>";

                            while($row = $result->fetch(PDO::FETCH_ASSOC)) {
                                $friend_id = $row['user_id_friend'];
                                $result_friend = $db->query("SELECT * FROM users WHERE user_id='$friend_id'");

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

                                    echo "<div class = 'grid'><a href = 'profile.php?id=$user_id'>";
                                    echo "<div class = 'friends_picture'><img src='data:image/jpeg;base64,".base64_encode($img)."'/></div>";
                                    echo "<div class = 'friend_poster'><p>$first_name $last_name</p></div>";
                                    echo "</div></a>";
                                }
                            }

                            echo "</div>";
                        }
                    ?>
                </div>
            </div>
        </div>
    </body>
</html>
