<?php
    // Enable https
    include "enable_https.php";
    // Check if logged in
    include 'login_check.php';

    // Create query
    $search = $_POST['search'];

    // Split in array
    $search_array = explode(" ", $search);
    $first_name = $search_array[0];
    $last_name = $search_array[1];

    // Save in session
    if (empty($search)) {
        $_SESSION['sidebar_search'] = "SELECT * FROM users WHERE banned=0 ORDER BY user_id DESC LIMIT 100";
    } elseif (empty($search_array[1])) {
        $_SESSION['sidebar_search'] = "SELECT user_id, first_name, last_name, birthdate, sex, location, profile_content, profile_picture FROM users WHERE first_name LIKE '%$first_name%' AND banned=0 ORDER BY first_name LIMIT 100";
    } else {
        $_SESSION['sidebar_search'] = "SELECT user_id, first_name, last_name, birthdate, sex, location, profile_content, profile_picture FROM users WHERE (first_name LIKE '%$first_name%' OR last_name LIKE '%$last_name%') AND banned=0 ORDER BY first_name LIMIT 100";
    }

    // Refresh home.php
    header("Location: home.php");
?>
