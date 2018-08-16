<?php
    // Inlcude the database class functionality.
    include_once '../php/database.php';
    $database = new Database();

    // Used to relay any error messages to the user.
    $error = '';

    if (isset($_POST['dbname']) && isset($_POST['dbuser']) && isset($_POST['dbpass'])) {
        

        if ($database->testConnection($_POST['server'], $_POST['dbuser'], $_POST['dbpass'], $_POST['dbname'])) {
            header('Location: ./create.admin.php');
        } else {
            $error = "<b>Failed to connect to database. Please check credentials and try again.</b>";
        }
    }
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
        <div id="setup-subheading">
            <?php
                if ($error !== '') {
                    echo $error;
                } else {
            ?>
            Some information is required before being able to use this system.
            <?php
                }
            ?>
        </div>
        
        <!-- Database setup form -->
        <div id="setup-form">
        <form action="" method="POST">
            <div class="form-field">
                <div class="input-icon"><i class="fas fa-user fa-lg"></i></div>
                <div class="input-box"><input type="text" name="server" value="localhost"></div>
            </div>
            <div class="form-field">
                <div class="input-icon"><i class="fas fa-user fa-lg"></i></div>
                <div class="input-box"><input type="text" name="dbname" placeholder="Database name"></div>
            </div>
            <div class="form-field">
                <div class="input-icon"><i class="fas fa-user fa-lg"></i></div>
                <div class="input-box"><input type="text" name="dbuser" placeholder="Username"></div>
            </div>
            <div class="form-field">
                <div class="input-icon"><i class="fas fa-lock fa-lg"></i></div>
                <div class="input-box"><input type="password" name="dbpass" placeholder="Password"></div>
            </div>
            <input type="submit" value="Next">
            </form>
        </div>
    </div>
</body>
</html>
