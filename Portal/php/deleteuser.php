<?php
    include_once "database.php";
    $database = new Database();

    include_once "sessions.php";

    $redirect = "../page/index.php";

    if (!isset($_SESSION['userid'])) {
        $redirect = "../index.php";
    } else {
        if (isset($_GET['table']) && isset($_GET['id'])) {
            $table = $_GET['table'];
            $id = $_GET['id'];

            if ($table == "admin") {
                $database->removeAdmin($id);
                $redirect = "../page/listadmin.php";
            } else if ($table == "member") {
                $database->removeMember($id);
                $redirect = "../page/listmember.php";
            }
        } else {
            $redirect = "../page/index.php";
        }
    }

    header("Location: $redirect");
?>