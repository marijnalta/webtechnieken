<?php
    // Enable https
    include "enable_https.php";

    // End session
    session_start();
    unset($_SESSION['adminname']);
    unset($_SESSION['query']);
    unset($_SESSION['search']);
    unset($_SESSION['sql']);
    session_destroy();
?>

<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" type="text/css" href="style.css"/>
        <link rel="shortcut icon" href="logo.ico" />
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Log Out</title>
    </head>
    <body>
        <div class="logout-box">
            <img src="person.png" class="avatar">
            <h1>Signed out</h1>

            <p>You have been succesfully signed out.</p>

            <p><a href="index.php">Didn't mean to log out? Sign in again!</a></p>
        </div>
    </body>
</html>
