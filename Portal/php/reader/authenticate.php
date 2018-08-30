<?php

include_once('../database.php');
$database = new Database();

// Readers will validate cards here.
if (isset($_GET['reader']) && isset($_GET['key'])) {
    $readerid = $_GET['reader'];
    $key = $_GET['key'];
    
    if ($database->checkPrivilege($readerid,$key)) {
        die("1");
    } else {
        die("0");
    }
    
} else {
    die("0");
}
?>
