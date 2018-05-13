<?php    
    include "database.php";

    // Create the tables
    $database = new Database;
    $database->createTables();

    // Insert test member
    $database->addMember("Mathew Norman", "someone@example.com", "0400000000", "0192889112412345643");
    $database->addMember("Mathew Norman", "someoneelse@example.com", "0400000000", "asq2889112412345643");

    // Update test member
    $database->updateMember('0', "Mathew Norman", "coolemail@mat.com", "", "");

    // Delete test member
    $database->deleteMember('1');

?>