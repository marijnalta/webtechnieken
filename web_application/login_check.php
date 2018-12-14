<?php
    // Start session
    session_start();

    // Check if user is logged in, redirect is not
    if (empty($_SESSION['user_id'])) {
        header("Location: index.php");
    }

    // Connect to database
    include "opendb.php";
?>
