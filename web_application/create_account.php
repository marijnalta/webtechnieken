<?php
    // Enable https
    include "enable_https.php";
    // Connect to database
    include "opendb.php";

    // Start session
    session_start();

    // Check if email already exists
    $email = strip_tags($_POST['email']);
    $result = $db->query("SELECT email FROM users WHERE email='$email'");

    // Redirect if so
    while($row = $result->fetch(PDO::FETCH_ASSOC)) {
        header("Location: register.php?exists=1");
        die();
    }

    // Check if passwords match
    if ($_POST['password'] != $_POST['password_check']) {
        header("Location: register.php?match=1");
        die();
    }

    // Upload profile picture
    $imagename = $_FILES['image']['name'];
    $imagetmp = addslashes(file_get_contents($_FILES['image']['tmp_name']));

    // Check if uploaded file is an image and not bigger than 2mb
    $allowed =  array('gif','png' ,'jpg', 'jpeg');
    $type = pathinfo($imagename, PATHINFO_EXTENSION);
    if(!in_array($type, $allowed) or $_FILES['image']['size'] > 2097152) {
        header("Location: register.php?toobig=1");
        die();
    }

    // Get data
    $first_name = strip_tags($_POST['first_name']);
    $last_name = strip_tags($_POST['last_name']);
    $birthdate = $_POST['date_of_birth'];
    $password = strip_tags($_POST['password']);
    $password = hash('sha256', $password);
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

    // Get ip
    $user_ip = getenv('REMOTE_ADDR');

    // Get more accurate location if possible
    if (empty($_POST['lat']) or empty($_POST['lon'])) {
        // Get coordinates and city by ip adress
        $geo = unserialize(file_get_contents("http://www.geoplugin.net/php.gp?ip=$user_ip"));
        $lat = $geo["geoplugin_latitude"];
        $lon = $geo["geoplugin_longitude"];
        $location = $geo["geoplugin_city"];
    } else {
        // Get latitude and longitude
        $lat = $_POST['lat'];
        $lon = $_POST['lon'];

        // Get data from googlemaps API
        $str = file_get_contents("https://maps.googleapis.com/maps/api/geocode/json?latlng=$lat,$lon&sensor=true&key=KEY");
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

    // Create query
    $profile_content = "Click the gear icon next to the home icon at the top to change your bio!";

    $query = "INSERT INTO users (first_name, last_name, birthdate, sex, email, password,
    location, latitude, longitude, strength, endurance, registration_date, user_ip, profile_picture, profile_content)
    VALUES ('$first_name', '$last_name', '$birthdate', '$sex', '$email', '$password',
    '$location', '$lat', '$lon', '$strength', '$endurance', '$date', '$user_ip', '$imagetmp', '$profile_content')";

    // Create account
    $db->query($query);

    // Get user id
    $user_query = $db->query("SELECT user_id FROM users WHERE email='$email' AND password='$password'");

    while($row = $user_query->fetch(PDO::FETCH_ASSOC)) {
        // Get post data
        $user_id = $row['user_id'];
        $content = "Hey there, I am looking for workout pals!";
        $date = date('Y/m/d H:i');

        // Hello world post
        $db->query("INSERT INTO posts (user_id, datetime, content) VALUES ('$user_id', '$date', '$content')");

        // Add the team as friends
        $db->query("INSERT INTO friends (user_id, user_id_friend, status, action_user_id) VALUES ('$user_id', '1', '1', '1')");
        $db->query("INSERT INTO friends (user_id, user_id_friend, status, action_user_id) VALUES ('1', '$user_id', '1', '1')");

        $db->query("INSERT INTO friends (user_id, user_id_friend, status, action_user_id) VALUES ('$user_id', '4', '1', '4')");
        $db->query("INSERT INTO friends (user_id, user_id_friend, status, action_user_id) VALUES ('4', '$user_id', '1', '4')");

        $db->query("INSERT INTO friends (user_id, user_id_friend, status, action_user_id) VALUES ('$user_id', '5', '1', '5')");
        $db->query("INSERT INTO friends (user_id, user_id_friend, status, action_user_id) VALUES ('5', '$user_id', '1', '5')");

        $db->query("INSERT INTO friends (user_id, user_id_friend, status, action_user_id) VALUES ('$user_id', '7', '1', '7')");
        $db->query("INSERT INTO friends (user_id, user_id_friend, status, action_user_id) VALUES ('7', '$user_id', '1', '7')");

        // Save user_id
        $_SESSION['user_id'] = $user_id;

        // Redirect
        header("Location: home.php");
    }
?>
