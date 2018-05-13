<?php    
    include "sqlcreds.php";

    echo $GLOBALS['server'];
    echo $GLOBALS['user'];
    echo $GLOBALS['pass'];
    echo $GLOBALS['rfidDB'];
    // Create connection
    $connection = new mysqli($GLOBALS['server'], $GLOBALS['user'], $GLOBALS['pass'], $GLOBALS['rfidDB']);
    
    // Check connection
    if ($connection->connect_error) {
        die("Connection failed: " . $connection->connect_error);
    } 

    // Create the members table upon success
    $sql = "CREATE TABLE staff (
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
        firstname VARCHAR(30) NOT NULL,
        lastname VARCHAR(30) NOT NULL,
        email VARCHAR(50)
    )";
        
    if ($connection>query($sql) !== TRUE) {
        die("Error creating table: " . $connection->error);
    }

    $connection->close();
?>