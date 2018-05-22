<?php
    include_once "database.php";
    $database = new Database();

    $table = $_GET['table'];
    $userid = $_GET['id'];

    echo "$table<br>$userid";

    $redirect = "../page/index.php";

    if ($table == "admin") {
         $database->removeAdmin($userid);
        $redirect = "../page/listadmin.php";
    } else if ($table == "member") {
        $database->removeMember($userid);
        $redirect = "../page/listmember.php";
    }
    header("Location: $redirect");
?>