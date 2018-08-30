<?php
    include_once('../database.php');
    $database = new Database();

    include_once('../validate.php');
    $validate = new Validate();
    
    if (isset($_GET['key']) && isset($_GET['reader'])) {
        $reader = $validate->sanitizeString($_GET['reader']);
        $key = $validate->sanitizeString($_GET['key']);
        if ($database->addLogEntry($readerid,$key, 1)) { 
            die("1");
        }
    }
?>