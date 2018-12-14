<?php
    // Enable https
    include "enable_https.php";
    // Connect to database
    include "opendb.php";

    // Check if forms aren't empty
    if(empty($_POST['username']) or empty($_POST['password'])) {
        header("Location: index.php");
        die();
    }

    // Get data and encrypt password
    $username = strip_tags($_POST['username']);
    $password = hash('sha256', strip_tags($_POST['password']));

    // Get data from admins table
    $result = $db->query("SELECT adminname, password FROM admins WHERE adminname='$username' AND password='$password'");

    // Check if login is valid
    while($row = $result->fetch(PDO::FETCH_ASSOC)) {
        // Save username
        session_start();
        $_SESSION['adminname'] = $username;

        // Redirect
        header("Location: admin_panel.php");
        die();
    }

    // Redirect
    header("Location: index.php");
?>
