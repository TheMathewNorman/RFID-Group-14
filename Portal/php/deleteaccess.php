<?php
    include_once "database.php";
    $database = new Database();

    include_once "sessions.php";

    $redirect = "../page/memberaccess.php";

    if (!isset($_SESSION['userid'])) {
        $redirect = "../index.php";
    } else {
        if (isset($_GET['id'])) {
            $id = $_GET['id'];

            $database->removePrivilege($id);
            $redirect = "../page/listaccess.php?id=$id";
        }
    }

    header("Location: $redirect");
?>