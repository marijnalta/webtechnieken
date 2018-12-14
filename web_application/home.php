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
        <link rel="stylesheet" type="text/css" href="sidebar.css"/>
        <link rel="shortcut icon" href="logo.ico" />
        <script src="jquery-3.3.1.min.js"></script>
        <meta charset="UTF-8">
        <title>Home</title>
        <script>
            function showHint(str) {
                if (str.length == 0) {
                    document.getElementById("search_container").innerHTML = "";
                    return;
                } else {
                    var xmlhttp = new XMLHttpRequest();
                    xmlhttp.onreadystatechange = function() {
                        if (this.readyState == 4 && this.status == 200) {
                            document.getElementById("search_container").innerHTML = this.responseText;
                        }
                    };
                    xmlhttp.open("GET", "sidebar_ajax.php?q=" + str, true);
                    xmlhttp.send();
                }
            }
        </script>
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

        <div class="sidebar">

            <p class="search_title">Search</p>

            <div class="searchbar">
                <input type="text" name="search" maxlength="500" placeholder="Find friends!" onkeyup="showHint(this.value)" />
            </div>

            <div id="search_container"></div>

            <script>
                $(document).ready(function(){
                    $("div.sidebar").hover(function(){
                        $("iframe").css("opacity", "1.0");
                        $("div.searchbar").css("opacity", "1.0");
                        $(".search_title").html("");
                        $("#search_container").css("opacity", "1.0");
                        $("#search_container").css("overflow-y", "scroll");
                        }, function(){
                        $("iframe").css("opacity", "0.0");
                        $("div.searchbar").css("opacity", "0.0");
                        $(".search_title").html("Search");
                        $("#search_container").css("opacity", "0.0");
                        $("#search_container").css("overflow-y", "hidden");
                    });
                });
            </script>
        </div>

        <div class="ads">
            <?php
                $quotes = array();

                // Fill quotes array
                array_push($quotes, array("The expert in anything was once a beginner", "Helen Hayes"));
                array_push($quotes, array("Look in the mirror, that's your competition", "Anonymous"));
                array_push($quotes, array("A little progress each day adds up to big results", "Satya Nani"));
                array_push($quotes, array("It never gets easier, you just get better", "Anonymous"));
                array_push($quotes, array("Don't tell people about your dreams, show them", "Anonymous"));
                array_push($quotes, array("The best time for new beginnings is now", "Anonymous"));
                array_push($quotes, array("The secret of getting ahead is getting started", "Mark Twain"));
                array_push($quotes, array("With the new day comes new strength and new thoughts", "Eleanor Roosevelt"));
                array_push($quotes, array("It always seems impossible until it's done", "Nelson Mandela"));
                array_push($quotes, array("It's a slow process, but quitting won't speed up the process.", "Anonymous"));
                array_push($quotes, array("Difficult roads often lead to beautiful destinations", "Anonymous"));
                array_push($quotes, array("Don't be afraid to fail<br>Be afraid not to try", "Anonymous"));
                array_push($quotes, array("When you feel like quitting, think about why you started", "Anonymous"));
                array_push($quotes, array("The only bad workout is the one that didn't happen", "Anonymous"));
                array_push($quotes, array("Never let a stumble in the road be the end of the journey", "Anonymous"));
                array_push($quotes, array("You miss all the shots you don't take", "Wayne Gretzky"));
                array_push($quotes, array("If you can dream it, you can do it", "Walt Disney"));
                array_push($quotes, array("When people throw stones at you, convert them into milestones", "Sachin Tendulkar"));
                array_push($quotes, array("Life is like riding a bicycle. To keep your balance, you must keep moving", "Albert Einstein"));
                array_push($quotes, array("I can accept failure, but I can't accept not trying", "Michael Jordan"));
                array_push($quotes, array("Just keep swimming", "Dory, Finding Nemo"));

                // Select random quote
                $rand = array_rand($quotes, 1);
                $random_quote = $quotes[$rand];

                // Place text
                echo "<div class = 'quote'><h3>";
                echo '"';
                echo $random_quote[0];
                echo '"';
                echo "</h3></div>";

                // Place author
                echo "<div class = 'author'><h4>- ";
                echo $random_quote[1];
                echo "</h4></div>";
            ?>
        </div>

        <form id="post" action="post.php" method="post" accept-charset="UTF-8">
            <h1>Home</h1>

            <div class = "update_container">
                <input type="text" name="update" maxlength="500" placeholder="What's up?" required />
                <input type="submit" name="post" value="Post">
            </div>

            <h3></h3>

            <?php
                // Get post data
                $result = $db->query("SELECT * FROM posts ORDER BY post_id DESC LIMIT 100");

                // Print all posts
                while($row = $result->fetch(PDO::FETCH_ASSOC)) {
                    // Check if friends
                    $real_id = $_SESSION['user_id'];
                    $user_id = $row['user_id'];

                    // Check user_id
                    if ($user_id == $real_id) {
                        // Get user data
                        $user_data = $db->query("SELECT first_name, last_name, profile_picture FROM users WHERE user_id='$user_id' AND banned='0'");

                        while($row_user = $user_data->fetch(PDO::FETCH_ASSOC)) {
                            $first_name = $row_user['first_name'];
                            $last_name = $row_user['last_name'];
                            $img = $row_user['profile_picture'];
                            $content = str_replace(":db:", "<img src='db.gif' />", $row['content']);

                            // Print to screen
                            echo "<div class = 'post_field'>";
                            echo "<div class = 'profile_picture'><a href = 'profile.php?id=$user_id'><img src='data:image/jpeg;base64,".base64_encode($img)."'/></a></div>";
                            echo "<div class = 'post_container'>";
                            echo "<div class = 'poster'><p>$first_name $last_name</p></div>";
                            echo "<div class = 'post_content'><p>$content</p></div>";
                            echo "<div class = 'post_date'><p>".$row['datetime']."</p></div>";

                            if ($user_id == $_SESSION['user_id']) {
                                echo "<div class = 'post_delete'><a href = 'delete_post.php?post_id=".$row['post_id']."'>Delete</a></div>";
                            }

                            echo "</div></div>";
                        }
                    } else {
                        $friend_check = $db->query("SELECT * FROM friends WHERE user_id='$real_id' AND user_id_friend='$user_id' AND status='1'");

                        while($check = $friend_check->fetch(PDO::FETCH_ASSOC)) {
                            // Get user data
                            $user_data = $db->query("SELECT first_name, last_name, profile_picture FROM users WHERE user_id='$user_id' AND banned='0'");

                            while($row_user = $user_data->fetch(PDO::FETCH_ASSOC)) {
                                $first_name = $row_user['first_name'];
                                $last_name = $row_user['last_name'];
                                $img = $row_user['profile_picture'];
                                $content = str_replace(":db:", "<img src='db.gif' />", $row['content']);

                                // Print to screen
                                echo "<div class = 'post_field'>";
                                echo "<div class = 'profile_picture'><a href = 'profile.php?id=$user_id'><img src='data:image/jpeg;base64,".base64_encode($img)."'/></a></div>";
                                echo "<div class = 'post_container'>";
                                echo "<div class = 'poster'><p>$first_name $last_name</p></div>";
                                echo "<div class = 'post_content'><p>$content</p></div>";
                                echo "<div class = 'post_date'><p>".$row['datetime']."</p></div>";

                                if ($user_id == $_SESSION['user_id']) {
                                    echo "<div class = 'post_delete'><a href = 'delete_post.php?post_id=".$row['post_id']."'>Delete</a></div>";
                                }

                                echo "</div></div>";
                            }
                        }
                    }
                }
            ?>
        </form>
    </body>
</html>
