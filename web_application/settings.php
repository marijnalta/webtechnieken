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
                        echo "<div class='profilenav'><p>$first_name</p></div>";
                    }
                ?>
            </a></p>
            <p><a href="matching.php">Matching</a></p>
            <p><a href="friends.php">Friends</a></p>
        </div>
        <p class="logout"><a href="logout.php"><img src="logoutnav.png" class="logoutnav"></a></p>

        <?php
            // Get user id from session
            $user_id = $_SESSION['user_id'];

            // Get post data
            $result = $db->query("SELECT * FROM users WHERE user_id='$user_id'");

            // Save data
            while($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $first_name = $row['first_name'];
                $last_name = $row['last_name'];
                $bio = $row['profile_content'];;
                $email = $row['email'];
                $password = $row['password'];
                $birthdate = $row['birthdate'];
                $strength = $row['strength'];
                $endurance = $row['endurance'];
                $profile_private = $row['profile_private'];
            }

            // Check gender to check the right checkbox
            function checkGender($type) {
                // Connect to database
                include "opendb.php";

                // Get user id from session
                $user_id = $_SESSION['user_id'];

                // Get post data
                $result = $db->query("SELECT sex FROM users WHERE user_id='$user_id'");

                while($row = $result->fetch(PDO::FETCH_ASSOC)) {
                    if ($row['sex'] == $type) {
                        echo "checked";
                    }
                }
            }

            // Check privacy to check the right checkbox
            function checkPrivacy($type) {
                // Connect to database
                include "opendb.php";

                // Get user id from session
                $user_id = $_SESSION['user_id'];

                // Get post data
                $result = $db->query("SELECT profile_private FROM users WHERE user_id='$user_id'");

                while($row = $result->fetch(PDO::FETCH_ASSOC)) {
                    if ($row['profile_private'] == $type) {
                        echo "checked";
                    }
                }
            }
        ?>

        <div class = "settings_container">
            <form id="post" action="edit_profile.php" method="post" accept-charset="UTF-8" enctype="multipart/form-data">
                <h1>Settings</h1>

                <div id="settings_name" class="settings_box">
                    <label for="name">Name:</label>
                    <input type="text" name="first_name" id="first_name" maxlength="50" placeholder="First name" value = <?php echo "'$first_name'"; ?> required/>
                    <input type="text" name="last_name" id="last_name" maxlength="50" placeholder="Last name" value = <?php echo "'$last_name'"; ?> required/>
                </div>

                <div id="settings_bio" class="settings_box">
                    <label for="bio">Write something about yourself:</label><br>
                    <textarea type="text" name="profile_content" id="profile_content" maxlength="360" placeholder="Bio text, displayed on your profile." required cols="40" rows="auto"><?php echo "$bio"; ?></textarea>
                </div>

                <div id="settings_email" class="settings_box">
                    <label for="email">Email:</label>
                    <input type="email" name="email" id="email" maxlength="100" placeholder="Email@example.com" value = <?php echo "'$email'"; ?> required/>
                </div>

                <div id="settings_birthdate" class="settings_box">
                    <label for="date_of_birth">Date of birth:</label>
                    <input type="date" name="date_of_birth" id="date_of_birth" value = <?php echo "'$birthdate'"; ?> required>
                </div>

                <div id="settings_gender" class="settings_box">
                    <label for="gender">Gender:</label>
                    <div class="checkgender">
                        <label class="container">
                            <input type="radio" name="gender" value="male" <?php checkGender("M"); ?>> Male<br>
                            <span class="checkmark"></span>
                        </label>
                        <label class="container">
                            <input type="radio" name="gender" value="female" <?php checkGender("F"); ?>> Female<br>
                            <span class="checkmark"></span>
                        </label>
                        <label class="container">
                            <input type="radio" name="gender" value="other" <?php checkGender("O"); ?>> Other
                            <span class="checkmark"></span>
                        </label>
                    </div>
                    <br>
                </div>

                <div id="settings_strength" class="settings_box">
                    <label for="strength">Rate your strength:</label>
                    <input type="range" name="strength" min="1" max="5" value = <?php echo "'$strength'"; ?> class="slider" id="strength">
                </div>

                <div id="settings_endurance" class="settings_box">
                    <label for="endurance">Rate your endurance:</label>
                    <input type="range" name="endurance" min="1" max="5" value = <?php echo "'$endurance'"; ?> class="slider" id="endurance">
                </div>

                <div id="settings_img" class="settings_box">
                    <label for="image">Profile picture:</label>
                    <input type="file" name="image" accept="image/*">
                </div>

                <div id="settings_privacy" class="settings_box">
                    <label for="privacy">Profile:</label>
                    <div class="checkgender">
                        <label class="container">
                            <input type="radio" name="privacy" value="0" <?php checkPrivacy(0); ?>> Public<br>
                            <span class="checkmark"></span>
                        </label>
                        <label class="container">
                            <input type="radio" name="privacy" value="1" <?php checkPrivacy(1); ?>> Private<br>
                            <span class="checkmark"></span>
                        </label>
                    </div>
                    <br><br>
                </div>

                <div id="settings_password" class="settings_box">
                    <label for="password_old">Confirm password to save changes:</label>
                    <br>
                    <input type="password" name="password_old" id="password" maxlength="100" placeholder="Old password" required/>
                    <br>
                    <br>
                    <label for="password_new">If you want to, you can also change your pasword:</label>
                    <br>
                    <input type="password" name="password_new" id="password" maxlength="100" placeholder="New password"/>
                    <br>
                    <input type="password" name="password_verify" id="password" maxlength="100" placeholder="Verify password"/>
                </div>

                <div id="settings_change" class="settings_box">
                    <input type="submit" name="Submit" value="Change profile"/>
                </div>

                <div class="delete_account_settings">
                    <a href="delete_account.php"><p>Delete account?</p></a>
                </div>
            </form>
        </div>
    </body>
</html>
