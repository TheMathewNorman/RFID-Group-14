<?php
    include_once('../database.php');
    $database = new Database();
    
    if (isset($_GET['key']) && isset($_GET['reader'])) {
        $readerid = $_GET['reader'];
        $key = $_GET['key'];
        if ($database->addLogEntry($readerid,$key, 1)) { 
            die("1");
        }
    }
    die("0");
?>