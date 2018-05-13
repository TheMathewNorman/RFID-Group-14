<?php    
    include "sqlcreds.php";

    // Create connection
    $connection = new mysqli($GLOBALS['server'], $GLOBALS['user'], $GLOBALS['pass'], $GLOBALS['dbname']);
    
    // Check connection
    if ($connection->connect_error) {
        die("Connection failed: " . $connection->connect_error);
    }

    // Create the members table upon success
    $sql = "CREATE TABLE admin (
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
        fullname VARCHAR(30) NOT NULL,
        email VARCHAR(50),
        phone VARCHAR(10),
        passhash VARCHAR(128) NOT NULL
    ); CREATE TABLE member (
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
        fullname VARCHAR(30) NOT NULL,
        email VARCHAR(50),
        phone VARCHAR(10),
        cardkey VARCHAR(128) NOT NULL
    ); CREATE TABLE priviledge (
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
        member_id INT(6) NOT NULL,
        reader_id INT(6),
        reader_group INT(6)
    ); CREATE TABLE reader (
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
        reader_name VARCHAR(30) NOT NULL,
        reader_group INT(6) NOT NULL
    ); CREATE TABLE logs (
        id INT(10) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
        member_id INT(6) NOT NULL,
        reader_id INT(6) NOT NULL,
        access_date TIMESTAMP NOT NULL
    )";
        
    if (mysqli_multi_query($connection, $sql) === FASLE) {
        die("Error creating table: " . $connection->error);
    } else {
        echo "Creation success";
    }

    $connection->close();
?>