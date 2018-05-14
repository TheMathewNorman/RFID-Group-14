<?php    
    include "database.php";

    // Create the tables
    $database = new Database;
    $database->createTables();
    echo "Tables created successfully.<br>";


    // List Admins
    echo "Admin list test.<br>";
    $database->listAdmins();

    // Insert test Admin
    echo "Admin add test 1.<br>";
    $database->addAdmin("Mathew","Norman", "someone@example.com", "0400000000", "Password1");
    echo "Admin add test 2.<br>";
    $database->addAdmin("Mathew","Norman", "someoneelse@example.com", "0400000000", "Password2");

    echo "Search test 1.<br>";
    $database->searchAdmins("Ma");
    $database->searchAdmins("amte");

    // List Admins
    echo "Admin list test.<br>";
    $database->listAdmins();

    // Update test Admin
    echo "Admin update test.<br>";
    $database->updateAdmin('1', "Mathew","Norman", "coolemail@mat.com", "", "");

    // List Admins
    echo "Admin list test.<br>";
    $database->listAdmins();

    // Delete test Admin
    echo "Admin delete test.<br>";
    $database->deleteAdmin(2);

    // List Admins
    echo "Admin list test.<br>";
    $database->listAdmins();


    // List members
    echo "Member list test.<br>";
    $database->listMembers();

    // Insert test member
    echo "Member add test 1.<br>";
    $database->addMember("Mathew","Norman", "someone@example.com", "0400000000", "0192889112412345643");
    echo "Member add test 2.<br>";
    $database->addMember("Mathew","Norman", "someoneelse@example.com", "0400000000", "asq2889112412345643");

    echo "Search test 1.<br>";
    $database->searchMembers("Ma");
    $database->searchMembers("amte");

    // List members
    echo "Member list test.<br>";
    $database->listMembers();

    // Update test member
    echo "Member update test.<br>";
    $database->updateMember('1', "Mathew","Norman", "coolemail@mat.com", "", "");

    // List members
    echo "Member list test.<br>";
    $database->listMembers();

    // Delete test member
    echo "Member delete test.<br>";
    $database->deleteMember(2);

    // List members
    echo "Member list test.<br>";
    $database->listMembers();
?>