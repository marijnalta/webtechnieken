<?php
    // Enable https
    include "enable_https.php";
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Login</title>
        <meta charset="UTF-8">
        <link rel="stylesheet" type="text/css" href="style.css">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="shortcut icon" href="logo.ico" />
    </head>
    <body>
        <div class="login-box">
            <img src="person.png" class="avatar">

            <h1>Sign in to Workout Pal</h1>
            <p>Your buddies are waiting for you!</p>

            <form id="login" name="login" action="login.php" method="post" accept-charset="UTF-8">
                <div class="loginput">
                    <input type="email" name="email" maxlength="100" placeholder="Example@email.com" required />
                    <input type="password" name="password" maxlength="100" placeholder="Password" required />

                    <!-- Get location -->
                    <input type="hidden" name="lat" value="">
                    <input type="hidden" name="lon" value="">
                </div>

                <input type="submit" name="submit" value="Sign in">
                <div class="loginurl">
                    <a href="#"> Reset password</a>
                    <a href="register.php"> I don't have an account</a>
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

                    document.login.lat.value = lat;
                    document.login.lon.value = lon;
                }
            </script>
        </div>

        <div class="footer">
            <p>Workout Pal © 2018</p> ・
            <p><a href="about_us.php">About Us</a></p> ・
            <p><a href="contact.php">Contact</a></p> ・
            <p><a href="our_mission.php">Our Mission</a></p>
        </div>
    </body>
</html>
