<?php
    include_once "database.php";
    $database = new Database();

    include_once "sessions.php";

    $redirect = "../page/listreaders.php";

    if (!isset($_SESSION['userid'])) {
        $redirect = "../index.php";
    } else {
        if (isset($_GET['id'])) {
            $id = $_GET['id'];

            $database->removeReader($id);
        }
    }


    header("Location: $redirect");
?>