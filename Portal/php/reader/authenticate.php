<?php
    include_once('../database.php');
    $database = new Database();

    $return = "0";

    // Readers will validate cards here.
    if (isset($_GET['reader']) && isset($_GET['key'])) {
        $readerid = $_GET['reader'];
        $key = $_GET['key'];
        
        if ($database->checkPrivilege($readerid,$key)) {
            echo "Permission Granted";
            if ($database->addLogEntry($readerid,$key)) { 
                $return = "1";
            } else {
                echo "Log entry failed";
            }
        } else {
            echo "Permission Denied";
        }
        
    }

    die($return);
?>
