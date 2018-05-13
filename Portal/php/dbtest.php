<?php    
    include "database.php";

    // Create the tables
    $database = new Database;
    $database->createTables();

    echo "Tables created successfully.<br>";

    // Insert test member
    $database->addMember("Mathew Norman", "someone@example.com", "0400000000", "0192889112412345643");
    echo "Member add test 1.<br>";
    $database->addMember("Mathew Norman", "someoneelse@example.com", "0400000000", "asq2889112412345643");
    echo "Member add test 2.<br>";

    // Update test member
    $database->updateMember('1', "Mathew Norman", "coolemail@mat.com", "", "");
    echo "Member update test.<br>";

    // Delete test member
    $database->deleteMember('2');
    echo "Member delete test.<br>";

?>