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
        <link rel="shortcut icon" href="logo.ico" />
        <script src="jquery-3.3.1.min.js"></script>
        <meta charset="UTF-8">
        <link rel="shortcut icon" href="logo.ico" />
        <title>Settings</title>
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

        <div class="delete_account">
            <h1>Are you sure to delete your account?</h1>
        </div>

        <div class="delete_confirm">
            <h2><a href="delete_user.php">YES</a> / <a href="settings.php">NO</a></h2>
        </div>
    </body>
</html>
