<?php
    if (!isset($_GET['id'])) {
        header("Location: listmember.php");
    }

    include_once "../php/database.php";
    $database = new Database();

    // Used form with information as placeholder text.
    $userInfo = $database->fetchMemberInfo($_GET['id']);

    if (isset($_POST['delete']) && $_POST['delete'] === "true") {
        header("Location: ../php/deleteuser.php?table=member&id=".$_GET['id']);
    }
    
    if (isset($_POST['firstname']) || isset($_POST['lastname']) || isset($_POST['email']) || isset($_POST['phone']) || isset($_POST['keycard'])) {        
        $database->updateMember($_GET['id'], $_POST['firstname'], $_POST['lastname'], $_POST['email'], $_POST['phone'], $_POST['keycard']);
        header("Location: listmember.php");
    } 
?>
<html>
    <head>
        <title>Update Member</title>
        <link rel="stylesheet" type="text/css" href="../css/global-style.css">
        <link rel="stylesheet" type="text/css" href="../css/header.css">
        <link rel="stylesheet" type="text/css" href="../css/menu.css">
    </head>
    <body>
        <div id="content">
        
        <?php include "../component/header.php"; ?>
        <?php include "../component/menu.php"; ?>
            
            <form action="" method="POST">
                <table class="form-table">
                <tr><td style="text-align:right">First name: </td><td><input type="text" name="firstname" value="<?php echo $userInfo['fname']; ?>"></td></tr>
                <tr><td style="text-align:right">Last name: </td><td><input type="text" name="lastname" value="<?php echo $userInfo['lname']; ?>"></td></tr>
                <tr><td style="text-align:right">Email: </td><td><input type="email" name="email" value="<?php echo $userInfo['email']; ?>"></td></tr>
                <tr><td style="text-align:right">Phone: </td><td><input type="text" name="phone" value="<?php echo $userInfo['phone']; ?>"></td></tr>
                <tr><td style="text-align:right">Keycard: </td><td><input type="text" name="keycard"></td></tr>
                <tr><td style="text-align:right">Delete:</td><td><input type="checkbox" name="delete" value="true"></td></tr>
                <tr><td colspan="2" style="text-align:right"><input type="submit" value="Update Member"> <input type="reset" value="Clear"></td></tr>
                </table>
            </form>
        </div>
    </body>
</html>
