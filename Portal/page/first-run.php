<?php
    $setup = "";
    if (isset($_GET['setup'])) {
        $setup = $_GET['setup'];    
    }
?>
<html>
<head>
    <title>First Run Setup</title>
    <link rel="stylesheet" type="text/css" href="../css/global-style.css">
    <link rel="stylesheet" type="text/css" href="../css/menu.css">
</head>
<body>
    <div id="content">
            <table>
            <form action="" method="post">
                <tr><td colspan="2">Database:</td></tr>
                <tr><td style="text-align:right">Server:</td><td><input type="text" name="server" value="localhost"></td></tr>
                <tr><td style="text-align:right">Database name:</td><td><input type="text" name="dbname"></td></tr>
                <tr><td style="text-align:right">Database user:</td><td><input type="text" name="dbuser"></td></tr>
                <tr><td style="text-align:right">Database pass:</td><td><input type="password" name="dbpass"></td></tr>
                <tr><td colspan="2">Admin account:</td></tr>
                <tr><td style="text-align:right">First name:</td><td><input type="text" name="fname"></td></tr>
                <tr><td style="text-align:right">Last name:</td><td><input type="text" name="lname"></td></tr>
                <tr><td style="text-align:right">Email:</td><td><input type="email" name="email"></td></tr>
                <tr><td style="text-align:right">Phone:</td><td><input type="text" name="phone"></td></tr>
                <tr><td style="text-align:right">Password:</td><td><input type="password" name="password"></td></tr>
                <tr><td colspan="2" style="text-align:right"><input type="submit" value="Run Setup"> <input type="reset" value="Clear"></td></tr>
                <tr><td colspan="2" style="text-align:right;font-size: 1em;">* Required</td></tr>
            </form>
            </table>


<!-- This is the form to be filled out when the portal is first opened which will set up the database -->

        <!-- Include a form asking for database credentials -->
        <!-- Include a form asking for setting up the initial admin account -->

        This is some dummy text.
    </div>
</body>
</html>