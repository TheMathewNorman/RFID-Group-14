<?php
    if (!isset($_GET['id'])) {
        header("Location: listmember.php");
    }

    include_once "../php/database.php";
    $database = new Database();

    $userInfo = $database->fetchMemberInfo($_GET['id']);
?>
<html>
    <head>
        <title>Update Member</title>
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
                <tr><td style="text-align:right">First name: </td><td><input type="text" name="firstname"></td></tr>
                <tr><td style="text-align:right">Last name: </td><td><input type="text" name="lastname"></td></tr>
                <tr><td style="text-align:right">Email: </td><td><input type="email" name="email" ></td></tr>
                <tr><td style="text-align:right">Phone: </td><td><input type="text" name="phone"></td></tr>
                <tr><td style="text-align:right">Keycard: </td><td><input type="text" name="keycard"></td></tr>
                <tr><td style="text-align:right">Delete:</td><td><input type="checkbox" name="delete" value="Delete"></td></tr>
                <tr><td colspan="2" style="text-align:right"><input type="submit" value="Update Member"> <input type="reset" value="Clear"></td></tr>
                </table>
            </form>
        </div>
    </body>
</html>
