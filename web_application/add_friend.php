<?php
    // Enable https
    include "enable_https.php";
    // Connect to database
    include "opendb.php";
    // Check if logged id
    include "login_check.php";

    // Check if friends or not
    $user_id = $_SESSION['user_id'];
    $friend_id = $_GET['id'];
    $result = $db->query("SELECT * FROM friends WHERE user_id='$user_id' AND user_id_friend='$friend_id'");

    while($row = $result->fetch(PDO::FETCH_ASSOC)) {
        if (($row['status'] == 1) or ($row['status'] == 2 and $row['action_user_id'] == $user_id)) {
            // Unfriend when status 1 or retract invite if status 2
            $db->query("DELETE FROM friends WHERE user_id='$user_id' AND user_id_friend='$friend_id'");
            $db->query("DELETE FROM friends WHERE user_id='$friend_id' AND user_id_friend='$user_id'");
        } elseif ($row['status'] == 2 and $row['action_user_id'] == $friend_id) {
            // Accept request if other person sent invite
            $db->query("UPDATE friends SET status='1' WHERE user_id='$user_id' AND user_id_friend='$friend_id' AND status='2' AND action_user_id='$friend_id'");
            $db->query("UPDATE friends SET status='1' WHERE user_id='$friend_id' AND user_id_friend='$user_id' AND status='2' AND action_user_id='$friend_id'");
        }

        // Redirect
        header("Location: profile.php?id=$friend_id");
        die();
    }

    if ($user_id != $friend_id) {
        // Send friend request
        $db->query("INSERT INTO friends (user_id, user_id_friend, status, action_user_id)
        VALUES ('$user_id', '$friend_id', '2', '$user_id')");
        $db->query("INSERT INTO friends (user_id, user_id_friend, status, action_user_id)
        VALUES ('$friend_id', '$user_id', '2', '$user_id')");

        // Redirect
        header("Location: profile.php?id=$friend_id");
    } else {
        // Redirect
        header("Location: friends.php");
    }
?>
