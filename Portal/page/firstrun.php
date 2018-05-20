<?php
    include_once "../php/database.php";
    $database = new Database();

    // Check if form has been submitted, if it has do the following:
    // - Test connection ($database->testConnection($_POST['server'], $_POST['dbuser'], $_POST['dbpass'], $_POST['dbname']))
    // - Create sqlcreds.php file
    // - Create table ($database->createTables())
    // - Add admin ($database->addAdmin($_POST['fname'], $_POST['lname'], $_POST['email'], $_POST['phone'],  $_POST['password']))
    // - Redirect to login page (header("Location: ../index.php"))

?>
<html>
<head>
    <title>First Run Setup</title>
    <link rel="stylesheet" type="text/css" href="../css/global-style.css">
    <link rel="stylesheet" type="text/css" href="../css/menu.css">
</head>
<body>
    <div id="content">
            <h1>First-run Setup</h1>
            <p>This system requires some information before it can be used.<br>All of the following required before use. This information can be changed later if necessary.</p>
            <table>
            <form action="" method="post">
                <tr><td colspan="2" style="font-weight:bolder;padding-bottom:5px;">Database:</td></tr>
                <tr><td style="text-align:right">Server: </td><td><input type="text" name="server" value="localhost" required></td></tr>
                <tr><td style="text-align:right">Database: </td><td><input type="text" name="dbname" required></td></tr>
                <tr><td style="text-align:right">Username: </td><td><input type="text" name="dbuser" required></td></tr>
                <tr><td style="text-align:right">Password: </td><td><input type="password" name="dbpass" required></td></tr>
                
                <tr><td colspan="2" style="font-weight:bolder;padding:15px 0 5px 0;">Admin account:</td></tr>
                <tr><td style="text-align:right">First name: </td><td><input type="text" name="fname" required></td></tr>
                <tr><td style="text-align:right">Last name: </td><td><input type="text" name="lname" required></td></tr>
                <tr><td style="text-align:right">Email: </td><td><input type="email" name="email" required></td></tr>
                <tr><td style="text-align:right">Phone: </td><td><input type="text" name="phone" required></td></tr>
                <tr><td style="text-align:right">Password: </td><td><input type="password" name="password" required></td></tr>
                <tr><td colspan="2" style="text-align:right"><input type="submit" value="Run Setup"> <input type="reset" value="Clear"></td></tr>
            </form>
            </table>
    </div>
</body>
</html>