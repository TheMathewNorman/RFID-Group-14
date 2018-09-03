<?php
    if (!isset($_GET['id'])) {
        header("Location: listadmin.php");
    }

    include_once "../php/database.php";
    $database = new Database();

    // Used form with information as placeholder text.
    $userInfo = $database->fetchAdminInfo($_GET['id']);

    if (isset($_POST['delete']) && $_POST['delete'] === "true") {
        header("Location: ../php/deleteuser.php?table=admin&id=".$_GET['id']);
    }
    
    if (isset($_POST['firstname']) || isset($_POST['lastname']) || isset($_POST['email']) || isset($_POST['phone']) || isset($_POST['password'])) {        
        $database->updateAdmin($_GET['id'], $_POST['firstname'], $_POST['lastname'], $_POST['email'], $_POST['phone'], $_POST['password']);
        header("Location: listadmin.php");
    } 
?>
<html>
    <head>
        <title>Update Admin</title>
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
                <tr><td style="text-align:right">First name: </td><td><input type="text" name="firstname" placeholder="<?php echo $userInfo['fname']; ?>"></td></tr>
                <tr><td style="text-align:right">Last name: </td><td><input type="text" name="lastname" placeholder="<?php echo $userInfo['lname']; ?>"></td></tr>
                <tr><td style="text-align:right">Email: </td><td><input type="email" name="email"  placeholder="<?php echo $userInfo['email']; ?>"></td></tr>
                <tr><td style="text-align:right">Phone: </td><td><input type="text" name="phone" placeholder="<?php echo $userInfo['phone']; ?>"></td></tr>
                <tr><td style="text-align:right">Password: </td><td><input type="password" name="password"></td></tr>
                <?php
                if (($_SESSION['userid'] != $_GET['id']) && ($_GET['id'] != '1'))
                ?>
                <tr><td style="text-align:right">Delete:</td><td><input type="checkbox" name="delete" value="true"></td></tr>
                <?php
                }
                ?>
                <tr><td colspan="2" style="text-align:right"><input type="submit" value="Update Member"> <input type="reset" value="Clear"></td></tr>
                </table>
            </form>
        </div>
    </body>
</html>
