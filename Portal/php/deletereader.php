<?php
    include_once "database.php";
    $database = new Database();

    $redirect = "../page/listreaders.php";

    if (isset($_GET['id'])) {
        $id = $_GET['id'];

        $database->removeReader($id);
    }


    header("Location: $redirect");
?>