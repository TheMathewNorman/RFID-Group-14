<?php

    include_once "../php/database.php";
    $database = new Database();

    $location = "";

    if (!isset($_GET['id'])) {
        $location = "memberaccess.php";
    }

    if (count($_POST) > 0) {
        foreach ($_POST as $postItem) {
            $database->addPrivilege($_GET['id'], $postItem);
        }
        //$location = "listaccess.php?id=".$_GET['id'];
    }

    if ($location != "") {
        header("Location: $location");
    }
  
?>
<html>
    <head>
        <title>Add Access</title>
        <link rel="stylesheet" type="text/css" href="../css/global-style.css">
        <link rel="stylesheet" type="text/css" href="../css/header.css">
        <link rel="stylesheet" type="text/css" href="../css/menu.css">
    </head>
    <body>
        <div id="content">
        <?php include "../component/header.php"; ?>
        <?php include "../component/menu.php"; ?>
            
            <form action="" method="POST">
            <table id="list-table">
                <tr>
                    <th>Reader ID</th>
                    <th>Reader Name</th>
                    <th>Add</th>
                </tr>
                <?php
                    $database->listPrivilegeReaders();
                ?>
            </table>
            <div style="text-align:center;margin-top:10px;">
                <input type="Submit" value="Submit">
            </div>
            </form>

        </div>
    </body>
</html>