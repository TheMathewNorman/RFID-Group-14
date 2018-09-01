<?php
    include './database.php';
    $database = new Database();

    include 'sessions.php';

    $location = "../page/listpending.php";

    if (!isset($_SESSION['userid'])) {
        $location = "../index.php";
    } else {

        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            
            $database->approveReader($id);
            $location = "../page/updatereader.php?id=$id";
        }
    }       

    header("Location: $location");
?>