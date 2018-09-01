<?php
include 'sqlcreds.php';

class Database {

    //// GENERAL FUNCTIONALITY //// 
    // Create the tables.
    function createTables() {

        // Create connection
        $connection = new mysqli($GLOBALS['server'], $GLOBALS['user'], $GLOBALS['pass'], $GLOBALS['dbname']);
        
        // Check connection
        if ($connection->connect_error) {
            die("Connection failed<br>$connection->connect_error");
        }

        // Create the members table upon success
        $sql = "CREATE TABLE admins (
            id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
            firstname VARCHAR(30) NOT NULL,
            lastname VARCHAR(30) NOT NULL,
            email VARCHAR(50) NOT NULL,
            phone VARCHAR(10),
            passhash VARCHAR(128) NOT NULL
        ); CREATE TABLE members (
            id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
            firstname VARCHAR(30) NOT NULL,
            lastname VARCHAR(30) NOT NULL,
            email VARCHAR(50),
            phone VARCHAR(10),
            cardkey VARCHAR(128) NOT NULL
        ); CREATE TABLE priviledge (
            id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
            member_id INT(6) NOT NULL,
            reader_id INT(6),
            reader_group INT(6)
        ); CREATE TABLE readers (
            id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
            reader_name VARCHAR(30) NOT NULL,
            reader_group INT(6) NOT NULL,
            signature VARCHAR(60) NOT NULL,
            approved BOOLEAN NOT NULL
        ); CREATE TABLE logs (
            id INT(10) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
            member_id INT(6) NOT NULL,
            reader_id INT(6) NOT NULL,
            access_date TIMESTAMP NOT NULL,
            check_in BOOL DEFAULT false
        )";
            
        if (mysqli_multi_query($connection, $sql) === FALSE) {
            die("Error creating table: " . $connection->error);
        }

        // Close the connection
        $connection->close();
    }

    // Test database connection.
    function testConnection($server="", $dbuser="", $dbpass="", $dbname="") {
        if ($server=="") {
            $server = $GLOBALS['server'];
        }
        if ($dbuser=="") {
            $dbuser = $GLOBALS['user'];
        }
        if ($dbpass=="") {
            $dbpass = $GLOBALS['pass'];
        }
        if ($dbname=="") {
            $dbname = $GLOBALS['dbname'];
        }
        
        // Create connection
        $connection = new mysqli($server, $dbuser, $dbpass, $dbname);
                
        // Check connection and return status
        if ($connection->connect_error) {
            $connection->close();
            return False;
        } else {
            $connection->close();
            return True;
        }
    }

}

include_once 'database/admins.php';
include_once 'database/members.php';
include_once 'database/logs.php';
include_once 'database/privilege.php';
include_once 'database/readers.php';


?>
