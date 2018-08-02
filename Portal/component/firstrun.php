<?php
    //include_once "../php/database.php";
    //$database = new Database();
    
    // Handle the form submission
    // if (isset($_POST['server']) && isset($_POST['dbname']) && isset($_POST['dbuser']) && isset($_POST['dbpass'])) {
    //     // Test connection
    //     if ($database->testConnection($_POST['server'], $_POST['dbuser'], $_POST['dbpass'], $_POST['dbname'])) {
    //         // Create SQL creds file.
    //         $fileContents = "<?php\n";
    //         $fileContents .= '$GLOBALS[\'server\'] = "localhost";\n';
    //         $fileContents .= '$GLOBALS[\'user\'] = "rfidmyadmin";\n';
    //         $fileContents .= '$GLOBALS[\'pass\'] = "H#5D5gRGzfrgC6eMYh";\n';
    //         $fileContents .= '$GLOBALS[\'dbname\'] = "rfidDB";\n';
    //         $fileContents .= "?>";
    //         $sqlcredsfile = fopen("../php/sqlcreds.php","w");
    //         fwrite($sqlcredsfile, $fileContents);
    //         fclose($sqlcredsfile);

    //         // Assign new instance of Database.
    //         $database = new Database();

    //         // Create tables
    //         $database->createTables();

    //         // Add admin account
    //         $database->addAdmin($_POST['fname'],$_POST['fname'],$_POST['email'],$_POST['phone'],$_POST['pass']);

    //         // Redirect to login page
    //         header("Location: ../index.php");
    //     } else {
    //         echo "ERROR";
    //     }
    // }
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
            
            Database:
            <form action="" method="post">
            <table>
                <tr><td style="text-align:right">Server: </td><td><input type="text" name="server" value="localhost" required></td></tr>
                <tr><td style="text-align:right">Database: </td><td><input type="text" name="dbname" required></td></tr>
                <tr><td style="text-align:right">Username: </td><td><input type="text" name="dbuser" required></td></tr>
                <tr><td style="text-align:right">Password: </td><td><input type="password" name="dbpass" required></td></tr>
            </table>
            Admin:
            <table>            
                <tr><td style="text-align:right">First name: </td><td><input type="text" name="fname" required></td></tr>
                <tr><td style="text-align:right">Last name: </td><td><input type="text" name="lname" required></td></tr>
                <tr><td style="text-align:right">Email: </td><td><input type="email" name="email" required></td></tr>
                <tr><td style="text-align:right">Phone: </td><td><input type="text" name="phone" required></td></tr>
                <tr><td style="text-align:right">Password: </td><td><input type="password" name="password" required></td></tr>
                <tr><td colspan="2" style="text-align:right"><input type="submit" value="Run Setup"> <input type="reset" value="Clear"></td></tr>
            </table>
            </form>
    </div>
</body>
</html>