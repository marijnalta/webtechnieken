<?php
  // Enable https
  include "enable_https.php";
?>
<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" type="text/css" href="style.css"/>
        <link rel="shortcut icon" href="logo.ico" />
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Admin Log In</title>
    </head>
    <body>
        <div class="login-box">
            <img src="person.png" class="avatar">

            <h1>Sign in to Admin Panel</h1>
            <p>Are you ready to be an admin?</p>

            <form id="login" name="login" action="admin_login.php" method="post" accept-charset="UTF-8">
                <div class="loginput">
                    <input type="text" name="username" maxlength="100" placeholder="Username" required />
                    <input type="password" name="password" maxlength="100" placeholder="Password" required />
                </div>

                <input type="submit" name="submit" value="Sign in">
            </form>
        </div>
    </body>
</html>
