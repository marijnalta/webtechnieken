<?php
    $mysql_host = "";
    $mysql_database = "workoutpal";
    $mysql_username = "marijn";
    $mysql_password = "YOURPASSWORD";

    $db = new PDO("mysql:host=$mysql_host;dbname=$mysql_database;charset=utf8",
                "$mysql_username", "$mysql_password")
                or die('Error connecting to mysql server');
?>
