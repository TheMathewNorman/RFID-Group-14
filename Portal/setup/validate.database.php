<?php
    $dbserver = $_POST['server'];
    $dbname = $_POST['dbserver'];
    $dbuser = $_POST['dbuser'];
    $dbpass = $_POST['dbpass'];

    include_once "../php/database.php";
    $database = new Database();

    if (!$database->testConnection($dbserver, $dbuser, $dbpass, $dbname)) {
        
    }
?>