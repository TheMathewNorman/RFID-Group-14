<?php
    // Include instances of required classes
    require_once "./php/sessions.php";
    $sessions = new Sessions();
    
    // Store any message
    $message = "";

    // Redirect locaton
    $location = "";

    //// First-run check
    // Check config file exists
    $database;
    if (!file_exists($_SERVER['DOCUMENT_ROOT'].'/config.php')) {
        $location = "./setup/index.php?issue=noConfig";
    } else {
        // Test database connection
        require_once "./php/database.php";
        $database = new Database();
        if (!$database->connsuccess) {
            $location = "./setup/index.php?issue=database";
        } else {
            // Check all tables required exist
            if (!$database->checkTablesExist()) {
                $location = "./setup/index.php?issue=tables";
            }
        }
    }

    //// Set page message
    if (isset($_GET['message'])) {
        if ($_GET['message'] = "nologin") {
            $message = "You must be logged in to view that page.";
        }
        if ($_GET['message'] = "logout") {
            $message = "You have successfully been logged out.";
        }
    }

    // Attempt to login when credientials have been entered.
    if (empty($location)) {
        if (isset($_POST['email']) && isset($_POST['password'])) {
            
            try {
                $loginResponse = $database->loginAdmin($_POST['email'], $_POST['password']);

                $sessions->startSession($loginResponse['id'], $loginResponse['fname']);
                $location = 'page/index.php';
            } catch (Exception $e) {
                $message = 'Error: Email or password was incorrect.';
            }
        }
    }

    // Redirect if already logged in
    if (isset($_SESSION['userid'])) {
        $location = "./page/index.php";
    }

    if (!empty($location)) {
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
        <div id="login-error"><?php echo $message; ?></div>
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
