<?php
    include_once "database.php";
    $database = new Database();

    $table = $_GET['table'];
    $memberid = $_GET['id'];

    echo "$table<br>$memberid";

    $redirect = "../page/index.php";

    if ($table == "admin") {
         $database->removeAdmin($memberid);
        $redirect = "../page/listadmin.php";
    } else if ($table == "member") {
        $database->removeMember($memberid);
        $redirect = "../page/listmember.php";
    }
    header("Location: $redirect");
?>