<?php
    // First run redirect
    $location = "";
    if (!file_exists("./php/sqlcreds.php")) {
        $location = "./component/firstrun.php";
    }
    header("Location: $location");


    // Check login
    session_start();
    // List members code goes here
    include_once "./php/database.php";
    $database = new Database();
    if (isset($_POST['email']) && isset($_POST['password'])) {
        $error = $database->loginAdmin($_POST['email'], $_POST['password']);
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
                    <div class="input-box"><input type="text" name="email" placeholder="Email"></div>
                </div>
                <div class="form-field">
                    <div class="input-icon"><i class="fas fa-lock fa-lg"></i></div>
                    <div class="input-box"><input type="password" name="password" placeholder="Password"></div>
                </div>
                <input type="submit" value="Login">
                <span id="login-error"><?php echo $error; ?></span>
            </form>
        </div>

    </div>
</body>
</html>
