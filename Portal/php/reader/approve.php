<?php
    include_once('../database.php');
    include_once('../validate.php');
    $database = new Database();
    $validate = new Validate();

    $return = '0';

    if (isset($_GET['reader'])) {
        $readerid = $_GET['reader'];

        if ($validate->sanitizeString($readerid) != "") {
            // Check if reader is approved
            if ($database->checkReaderApproved($readerid)) {
                $return = '1';
            }
        }
    }

    die($return);
?>
