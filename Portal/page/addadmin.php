<?php
    // Add admin code goes here
?>
<html>
    <head>
        <title>Add Admin</title>
        <link rel="stylesheet" type="text/css" href="../css/global-style.css">
        <link rel="stylesheet" type="text/css" href="../css/menu.css">
    </head>
    <body bgcolor="#f5f5f5">
        <div id="content">
        

        <?php include "menu.php"; ?>
            
            <form action="" method="POST">
                <table>
                    <tr><td style="text-align:right">First name: </td><td><input type="text" name="firstname" required> *</td></tr>
                    <tr><td style="text-align:right">Last name: </td><td><input type="text" name="lastname"  required> *</td></tr>
                    <tr><td style="text-align:right">Email: </td><td><input type="email" name="email" ></td></tr>
                    <tr><td style="text-align:right">Phone: </td><td><input type="text" name="phone"></td></tr>
                    <tr><td style="text-align:right">Password: </td><td><input type="password" name="keycard" required> *</td></tr>
                    <tr><td colspan="2" style="text-align:right"><input type="submit" value="Add Member"> <input type="reset" value="Clear"></td></tr>
                    <tr><td colspan="2" style="text-align:right;font-size: 1em;">* Required</td></tr>
                </table>
            </form>

        </div>
    </body>
</html>