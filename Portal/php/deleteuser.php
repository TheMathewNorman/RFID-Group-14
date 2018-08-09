<?php
    include_once "database.php";
    $database = new Database();

    $table = $_GET['table'];
    $id = $_GET['id'];

    echo "$table<br>$id";

    $redirect = "../page/index.php";

    if ($table == "admin") {
        $database->removeAdmin($id);
        $redirect = "../page/listadmin.php";
    } else if ($table == "member") {
        $database->removeMember($id);
        $redirect = "../page/listmember.php";
    }
    header("Location: $redirect");
?>