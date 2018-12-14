<?php
    // Enable https
    include "enable_https.php";

    // End session
    session_start();
    unset($_SESSION['email']);
    unset($_SESSION['password']);
    session_destroy();
?>

<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" type="text/css" href="style.css"/>
        <meta charset="UTF-8">
        <link rel="shortcut icon" href="logo.ico" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Log Out</title>
    </head>
    <body>
        <div class="logout-box">
            <img src="person.png" class="avatar">
            <h1>Signed out</h1>

            <p>You have been succesfully signed out.</p>

            <p><a href="index.php">Didn't mean to log out? Sign in again! </a></p>
        </div>
    </body>
    <div class="footer">
        <p>Workout Pal © 2018</p> ・
        <p><a href="about_us.php">About Us</a></p> ・
        <p><a href="contact.php">Contact</a></p> ・
        <p><a href="our_mission.php">Our Mission</a></p> ・
        <p><a href="index.php">Back to login</a></p>
    </div>
</html>
