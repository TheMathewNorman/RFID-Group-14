<?php
    include_once "../php/database.php";
    $database = new Database();

    if (isset($_POST['firstname']) && isset($_POST['lastname']) && isset($_POST['keycard'])) {
        $fname = $_POST['firstname'];
        $lname = $_POST['lastname'];
        $email = "";
        $phone = "";
        $keycard = $_POST['keycard'];

        if (isset($_POST['email'])) {
            $email = $_POST['email'];
        }
        if (isset($_POST['phone'])) {
            $phone = $_POST['phone'];
        }

        $database->addMember($fname,$lname,$email,$phone,$keycard);
        header("Location: listmember.php");
    }
?>
<html>
    <head>
        <title>Add Member</title>
        <link rel="stylesheet" type="text/css" href="../css/global-style.css">
        <link rel="stylesheet" type="text/css" href="../css/header.css">
        <link rel="stylesheet" type="text/css" href="../css/menu.css">
    </head>
    <body bgcolor="#f5f5f5">
        <div id="content">
        <?php include "../component/header.php"; ?>
        <?php include "../component/menu.php"; ?>  
    
            <form action="" method="POST">
                <table class="form-table">
                    <tr><td style="text-align:right">First name: </td><td><input type="text" name="firstname" required> *</td></tr>
                    <tr><td style="text-align:right">Last name: </td><td><input type="text" name="lastname"  required> *</td></tr>
                    <tr><td style="text-align:right">Email: </td><td><input type="email" name="email" ></td></tr>
                    <tr><td style="text-align:right">Phone: </td><td><input type="text" name="phone"></td></tr>
                    <tr><td style="text-align:right">Keycard: </td><td><input type="text" name="keycard" required> *</td></tr>
                    <tr><td colspan="2" style="text-align:right"><input type="submit" value="Add Member"> <input type="reset" value="Clear"></td></tr>
                    <tr><td colspan="2" style="text-align:right;font-size: 1em;">* Required</td></tr>
                </table>
            </form>
        
    </div>
    </body>
</html>
