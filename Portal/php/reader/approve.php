<?php
    include_once('../database.php');
    $database = new Database();

    $return = '0';

    if (isset($_GET['reader'])) {
        $readerid = $_GET['reader'];
        // Check if reader is approved
        if ($database->checkReaderApproved($readerid)) {
            $return = '1';
        }
    }

    die($return);
?>
