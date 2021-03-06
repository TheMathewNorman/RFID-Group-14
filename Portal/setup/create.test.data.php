<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/config.php';
include_once "../php/database.php";
$database = new Database();
$database->createTables();

// Create connection
$connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        
// Check connection
if ($connection->connect_error) {
    die("Connection failed<br>$connection->connect_error");
} else {

    // Clear current contents
    $sql = "DELETE FROM admins";
    if (!mysqli_query($connection, $sql)) { die("Error clearing admins.".mysqli_error($connection)); }
    $sql = "DELETE FROM logs";
    if (!mysqli_query($connection, $sql)) { die("Error clearing logs.".mysqli_error($connection)); }
    $sql = "DELETE FROM members";
    if (!mysqli_query($connection, $sql)) { die("Error clearing members.".mysqli_error($connection)); }
    $sql = "DELETE FROM privilege";
    if (!mysqli_query($connection, $sql)) { die("Error clearing privilege.".mysqli_error($connection)); }
    $sql = "DELETE FROM readers";
    if (!mysqli_query($connection, $sql)) { die("Error clearing readers.".mysqli_error($connection)); }
    echo "Clear tables.<br>";

    // Populate admins
    $passhash = hash('sha512', 'Password1');
    $sql = "INSERT INTO admins (firstname, lastname, email, phone, passhash) VALUES ('RFID','Admin','admin@therfid.men','0400000000','$passhash')";
    if (!mysqli_query($connection, $sql)) { die("Error running admins. ".mysqli_error($connection)); }
    echo "Admins successful.<br>";

    // Populate members
    $sql = "INSERT INTO members (firstname, lastname, email, phone, cardkey) VALUES ('Adam','Smeaton','adam@deakin.com','0411111111','".hash('sha512','111222333')."')";
    if (!mysqli_query($connection, $sql)) { die("Error running members.".mysqli_error($connection)); }
    $sql = "INSERT INTO members (firstname, lastname, email, phone, cardkey) VALUES ('Asrin','Dayananda','asrin@deakin.com','0422222222','".hash('sha512','222333444')."')";
    if (!mysqli_query($connection, $sql)) { die("Error running members.".mysqli_error($connection)); }
    $sql = "INSERT INTO members (firstname, lastname, email, phone, cardkey) VALUES ('Jonathon','Davis','jon@deakin.com','0433333333','".hash('sha512','333444555')."')";
    if (!mysqli_query($connection, $sql)) { die("Error running members.".mysqli_error($connection)); }
    $sql = "INSERT INTO members (firstname, lastname, email, phone, cardkey) VALUES ('Mathew','Norman','mathew@deakin.com','0444444444','".hash('sha512','444555666')."')";
    if (!mysqli_query($connection, $sql)) { die("Error running members.".mysqli_error($connection)); }
    $sql = "INSERT INTO members (firstname, lastname, email, phone, cardkey) VALUES ('Yuan','Ren','yuan@deakin.com','0455555555','".hash('sha512','555666777')."')";
    if (!mysqli_query($connection, $sql)) { die("Error running members.".mysqli_error($connection)); }
    echo "Members successful.<br>";

    // Populate readers
    $sql = "INSERT INTO readers (id, reader_name, reader_group, signature, approved) VALUES (1, 'Door', 1, 1, 1)";
    if (!mysqli_query($connection, $sql)) { die("Error running readers.".mysqli_error($connection)); }
    $sql = "INSERT INTO readers (id, reader_name, reader_group, signature, approved) VALUES (2, '3D Printer', 2, 2, 1)";
    if (!mysqli_query($connection, $sql)) { die("Error running readers.".mysqli_error($connection)); }
    $sql = "INSERT INTO readers (id, reader_name, reader_group, signature, approved) VALUES (3, 'CNC Machine', 2, 3, 1)";
    if (!mysqli_query($connection, $sql)) { die("Error running readers.".mysqli_error($connection)); }
    echo "Readers successful.<br>";

    // Populate privilege
    $sql = "INSERT INTO privilege (id, member_id, reader_id) VALUES (1, 1, 1)";
    if (!mysqli_query($connection, $sql)) { die("Error running privilege.".mysqli_error($connection)); }
    $sql = "INSERT INTO privilege (id, member_id, reader_id) VALUES (2, 2, 1)";
    if (!mysqli_query($connection, $sql)) { die("Error running privilege.".mysqli_error($connection)); }
    $sql = "INSERT INTO privilege (id, member_id, reader_id) VALUES (3, 3, 1)";
    if (!mysqli_query($connection, $sql)) { die("Error running privilege.".mysqli_error($connection)); }
    $sql = "INSERT INTO privilege (id, member_id, reader_id) VALUES (4, 4, 1)";
    if (!mysqli_query($connection, $sql)) { die("Error running privilege.".mysqli_error($connection)); }
    $sql = "INSERT INTO privilege (id, member_id, reader_id) VALUES (5, 5, 1)";
    if (!mysqli_query($connection, $sql)) { die("Error running privilege.".mysqli_error($connection)); }
    $sql = "INSERT INTO privilege (id, member_id, reader_id) VALUES (6, 1, 2)";
    if (!mysqli_query($connection, $sql)) { die("Error running privilege.".mysqli_error($connection)); }
    $sql = "INSERT INTO privilege (id, member_id, reader_id) VALUES (7, 2, 2)";
    if (!mysqli_query($connection, $sql)) { die("Error running privilege.".mysqli_error($connection)); }
    $sql = "INSERT INTO privilege (id, member_id, reader_id) VALUES (8, 4, 2)";
    if (!mysqli_query($connection, $sql)) { die("Error running privilege.".mysqli_error($connection)); }
    $sql = "INSERT INTO privilege (id, member_id, reader_id) VALUES (9, 2, 3)";
    if (!mysqli_query($connection, $sql)) { die("Error running privilege.".mysqli_error($connection)); }
    $sql = "INSERT INTO privilege (id, member_id, reader_id) VALUES (10, 3, 3)";
    if (!mysqli_query($connection, $sql)) { die("Error running privilege.".mysqli_error($connection)); }
    $sql = "INSERT INTO privilege (id, member_id, reader_id) VALUES (11, 5, 3)";
    if (!mysqli_query($connection, $sql)) { die("Error running privilege.".mysqli_error($connection)); }
    echo "Privilege successful.<br>";

    // Populate logs
    $sql = "INSERT INTO logs (id, member_id, reader_id) VALUES (1, 1, 1)";
    if (!mysqli_query($connection, $sql)) { die("Error running logs.".mysqli_error($connection)); }
    $sql = "INSERT INTO logs (id, member_id, reader_id) VALUES (2, 2, 1)";
    if (!mysqli_query($connection, $sql)) { die("Error running logs.".mysqli_error($connection)); }
    $sql = "INSERT INTO logs (id, member_id, reader_id) VALUES (3, 3, 1)";
    if (!mysqli_query($connection, $sql)) { die("Error running logs.".mysqli_error($connection)); }
    $sql = "INSERT INTO logs (id, member_id, reader_id) VALUES (4, 4, 1)";
    if (!mysqli_query($connection, $sql)) { die("Error running logs.".mysqli_error($connection)); }
    $sql = "INSERT INTO logs (id, member_id, reader_id) VALUES (5, 5, 1)";
    if (!mysqli_query($connection, $sql)) { die("Error running logs.".mysqli_error($connection)); }
    $sql = "INSERT INTO logs (id, member_id, reader_id) VALUES (6, 1, 2)";
    if (!mysqli_query($connection, $sql)) { die("Error running logs.".mysqli_error($connection)); }
    $sql = "INSERT INTO logs (id, member_id, reader_id) VALUES (7, 2, 2)";
    if (!mysqli_query($connection, $sql)) { die("Error running logs.".mysqli_error($connection)); }
    $sql = "INSERT INTO logs (id, member_id, reader_id) VALUES (8, 3, 3)";
    if (!mysqli_query($connection, $sql)) { die("Error running logs.".mysqli_error($connection)); }
    $sql = "INSERT INTO logs (id, member_id, reader_id) VALUES (9, 4, 2)";
    if (!mysqli_query($connection, $sql)) { die("Error running logs.".mysqli_error($connection)); }
    $sql = "INSERT INTO logs (id, member_id, reader_id) VALUES (10, 5, 3)";
    if (!mysqli_query($connection, $sql)) { die("Error running logs.".mysqli_error($connection)); }
    $sql = "INSERT INTO logs (id, member_id, reader_id) VALUES (11, 1, 2)";
    if (!mysqli_query($connection, $sql)) { die("Error running logs.".mysqli_error($connection)); }
    $sql = "INSERT INTO logs (id, member_id, reader_id) VALUES (12, 2, 3)";
    if (!mysqli_query($connection, $sql)) { die("Error running logs.".mysqli_error($connection)); }
    $sql = "INSERT INTO logs (id, member_id, reader_id) VALUES (13, 3, 3)";
    if (!mysqli_query($connection, $sql)) { die("Error running logs.".mysqli_error($connection)); }
    $sql = "INSERT INTO logs (id, member_id, reader_id) VALUES (14, 4, 2)";
    if (!mysqli_query($connection, $sql)) { die("Error running logs.".mysqli_error($connection)); }
    $sql = "INSERT INTO logs (id, member_id, reader_id) VALUES (15, 5, 3)";
    if (!mysqli_query($connection, $sql)) { die("Error running logs.".mysqli_error($connection)); }
    echo "Logs successful.<br>";
}

echo "Successful.";

// Close the connection
$connection->close();

?>
