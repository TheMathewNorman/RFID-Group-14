<?php

?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8" />
<title>Access Control Management System</title>

<link rel="stylesheet" href="../css/setup.css">
</head>
<body>
    <div id="setup-container">
        <div id="setup-heading">
            First Run Setup
        </div>
        <div id="setup-text">
            Some information is required before being able to use this system.
        </div>
        
        <!-- Database setup form -->
        <div id="setup-form">
        <form action="" method="POST">
            <div class="form-field">
                <div class="input-icon"><i class="fas fa-user fa-lg"></i></div>
                <div class="input-box"><input type="text" name="server" placeholder="Server"></div>
            </div>
            <div class="form-field">
                <div class="input-icon"><i class="fas fa-user fa-lg"></i></div>
                <div class="input-box"><input type="text" name="dbuser" placeholder="Username"></div>
            </div>
            <div class="form-field">
                <div class="input-icon"><i class="fas fa-lock fa-lg"></i></div>
                <div class="input-box"><input type="password" name="dbpass" placeholder="Password"></div>
            </div>
            <input type="submit" value="Submit">
            </form>
        </div>

    <!-- Admin setup form 
    <div id="setup-form">
        <form action="index.php?page=2" method="POST">
            <div class="form-field">
                <div class="input-icon"><i class="fas fa-user fa-lg"></i></div>
                <div class="input-box"><input type="text" name="fname" placeholder="Server"></div>
            </div>
            <div class="form-field">
                <div class="input-icon"><i class="fas fa-user fa-lg"></i></div>
                <div class="input-box"><input type="text" name="lname" placeholder="Username"></div>
            </div>
            <div class="form-field">
                <div class="input-icon"><i class="fas fa-lock fa-lg"></i></div>
                <div class="input-box"><input type="password" name="email" placeholder="Password"></div>
            </div>
            <input type="submit" value="Submit">
        </form>
    </div>
-->
    </div>
</body>
</html>
