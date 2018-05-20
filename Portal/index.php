<?php
    if (!file_exists("./php/sqlcreds.php")) {
        header("Location: ./page/first-run.php");
    }
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8" />
<title>Access Control Management System</title>

<link rel="stylesheet" href="css/login.css">
</head>
<body>
    <div id="login-container">
        <div id="login-heading">
            RFID Access Control
        </div>
        <div id="login-form">
            <form action="" method="POST">
                <div class="form-field">
                    <div class="input-icon"><i class="fas fa-user fa-lg"></i></div>
                    <div class="input-box"><input type="text" name="username" placeholder="Username"></div>
                </div>
                <div class="form-field">
                    <div class="input-icon"><i class="fas fa-lock fa-lg"></i></div>
                    <div class="input-box"><input type="password" name="password" placeholder="Password"></div>
                </div>
                <input type="submit" value="Login">
            </form>
        </div>

    </div>
</body>
</html>