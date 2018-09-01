<?php
    include_once "database.php";
    $database = new Database();

    include_once "sessions.php";

    $redirect = "../page/memberaccess.php";

    if (!isset($_SESSION['userid'])) {
        $redirect = "../index.php";
    } else {
        if (isset($_GET['id']) && isset($_GET['member'])) {
            $id = $_GET['id'];
            $memberid = $_GET['member'];

            $database->removePrivilege($id);
            $redirect = "../page/listaccess.php?id=$memberid";
        }
    }

    header("Location: $redirect");
?>