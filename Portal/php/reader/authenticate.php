<?php
    include_once('../database.php');
    $database = new Database();

    $return = "0";

    // Readers will validate cards here.
    if (isset($_GET['reader']) && isset($_GET['key'])) {
        $readerid = $_GET['reader'];
        $key = $_GET['key'];
        
        if ($database->checkPrivilege($readerid,$key)) {
            if ($database->addLogEntry($readerid,$key)) { 
                $return = "1";
            }
        }
        
    }

    // 0 is denied, 1 is granted access
    die($return);
?>
