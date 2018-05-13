<?php    
    include "database.php";

    // DROP TABLES BEFORE RUNNING TEST
    $connection = new mysqli($GLOBALS['server'], $GLOBALS['user'], $GLOBALS['pass'], $GLOBALS['dbname']);
    if ($connection->connect_error) {
        die("Connection failed<br>$connection->connect_error");
    }
    $sql = "DROP TABLE 'admin','logs','members','readers','priviledge'";
    // Try DB insertion, die on error.
    if ($connection->query($sql) !== TRUE) {
        die("Error dropping table:<br>$connection->error");
    }
    $connection->close();

    // Create the tables
    $database = new Database;
    $database->createTables();

    echo "Tables created successfully.<br>";

    // List members
    echo "Member list test.<br>";
    $database->listMembers();

    // Insert test member
    echo "Member add test 1.<br>";
    $database->addMember("Mathew Norman", "someone@example.com", "0400000000", "0192889112412345643");
    echo "Member add test 2.<br>";
    $database->addMember("Mathew Norman", "someoneelse@example.com", "0400000000", "asq2889112412345643");

    echo "Search test 1.<br>";
    $database->searchMembers("Ma");
    echo "Search test 2.<br>";
    $database->searchMembers("abcd");

    // List members
    echo "Member list test.<br>";
    $database->listMembers();

    // Update test member
    echo "Member update test.<br>";
    $database->updateMember('1', "Mathew Norman", "coolemail@mat.com", "", "");

    // List members
    echo "Member list test.<br>";
    $database->listMembers();

    // Delete test member
    echo "Member delete test.<br>";
    $database->deleteMember('2');

    // List members
    echo "Member list test.<br>";
    $database->listMembers();
?>