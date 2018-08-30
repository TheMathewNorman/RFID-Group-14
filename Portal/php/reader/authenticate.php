<?php
<<<<<<< HEAD
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
=======

include_once('../database.php');
$database = new Database();

// Readers will validate cards here.
if (isset($_GET['reader']) && isset($_GET['key'])) {
    $readerid = $_GET['reader'];
    $key = $_GET['key'];
    
    if ($database->checkPrivilege($readerid,$key)) {
        die("1");
>>>>>>> ec55f463f24a9c0cc3ef23ca02997303a486f7ea
    } else {
        die("0");
    }
<<<<<<< HEAD
=======
    
} else {
    die("0");
}
>>>>>>> ec55f463f24a9c0cc3ef23ca02997303a486f7ea
?>
