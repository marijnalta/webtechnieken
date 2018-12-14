<?php
    // Enable https
    include "enable_https.php";
?>
<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" type="text/css" href="style.css"/>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Register</title>
        <link rel="shortcut icon" href="logo.ico" />
    </head>
    <body>
        <div class="register-box">
            <img src="person.png" class="avatar">

            <h1>Join the Workout Pal fitness community and find workout partners, set goals and more!</h1>

            <form id="login" name="register" action="create_account.php" method="post" accept-charset="UTF-8"  enctype="multipart/form-data">
                <input type="hidden" name="submitted" id="submitted" value="1"/>

                <?php
                    if (!empty($_GET['toobig'])) {
                        echo "<p id='toobig'>You can only submit images up to 2mb in size!</p><br>";
                    }
                ?>

                <input type="text" name="first_name" id="first_name" maxlength="50" placeholder="First Name" required/>
                <br><br>

                <input type="text" name="last_name" id="last_name" maxlength="50" placeholder="Last Name" required/>
                <br><br>

                <input type="email" name="email" id="email" maxlength="100" placeholder="Email@example.com" required/>

                <?php
                    if (!empty($_GET['exists'])) {
                        echo "<p id='email_already_exists'>Please enter a different address.</p><br>";
                    } else {
                        echo "<br><br>";
                    }
                ?>

                <input type="password" name="password" id="password" maxlength="100" placeholder="Password" required/>
                <br><br>

                <input type="password" name="password_check" id="password_check" maxlength="100" placeholder="Verify password"  onChange="checkPasswordMatch();" required/>

                <p id="match">
                    <?php
                        if (!empty($_GET['match'])) {
                            echo "Passwords don't match!";
                        }
                    ?>
                </p><br>

                <script>
                    function checkPasswordMatch() {
                        var password = document.register.password.value;
                        var password_check = document.register.password_check.value;

                        if (password != password_check)
                            document.getElementById("match").innerHTML = "Passwords don't match!"
                        else
                            document.getElementById("match").innerHTML = "Passwords match!";
                    }
                </script>

                <label for="birthdate">What is your birthdate? (dd-mm-yyyy)</label><br>
                <input type="date" name="date_of_birth" id="date_of_birth" required>

                <div class="checkgender">
                <label class="container">
                    <input type="radio" name="gender" value="male" checked>Male<br>
                    <span class="checkmark"></span>
                </label>
                <label class="container">
                    <input type="radio" name="gender" value="female"> Female<br>
                    <span class="checkmark"></span>
                </label>
                <label class="container">
                    <input type="radio" name="gender" value="other"> Other
                    <span class="checkmark"></span>
                </label>
                </div>

                <label for="strength" >Rate your strength:</label>
                <input type="range" name="strength" min="1" max="5" value="3" class="slider" id="strength">
                <div class="tooltip">
                    <img src="info.png" class="info">
                    <span class="tooltiptext"> Are you a beginner or an expert in the category strength? Will be used to match you to another user.</span>
                </div>
                <br>

                <label for="endurance" >Rate your endurance:</label>
                <input type="range" name="endurance" min="1" max="5" value="3" class="slider" id="endurance">
                <div class="tooltip">
                    <img src="info.png" class="info">
                    <span class="tooltiptext">Are you a beginner or an expert in the category endurance? Will be used to match you to another user.</span>
                </div>
                <br>

                <!-- profile picture -->
                <label for="image">Profile picture:</label>
                <input type="file" name="image" accept="image/*" required><br><br>

                <!-- Get location -->
                <input type="hidden" name="lat" value="" >
                <input type="hidden" name="lon" value="" >

                <div class="submitbutton">
                    <input type="submit" name="Submit" value="Sign up"/>
                    <br><br><br>
                </div>

                <div class="loginurl">
                    <a href="index.php">Already have a Workout Pal account? Log in</a>
                </div>
            </form>

            <!-- Get location -->
            <script>
                getLocation();

                function getLocation() {
                    if (navigator.geolocation) {
                        navigator.geolocation.getCurrentPosition(showPosition);
                    }
                }
                function showPosition(position) {
                    var lat = position.coords.latitude;
                    var lon = position.coords.longitude;

                    document.register.lat.value = lat;
                    document.register.lon.value = lon;
                }
            </script>
        </div>
    </body>
</html>
