<?php

?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8" />
<title>Access Control Management System</title>

<link rel="stylesheet" href="../css/login.css">
</head>
<body>
    <div id="login-container">
        <div id="login-heading">
            First Run Setup
        </div>
        <div id="login-form">
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

    </div>
</body>
</html>
