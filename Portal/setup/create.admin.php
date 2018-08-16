<?php
    // Inlcude the database class functionality.
    include_once '../php/database.php';
    $database = new Database();

    // Used to relay any error messages to the user.
    $error = '';

    // // Check if post variables are set
    // if () {
        // // Create admin and login
    // }
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8" />
<title>Access Control Management System</title>

<link rel="stylesheet" href="../css/setup.css">
</head>
<body>
    <div id="setup-container" style="height:325px">
        <div id="setup-heading">
            First Run Setup
        </div>
        <div id="setup-subheading">
            <?php
                if ($error !== '') {
                    echo $error;
                } else {
            ?>
            Please enter the details for the primary admin account.
            <?php
                }
            ?>
        </div>

        <!-- Database setup form -->
        <div id="setup-form">
        <form action="" method="POST">
            <div class="form-field">
                <div class="input-icon"><i class="fas fa-user fa-lg"></i></div>
                <div class="input-box"><input type="text" name="email" placeholder="Email"></div>
            </div>
            <div class="form-field">
                <div class="input-icon"><i class="fas fa-user fa-lg"></i></div>
                <div class="input-box"><input type="text" name="phone" placeholder="Phone Number"></div>
            </div>
            <div class="form-field">
                <div class="input-icon"><i class="fas fa-user fa-lg"></i></div>
                <div class="input-box"><input type="password" name="password" placeholder="Password"></div>
            </div>
            <input type="submit" value="Create admin">
            </form>
        </div>
    </div>
</body>
</html>