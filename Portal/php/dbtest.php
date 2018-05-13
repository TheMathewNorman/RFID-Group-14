<?php    
    include "database.php";

    // Create the tables
    $database = new Database;
    $database->createTables();

    echo "Tables created successfully.<br>";

    // List members
    $database->listMembers();
    echo "Member list test.<br>";

    // Insert test member
    $database->addMember("Mathew Norman", "someone@example.com", "0400000000", "0192889112412345643");
    echo "Member add test 1.<br>";
    $database->addMember("Mathew Norman", "someoneelse@example.com", "0400000000", "asq2889112412345643");
    echo "Member add test 2.<br>";

    $database->searchMembers("Ma");
    $database->searchMembers("abcd");    

    // List members
    $database->listMembers();
    echo "Member list test.<br>";

    // Update test member
    $database->updateMember('1', "Mathew Norman", "coolemail@mat.com", "", "");
    echo "Member update test.<br>";

    // List members
    $database->listMembers();
    echo "Member list test.<br>";

    // Delete test member
    $database->deleteMember('2');
    echo "Member delete test.<br>";

    // List members
    $database->listMembers();
    echo "Member list test.<br>";
?>