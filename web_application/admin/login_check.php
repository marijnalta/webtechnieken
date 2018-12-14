<?php
    // Start session
    session_start();

    // Check if user is logged in, redirect if not
    if (empty($_SESSION['adminname'])) {
        header("Location: index.php");
    }
?>
