<?php
    // Enable https
    include "enable_https.php";
    // Connect to database
    include "opendb.php";

    // Check if user entered data, redirect if otherwise
    if(empty($_POST['email']) or empty($_POST['password'])) {
        header("Location: index.php");
        die();
    }

    // Modify user input
    $email = strip_tags($_POST['email']);
    $password = strip_tags($_POST['password']);
    $password = hash('sha256', $password);

    // Check if user exists
    $result = $db->query("SELECT user_id, email, password FROM users WHERE email='$email' AND password='$password' AND banned='0'");

    // Check if login is valid
    while($row = $result->fetch(PDO::FETCH_ASSOC)) {
        // Start session
        session_start();

        // Save username & user_id
        $_SESSION['email'] = $email;
        $_SESSION['user_id'] = $row['user_id'];

        // Update location
        if (empty($_POST['lat']) or empty($_POST['lon'])) {
            // Get coordinates and city by ip adress
            $user_ip = getenv('REMOTE_ADDR');
            $geo = unserialize(file_get_contents("http://www.geoplugin.net/php.gp?ip=$user_ip"));
            $lat = $geo["geoplugin_latitude"];
            $lon = $geo["geoplugin_longitude"];
            $location = $geo["geoplugin_city"];
        } else {
            // Get latitude and longitude
            $lat = $_POST['lat'];
            $lon = $_POST['lon'];

            // Get data from googlemaps API
            $str = file_get_contents("https://maps.googleapis.com/maps/api/geocode/json?latlng=$lat,$lon&sensor=true&key=AIzaSyA9hQQ4D-gPuH38AYRJt6AnPmMUdaQ19Rs");
            $json = json_decode($str, true);

            // Get name of city
            if ($json['results'][0]['address_components'][0]['types'][0] == "locality") {
                $location = $json['results'][0]['address_components'][0]['long_name'];
            } else if ($json['results'][0]['address_components'][1]['types'][0] == "locality") {
                $location = $json['results'][0]['address_components'][1]['long_name'];
            } else {
                $location = $json['results'][0]['address_components'][2]['long_name'];
            }
        }

        $user_id = $row['user_id'];
        $db->query("UPDATE users SET location='$location', latitude='$lat', longitude='$lon' WHERE user_id='$user_id'");

        // Redirect
        header("Location: home.php");
        die();
    }

    // Redirect
    header("Location: index.php");
?>
