<?php
    // Enable https
    include "enable_https.php";
    // Check if logged in
    include "login_check.php";

    // Get age
    if (!empty($_POST['en_age'])) {
        $_SESSION['age'] = $_POST['age'];
    }

    // Check gender
    if (!empty($_POST['en_gender'])) {
        if (!empty($_POST['male'])) {
            $_SESSION['male'] = "M";
        }
        if (!empty($_POST['female'])) {
            $_SESSION['female'] = "F";
        }
        if (!empty($_POST['other'])) {
            $_SESSION['other'] = "O";
        }
    }

    // Get strength
    if (!empty($_POST['en_strength'])) {
        $_SESSION['strength'] = $_POST['strength'];
    }

    // Get endurance
    if (!empty($_POST['en_endurance'])) {
        $_SESSION['endurance'] = $_POST['endurance'];
    }

    header("Location: matching.php");
?>
