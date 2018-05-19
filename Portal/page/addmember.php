<?php
    // Add members code goes here
?>
<html>
    <head>
        <title>Add Member</title>
        <link rel="stylesheet" type="text/css" href="../css/global-style.css">
        <link rel="stylesheet" type="text/css" href="../css/menu.css">
    </head>
    <body bgcolor="#f5f5f5">
        <div id="content">
        

        <?php include "menu.php"; ?>
        <!--<table align="center" cellspacing="30">
            <tr>
                <th><a href="Home.html">Home</a></th>
                <th><a href="ManageUsers.html">ManageUsers</a></th>
                <th><a href="ViewLogs.html">ViewLogs</a></th>
                <th><a href="setting.html">setting</a></th>
                <th></th>
            </tr>
        </table>-->
        
        
            <!--    <img src="addmember.png" alt= "addmember logo" width="100"height="100"> -->
            
            
            <form action="" method="POST">
                <table>
                <tr><td style="text-align:right">First name: </td><td><input type="text" name="firstname" required> *</td></tr>
                <tr><td style="text-align:right">Last name: </td><td><input type="text" name="lastname"  required> *</td></tr>
                <tr><td style="text-align:right">Email: </td><td><input type="email" name="email" ></td></tr>
                <tr><td style="text-align:right">Phone: </td><td><input type="text" name="phone"></td></tr>
                <tr><td style="text-align:right">Keycard: </td><td><input type="text" name="keycard" required> *</td></tr>
                <tr><td colspan="2" style="text-align:right"><input type="submit" value="Add Member"> <input type="reset" value="Clear"></td></tr>
                <tr><td colspan="2" style="text-align:right;font-size: 1em;">* Required</td></tr>
            </form>
        </div>
    </body>
</html>
