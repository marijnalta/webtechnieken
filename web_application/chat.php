<?php
    // Enable https
    include "enable_https.php";
    // Check if logged in
    include "login_check.php";

    // Get chat_id
    $_SESSION['chat_id'] = $_GET['id'];
?>

<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" type="text/css" href="stylesheet.css"/>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Chat</title>
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

        <div class="chat_container" onmouseover="showHint()">
            <script>
                showHint();

                function showHint() {
                    var xmlhttp = new XMLHttpRequest();
                    xmlhttp.onreadystatechange = function() {
                      if (this.readyState == 4 && this.status == 200) {
                          document.getElementById("php_fill").innerHTML = this.responseText;
                      }
                    };
                    xmlhttp.open("GET", "chat_data.php", true);
                    xmlhttp.send();
                }
            </script>

            <div id="php_fill"></div>

            <form action="message.php" method="post" accept-charset="UTF-8">
                <input type="text" name="update" maxlength="500" placeholder="What's up?" onfocus="showHint()" required />
                <input type="submit" name="post" value="Post">
            </form>
        </div>
    </body>
</html>
