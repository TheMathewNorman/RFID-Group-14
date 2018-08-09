<?php
    // Check login
    session_start();

    // First-run redirect
    $location = "";
    if (!file_exists("./php/sqlcreds.php")) {
        $location = "./component/firstrun.php";
    }
    
    include_once "./php/database.php";
    include_once "./php/sessions.php";
    $database = new Database();
    $sessions = new Sessions();

    if (isset($_POST['email']) && isset($_POST['password'])) {
        $loginResponse = $database->loginAdmin($_POST['email'], $_POST['password']);

        if ($loginResponse[0]) {
            $error = $loginResponse[0] . ": " . $loginResponse[1] . "<br> User: ".$loginResponse['id']."<br>Name: ".$loginResponse['fname'];
        } else {
            $error = $loginResponse[0] . ": " . $loginResponse[1];
        }
        
    }


    if ($location !== "") {
        header("Location: $location");
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
        <div id="login-error"><?php echo $error; ?></div>
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
            </form>
        </div>

    </div>
</body>
</html>
