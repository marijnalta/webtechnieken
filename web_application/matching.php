<?php
    // Enable https
    include "enable_https.php";
    // Connect to database
    include "login_check.php";

    // Source: https://stackoverflow.com/questions/10053358/measuring-the-distance-between-two-coordinates-in-php
    function distance ($latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo, $earthRadius = 6371000) {
        // convert from degrees to radians
        $latFrom = deg2rad($latitudeFrom);
        $lonFrom = deg2rad($longitudeFrom);
        $latTo = deg2rad($latitudeTo);
        $lonTo = deg2rad($longitudeTo);

        $lonDelta = $lonTo - $lonFrom;
        $a = pow(cos($latTo) * sin($lonDelta), 2) +
            pow(cos($latFrom) * sin($latTo) - sin($latFrom) * cos($latTo) * cos($lonDelta), 2);
        $b = sin($latFrom) * sin($latTo) + cos($latFrom) * cos($latTo) * cos($lonDelta);

        $angle = atan2(sqrt($a), $b);
        return $angle * $earthRadius;
    }

    function split_matches ($matches, $type, $value) {
        $check = true;
        $counter = 0;
        $number_of_matches = count($matches);
        $matches_good = array();
        $matches_bad = array();

        while ($check) {
            if ($counter == $number_of_matches) {
                $check = false;
            } else if ($matches[$counter][$type] > $value) {
                $check = false;
                $matches_good = array_slice($matches, 0, $counter);
                $matches_bad = array_slice($matches, $counter);
            }

            $counter++;
        }

        return array($matches_good, $matches_bad);
    }

    // Calculate age date
    function calcDate ($age_years) {
        $year = date("Y") - $age_years;
        $month = date("m");
        $day = date("d");
        return $year."-".$month."-".$day;
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" type="text/css" href="matching.css"/>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Matching</title>
        <link rel="shortcut icon" href="logo.ico" />
    </head>
    <body>
        <div class="navigation_bar">
            <p><a href="home.php"><img src="homeweight.png" class="homeweight"></a></p>
            <p><a href="settings.php"><img src="settingsnav.png" class="settingsnav"></a></p>
            <p><a href="inbox.php"><img src="inbox.png" class="inboxnav"></a></p>
            <p><a href="profile.php">
                <?php
                    $result = $db->query("SELECT * FROM users WHERE user_id=$_SESSION[user_id]");

                    while($row = $result->fetch(PDO::FETCH_ASSOC)) {
                        $first_name = $row['first_name'];
                        echo "<div class = 'profilenav'><p>$first_name</p></div>";
                    }
                ?>
            </a></p>
            <p><a href="matching.php">Matching</a></p>
            <p><a href="friends.php">Friends</a></p>
        </div>
        <p class="logout"><a href="logout.php"><img src="logoutnav.png" class="logoutnav"></a></p>

        <div class="wrap">
            <h1>Matches</h1>

            <div class="filter">
                <h2>Filter</h2>

                <form action="filter.php"  method="post" accept-charset="UTF-8" >
                    <div class="row">
                        <div class="option">
                            <label for="age">Age</label>

                            <div class="dropdown">
                                <select id="age" name="age">
                                    <option value="15-24">15-24</option>
                                    <option value="25-34">25-34</option>
                                    <option value="35-44">35-44</option>
                                    <option value="45-54">45-54</option>
                                    <option value="55-64">55-64</option>
                                    <option value="65-74">65-74</option>
                                    <option value="75-84">75-84</option>
                                    <option value="85-94">85-94</option>
                                </select>
                            </div>
                        </div>

                        <div class="option">
                            <div class="checkgender">
                                <label class="container">
                                    <input type="checkbox" name="male" value="male" checked>Male<br>
                                    <span class="checkmark"></span>
                                </label>

                                <label class="container">
                                    <input type="checkbox" name="female" value="female" checked> Female<br>
                                    <span class="checkmark"></span>
                                </label>

                                <label class="container">
                                    <input type="checkbox" name="other" value="other" checked> Other
                                    <span class="checkmark"></span>
                                </label>
                            </div>
                        </div>

                        <div class="option">
                            <div class="slider_container">
                                <?php
                                    // Get strength / endurance
                                    include "opendb.php";

                                    $user_id = $_SESSION['user_id'];
                                    $result = $db->query("SELECT strength, endurance FROM users WHERE user_id='$user_id'");

                                    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                                        $strength = $row['strength'];
                                        $endurance = $row['endurance'];
                                        echo "<div class='slider_strength'>";
                                        echo "<label for='strength'>Strength:</label>";
                                        echo "<input type='range' name='strength' min='1' max='5' value='$strength' class='slider' id='strength'>";
                                        echo "</div>";
                                        echo "<div class='slider_endurance'>";
                                        echo "<label for='endurance' >Endurance:</label>";
                                        echo "<input type='range' name='endurance' min='1' max='5' value='$endurance' class='slider' id='endurance'>";
                                        echo "</div>";
                                    }
                                ?>
                            </div>
                        </div>
                    </div>

                    <div class="submitbutton">
                        <input type="submit" name="Apply" value="Apply"/>
                    </div>

                    <br><br>
                    <div class="option">
                        <div class="filter_options">
                            <br>
                            <label class="container">
                                <input type="checkbox" name="en_age" value="en_age"> Enable Age<br>
                                <span class="checkmark"></span>
                            </label>

                            <label class="container">
                                <input type="checkbox" name="en_gender" value="en_gender"> Enable gender<br>
                                <span class="checkmark"></span>
                            </label>

                            <label class="container">
                                <input type="checkbox" name="en_strength" value="en_strength"> Enable strength<br>
                                <span class="checkmark"></span>
                            </label>

                            <label class="container">
                                <input type="checkbox" name="en_endurance" value="en_endurance"> Enable endurance<br>
                                <span class="checkmark"></span>
                            </label>
                        </div>
                    </div>
                </form>
             </div>
             <br><br>

            <div class="box">
                <?php
                    // Get user data
                    $user_id = $_SESSION['user_id'];
                    $result = $db->query("SELECT * FROM users WHERE user_id='$user_id'");

                    while($row = $result->fetch(PDO::FETCH_ASSOC)) {
                        $lat = $row['latitude'];
                        $lon = $row['longitude'];
                        $birthdate = $row['birthdate'];
                        $strength = $row['strength'];
                        $endurance = $row['endurance'];
                    }

                    // Filter strength
                    $strength_q = "";
                    if (!empty($_SESSION['strength'])) {
                        $strength_q = "AND strength=".$_SESSION['strength'];
                        unset($_SESSION['strength']);
                    }

                    // Filter endurance
                    $endurance_q = "";
                    if (!empty($_SESSION['endurance'])) {
                        $endurance_q = "AND endurance=".$_SESSION['endurance'];
                        unset($_SESSION['endurance']);
                    }

                    // Filter gender
                    $gender_q = "";
                    if (!empty($_SESSION['male']) and !empty($_SESSION['female']) and !empty($_SESSION['other'])) {
                        $gender_q = "";
                    } elseif (empty($_SESSION['male']) and !empty($_SESSION['female']) and !empty($_SESSION['other'])) {
                        $gender_q = "AND sex!='M'";
                    } elseif (!empty($_SESSION['male']) and empty($_SESSION['female']) and !empty($_SESSION['other'])) {
                        $gender_q = "AND sex!='F'";
                    } elseif (!empty($_SESSION['male']) and !empty($_SESSION['female']) and empty($_SESSION['other'])) {
                        $gender_q = "AND sex!='O'";
                    } elseif (!empty($_SESSION['male']) and empty($_SESSION['female']) and empty($_SESSION['other'])) {
                        $gender_q = "AND sex='M'";
                    } elseif (empty($_SESSION['male']) and !empty($_SESSION['female']) and empty($_SESSION['other'])) {
                        $gender_q = "AND sex='F'";
                    } elseif (empty($_SESSION['male']) and empty($_SESSION['female']) and !empty($_SESSION['other'])) {
                        $gender_q = "AND sex='O'";
                    }

                    unset($_SESSION['male']);
                    unset($_SESSION['female']);
                    unset($_SESSION['other']);

                    // Filter age
                    $age_q = "";

                    if (!empty($_SESSION['age'])) {
                        $ages = explode("-", $_SESSION['age']);
                        $lower_bound = calcDate($ages[1]);
                        $upper_bound = calcDate($ages[0]);
                        unset($_SESSION['age']);

                        $age_q = "AND birthdate BETWEEN '$lower_bound' AND '$upper_bound'";
                    }

                    // Get other user data
                    $result = $db->query("SELECT * FROM users WHERE user_id!='$user_id' AND banned='0' $strength_q $endurance_q $gender_q $age_q ORDER BY user_id");
                    $matches = array();

                    // Create array
                    while($row = $result->fetch(PDO::FETCH_ASSOC)) {
                        // Filter out friends
                        $friend_id = $row['user_id'];
                        $friend_check = $db->query("SELECT * FROM friends WHERE user_id='$user_id' AND user_id_friend='$friend_id' AND status='1'");

                        $friends = 0;
                        while($check = $friend_check->fetch(PDO::FETCH_ASSOC)) {
                            $friends = 1;
                        }

                        if ($friends == 0) {
                            // Calculate difference to user
                            $d_location = distance($lat, $lon, $row['latitude'], $row['longitude']);
                            $d_age = date_diff(date_create($row['birthdate']), date_create($birthdate))->y;;
                            $d_stats = abs($strength + $endurance - $row['strength'] - $row['endurance']);

                            array_push($matches, array($row['user_id'], $d_location, $d_age, $d_stats));
                        }
                    }

                    // Sort array on distance
                    array_multisort(array_column($matches, 1), SORT_ASC, $matches);

                    // Split array on distance difference of 10km
                    $splitted_matches = split_matches($matches, 1, 10000);
                    $matches_near = $splitted_matches[0];
                    $matches_far = $splitted_matches[1];

                    // Sort new array on age
                    array_multisort(array_column($matches_near, 2), SORT_ASC, $matches_near);

                    // Split array on age difference of 4 years
                    $splitted_matches = split_matches($matches_near, 2, 4);
                    $matches_same_age = $splitted_matches[0];
                    $matches_different_age = $splitted_matches[1];

                    // Sort new array on stats
                    array_multisort(array_column($matches_same_age, 3), SORT_ASC, $matches_same_age);

                    $sorted_matches = array();
                    $sorted_matches = array_merge($sorted_matches, $matches_same_age);
                    $sorted_matches = array_merge($sorted_matches, $matches_different_age);
                    $sorted_matches = array_merge($sorted_matches, $matches_far);

                    // Create matches
                    foreach ($matches as $match) {
                        $match_id = $match[0];
                        $result = $db->query("SELECT first_name, last_name, profile_picture, profile_content, birthdate, sex, location FROM users WHERE user_id='$match_id' AND banned='0'");

                        while($row = $result->fetch(PDO::FETCH_ASSOC)) {
                            $first_name = $row['first_name'];
                            $last_name = $row['last_name'];
                            $img = $row['profile_picture'];
                            $age = date_diff(date_create($row['birthdate']), date_create('today'))->y;;
                            $sex = $row['sex'];
                            $location = $row['location'];

                            // Print to screen
                            echo "<div class = 'grid'>";
                            echo "<a href = 'profile.php?id=$match_id'><img class = 'picture' src='data:image/jpeg;base64,".base64_encode($img)."'/></a>";
                            echo "<div class = 'info'>";
                            echo "<div><p class = 'poster'>$first_name $last_name</p>";
                            echo "<p class= 'poster_info'>$age years old ($sex), located in $location</p></div>";
                            echo "<div><p class = 'text'>".$row['profile_content']."</p></div>";
                            echo "</div></div>";
                        }
                    }
                ?>
            </div>
        </div>
    </body>
</html>
