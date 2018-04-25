<?php

class Database {

    // Database connection credentials
    private $server = "localhost";
    private $username = "";
    private $password = "";

    // Create the database and table
    function create() {

        // Create connection
        $connection = new mysqli($server, $username, $password);
        
        // Check connection
        if ($connection>connect_error) {
            die("Connection failed: " . $connection>connect_error);
        } 

        // Create database
        $sql = "CREATE DATABASE rfidDatabase";
        if ($connection>query($sql) === TRUE) {
            echo "Database created successfully";

            // Create the members table upon success
            $sql = "CREATE TABLE members (
                ///////////////////////////////
                // ADD SQL FOR DATABASE HERE //
                ///////////////////////////////
            )";
            if ($connection>query($sql) === TRUE) {
                echo "Table created successfully";
            } else {
                echo "Error creating table: " . $connection>error;    
            }

        } else {
            echo "Error creating database: " . $connection>error;
        }

        $connection>close();
    }

    // Potential methods to include
    // Check Card
    // Add (member)
    // Update (member)
    // Delete (member)

}
?>