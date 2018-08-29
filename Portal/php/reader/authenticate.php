<?php
    include_once '../reader.php';
    $reader = new Reader();

    if (isset($_GET['reader']) && isset($_GET['key'])) {   
        if ($database->checkPrivilege($reader, $key)) {
            die('1');
        } else {
            die('0');
        };
    } else {
        die('0');
    }



    // Readers will validate cards here.
    if (isset($_GET['reader']) && isset($_GET['key'])) {
        $reader = $_GET['reader'];
        $key = $_GET['key'];
        
        if ($reader == 10 && $key == 9988) {
            echo 1;
        } else {
            echo 0;
        }
    } else {
        echo 0;
    }
?>
