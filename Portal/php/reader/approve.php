<?php
    include_once('../database.php');
    $reader = new Reader();

    $return = '0';

    if (isset($_GET['reader'])) {
        $readerid = $_GET['reader'];

        if ($reader->checkReaderApproved($readerid)) {
            $return = '1';
        }

    }

    die($return);
?>
