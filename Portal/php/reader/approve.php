<?php
    include_once('../database.php');
    $database = new Database();

    $return = '0';

    if (isset($_GET['reader'])) {
        $readerid = $_GET['reader'];

        if ($database->approveReader($readerid)) {
            $return = '1';
        }
    }

    die($return);
?>
