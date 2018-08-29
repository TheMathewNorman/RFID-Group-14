<?php
include_once "../php/sqlcreds.php";

// Create connection
$connection = new mysqli($GLOBALS['server'], $GLOBALS['user'], $GLOBALS['pass'], $GLOBALS['dbname']);
        
// Check connection
if ($connection->connect_error) {
    die("Connection failed<br>$connection->connect_error");
} else {

    // Clear current contents
    $sql = "DELETE FROM admins; DELETE FROM logs; DELETE FROM members; DELETE FROM privilege; DELETE FROM readers";
    mysqli_multi_query($connection, $sql);
    echo "Clear tables.<br>";

    // Populate admins
    $sql = "INSERT INTO admins(firstname, lastname, email, phone, passhash) VALUES('RFID','Admin','admin@therfid.men','0400000000','".hash("sha512", "Password1")."')";
    $connection->query($sql);
    echo "Admins successful.<br>";

    // Populate members
    $sql = "INSERT INTO members(firstname, lastname, email, phone, cardkey) VALUES('Adam','Smeaton','adam@deakin.com','0411111111','".hash('sha512','111222333')."')";
    $connection->query($sql);
    $sql = "INSERT INTO members(firstname, lastname, email, phone, cardkey) VALUES('Asrin','Dayananda','asrin@deakin.com','0422222222','".hash('sha512','222333444')."')";
    $connection->query($sql);
    $sql = "INSERT INTO members(firstname, lastname, email, phone, cardkey) VALUES('Jonathon','Davis','jon@deakin.com','0433333333','".hash('sha512','333444555')."')";
    $connection->query($sql);
    $sql = "INSERT INTO members(firstname, lastname, email, phone, cardkey) VALUES('Mathew','Norman','mathew@deakin.com','0444444444','".hash('sha512','444555666')."')";
    $connection->query($sql);
    $sql = "INSERT INTO members(firstname, lastname, email, phone, cardkey) VALUES('Yuan','Ren','yuan@deakin.com','0455555555','".hash('sha512','555666777')."')";
    $connection->query($sql);
    echo "Members successful.<br>";

    // Populate readers
    $sql = "INSERT INTO readers(id, reader_name, reader_group) VALUES(1, 'Door', 1)";
    $connection->query($sql);
    $sql = "INSERT INTO readers(id, reader_name, reader_group) VALUES(2, '3D Printer', 2)";
    $connection->query($sql);
    $sql = "INSERT INTO readers(id, reader_name, reader_group) VALUES(3, 'CNC Machine', 2)";
    $connection->query($sql);
    echo "Readers successful.<br>";

    // Populate privilege
    $sql = "INSERT INTO privilege(id, member_id, reader_id) VALUES(1, 1, 1)";
    $connection->query($sql);
    $sql = "INSERT INTO privilege(id, member_id, reader_id) VALUES(2, 2, 1)";
    $connection->query($sql);
    $sql = "INSERT INTO privilege(id, member_id, reader_id) VALUES(3, 3, 1)";
    $connection->query($sql);
    $sql = "INSERT INTO privilege(id, member_id, reader_id) VALUES(4, 4, 1)";
    $connection->query($sql);
    $sql = "INSERT INTO privilege(id, member_id, reader_id) VALUES(5, 5, 1)";
    $connection->query($sql);
    $sql = "INSERT INTO privilege(id, member_id, reader_id) VALUES(6, 1, 2)";
    $connection->query($sql);
    $sql = "INSERT INTO privilege(id, member_id, reader_id) VALUES(7, 2, 2)";
    $connection->query($sql);
    $sql = "INSERT INTO privilege(id, member_id, reader_id) VALUES(8, 4, 2)";
    $connection->query($sql);
    $sql = "INSERT INTO privilege(id, member_id, reader_id) VALUES(9, 2, 3)";
    $connection->query($sql);
    $sql = "INSERT INTO privilege(id, member_id, reader_id) VALUES(10, 3, 3)";
    $connection->query($sql);
    $sql = "INSERT INTO privilege(id, member_id, reader_id) VALUES(11, 5, 3)";
    $connection->query($sql);
    echo "Privilege successful.<br>";

    // Populate logs
    $sql = "INSERT INTO logs(id, member_id, reader_id) VALUES(1, 1, 1)";
    $connection->query($sql);
    $sql = "INSERT INTO logs(id, member_id, reader_id) VALUES(2, 2, 1)";
    $connection->query($sql);
    $sql = "INSERT INTO logs(id, member_id, reader_id) VALUES(3, 3, 1)";
    $connection->query($sql);
    $sql = "INSERT INTO logs(id, member_id, reader_id) VALUES(4, 4, 1)";
    $connection->query($sql);
    $sql = "INSERT INTO logs(id, member_id, reader_id) VALUES(5, 5, 1)";
    $connection->query($sql);
    $sql = "INSERT INTO logs(id, member_id, reader_id) VALUES(6, 1, 2)";
    $connection->query($sql);
    $sql = "INSERT INTO logs(id, member_id, reader_id) VALUES(7, 2, 2)";
    $connection->query($sql);
    $sql = "INSERT INTO logs(id, member_id, reader_id) VALUES(8, 3, 3)";
    $connection->query($sql);
    $sql = "INSERT INTO logs(id, member_id, reader_id) VALUES(9, 4, 2)";
    $connection->query($sql);
    $sql = "INSERT INTO logs(id, member_id, reader_id) VALUES(10, 5, 3)";
    $connection->query($sql);
    $sql = "INSERT INTO logs(id, member_id, reader_id) VALUES(11, 1, 2)";
    $connection->query($sql);
    $sql = "INSERT INTO logs(id, member_id, reader_id) VALUES(12, 2, 3)";
    $connection->query($sql);
    $sql = "INSERT INTO logs(id, member_id, reader_id) VALUES(13, 3, 3)";
    $connection->query($sql);
    $sql = "INSERT INTO logs(id, member_id, reader_id) VALUES(14, 4, 2)";
    $connection->query($sql);
    $sql = "INSERT INTO logs(id, member_id, reader_id) VALUES(15, 5, 3)";
    $connection->query($sql);
    echo "Logs successful.<br>";
}

echo "Successful.";

// Close the connection
$connection->close();

?>