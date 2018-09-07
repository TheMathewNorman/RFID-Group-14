<?php
    include_once('../database.php');
    $reader = new Reader();

    $return = "0";

    // Readers will validate cards here.
    if (isset($_GET['reader']) && isset($_GET['key'])) {
        $readerid = $_GET['reader'];
        $key = $_GET['key'];
        
        if ($reader->checkPrivilege($readerid,$key)) {
            if ($reader->addLogEntry($readerid,$key)) { 
                $return = "1";
            }
        }   
    }

    // 0 is denied, 1 is granted access
    die($return);
?>
