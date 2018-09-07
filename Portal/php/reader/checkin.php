<?php
    include_once('../database.php');
    $reader = new Reader();
    
    if (isset($_GET['key']) && isset($_GET['reader'])) {
        $readerid = $_GET['reader'];
        $key = $_GET['key'];
        if ($reader->addLogEntry($readerid,$key, 1)) { 
            die("1");
        }
    }
    die("0");
?>