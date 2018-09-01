<?php
    include './database.php';
    $database = new Database();

    $location = "../page/listpending.php";

    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        
        $database->approveReader($id);
        $location = "../page/updatereader.php?id=$id";
    }

    header("Location: $location");
?>