<?php    
    include "database.php";

    // Create the tables
    $database = new Database;
    $database->createTables();

    // Insert test member
    $database->addMember("Mathew Norman", "someone@example.com", "0400000000", "0192889112412345643");

?>