<?php
    // Enable https
    include "enable_https.php";
    // Check if logged in
    include 'login_check.php';

    // Set queries for searches
    if (empty($_SESSION['sql']) or empty($_SESSION['query'])) {
        $_SESSION['sql'] = "SELECT * FROM users ORDER BY user_id DESC";
        $_SESSION['query'] = "SELECT * FROM posts ORDER BY post_id DESC";
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" type="text/css" href="stylesheet_admin.css"/>
        <link rel="shortcut icon" href="logo.ico" />
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Admin Panel</title>
    </head>
    <body>
        <!-- header -->
        <div id="admin_header">
            <div id="admin_title">
                <h1>Admin Panel</h1>
            </div>
        </div>

        <!-- left side of panel -->
        <div id="left_panel">
            <div id="user_list" class="panel">
                <h2>Users</h2>

                <form id="post" action="search_users.php" method="post" accept-charset="UTF-8">
                    SELECT * FROM users
                    <input type="text" name="sql" maxlength="500" placeholder="SQL code" />
                    <input type="submit" name="search" value="Search">
                </form>

                <br>

                <iframe src="users.php"></iframe>
            </div>
        </div>

        <!-- right side of panel -->
        <div id="right_panel">
            <div id="admin_feed" class="panel">
                <h2>Posts</h2>

                <form id="post" action="search_posts.php" method="post" accept-charset="UTF-8">
                    <input type="text" name="user_search" maxlength="100" placeholder="Filter users by id" />
                    <input type="text" name="post_search" maxlength="100" placeholder="Filter posts" />
                    <input type="submit" name="search" value="Search">
                </form>

                <br>

                <iframe src="feed.php"></iframe>
            </div>
        </div>

        <!-- footer -->
        <div id="admin_logout">
            <a href="admin_logout.php"><img src="logout.png" width="5%" height="5%"></img></a>
        </div>
    </body>
</html>
