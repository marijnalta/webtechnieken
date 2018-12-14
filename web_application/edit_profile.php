<?php
    // Enable https
    include "enable_https.php";
    // Check if logged in
    include "login_check.php";
    // Connect to database
    include "opendb.php";

    // Check if password is correct
    $user_id = $_SESSION['user_id'];
    $result = $db->query("SELECT password FROM users WHERE user_id='$user_id'");

    while($row = $result->fetch(PDO::FETCH_ASSOC)) {
        if ($row['password'] != hash('sha256', $_POST['password_old'])) {
            header("Location: settings.php?incorrect=1");
            die();
        }
    }

    // Check if email already exists, redirect if so
    $email = strip_tags($_POST['email']);
    $result = $db->query("SELECT user_id FROM users WHERE email='$email'");

    while($row = $result->fetch(PDO::FETCH_ASSOC)) {
        if ($row['user_id'] != $user_id) {
            header("Location: settings.php?exists=1");
            die();
        }
    }

    // Check if passwords match
    if ($_POST['password_new'] != $_POST['password_verify']) {
        header("Location: settings.php?match=1");
        die();
    } elseif (!empty($_POST['password_new'])) {
        $password = strip_tags($_POST['password_new']);
        $password = hash('sha256', $password);
        $db->query("UPDATE users SET password='$password' WHERE user_id='$user_id'");
    }

    // Check if empty
    if (!empty($_FILES['image']['name'])) {
        // Upload profile picture
        $imagename = $_FILES['image']['name'];
        $imagetmp = addslashes(file_get_contents($_FILES['image']['tmp_name']));

        // Check if uploaded file is an image and not bigger than 2mb
        $allowed =  array('gif','png' ,'jpg', 'jpeg');
        $type = pathinfo($imagename, PATHINFO_EXTENSION);
        if(!in_array($type, $allowed) or $_FILES['image']['size'] > 2097152) {
            header("Location: settings.php?toobig=1");
            die();
        }

        $db->query("UPDATE users SET profile_picture='$imagetmp' WHERE user_id='$user_id'");
    }

    // Get data
    $first_name = strip_tags($_POST['first_name']);
    $last_name = strip_tags($_POST['last_name']);
    $bio = strip_tags($_POST['profile_content']);
    $birthdate = strip_tags($_POST['date_of_birth']);
    $strength = $_POST['strength'];
    $endurance = $_POST['endurance'];
    $date = date("Y/m/d");

    // Edit sex
    $sex = $_POST['gender'];

    if ($sex == "male") {
        $sex = "M";
    } else if ($sex == "female") {
        $sex = "F";
    } else {
        $sex = "O";
    }

    // Edit privacy settings
    $privacy = $_POST['privacy'];

    // Update values
    $query = "UPDATE users SET first_name='$first_name', last_name='$last_name', profile_content='$bio',
    email='$email', birthdate='$birthdate', sex='$sex', strength='$strength', endurance='$endurance',
    profile_private='$privacy' WHERE user_id='$user_id'";

    // Create account
    $db->query($query);

    // Redirect
    header("Location: profile.php");
?>
