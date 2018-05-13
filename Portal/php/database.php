<?php
include 'sqlcreds.php';

class Database {

    // Create the tables.
    function createTables() {

        // Create connection
        $connection = new mysqli($GLOBALS['server'], $GLOBALS['user'], $GLOBALS['pass'], $GLOBALS['dbname']);
        
        // Check connection
        if ($connection->connect_error) {
            die("Connection failed<br>$connection->connect_error");
        }

        // Create the members table upon success
        $sql = "CREATE TABLE admin (
            id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
            fullname VARCHAR(30) NOT NULL,
            email VARCHAR(50),
            phone VARCHAR(10),
            passhash VARCHAR(128) NOT NULL
        ); CREATE TABLE members (
            id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
            fullname VARCHAR(30) NOT NULL,
            email VARCHAR(50),
            phone VARCHAR(10),
            cardkey VARCHAR(128) NOT NULL
        ); CREATE TABLE priviledge (
            id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
            member_id INT(6) NOT NULL,
            reader_id INT(6),
            reader_group INT(6)
        ); CREATE TABLE reader (
            id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
            reader_name VARCHAR(30) NOT NULL,
            reader_group INT(6) NOT NULL
        ); CREATE TABLE logs (
            id INT(10) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
            member_id INT(6) NOT NULL,
            reader_id INT(6) NOT NULL,
            access_date TIMESTAMP NOT NULL
        )";
            
        if (mysqli_multi_query($connection, $sql) === FASLE) {
            die("Error creating table: " . $connection->error);
        }

        // Close the connection
        $connection->close();
    }

    //// ADMIN TABLE FUNCTIONALITY //// 
    // Functions to include
    // addAdmin($fullname, $email, $phone, $pass)
    // updateAdmin($id,$fullname, $email, $phone, $pass)
    // removeAdmin($id)
    // listAdmins()
    // searchAdmins($searchq)
    

    //// MEMBERS TABLE FUNCTIONALITY //// 
    // List all members in the members table.
    function listMembers() {
        // Create connection
        $connection = new mysqli($GLOBALS['server'], $GLOBALS['user'], $GLOBALS['pass'], $GLOBALS['dbname']);
                
        // Check connection
        if ($connection->connect_error) {
            die("Connection failed<br>$connection->connect_error");
        }

        // Form SQL query
        $sql = "SELECT id, fullname, email, phone FROM members ORDER BY id";

        // Fetch each line and display in table
        if ($result = mysqli_query($connection, $sql)) {
            if (mysqli_num_rows($result) === 0) {
                echo "The members table is empty.<br>";
            } else {
                echo '<table><tr><th>Member ID</th><th>Full Name</th><th>Email Address</th><th>Phone No.</th></tr>';
                while ($row=mysqli_fetch_row($result)) {
                    echo "<tr><td>$row[0]</td><td>$row[1]</td><td>$row[2]</td><td>$row[2]</td></tr>";
                }
                echo '</table>';
            }
            mysqli_free_result($result);
        }

        // Close the connection
        $connection->close();
    }

    function searchMembers($searchq) {
        $nohtmlsearchq = strtolower(htmlspecialchars($searchq));
        
        // Create connection
        $connection = new mysqli($GLOBALS['server'], $GLOBALS['user'], $GLOBALS['pass'], $GLOBALS['dbname']);
                
        // Check connection
        if ($connection->connect_error) {
            die("Connection failed<br>$connection->connect_error");
        }

        // Form SQL query
        $sql = "SELECT * FROM 'members'
        WHERE
        CONCAT(id,fullname,email,phone)
        LIKE '%$nohtmlsearchq%'";

        // Fetch each line and display in table
        if ($result = mysqli_query($connection, $sql)) {
            if (mysqli_num_rows($result) === 0) {
                echo "The members table contains no match for the search:<b>$nohtmlsearchq<b><br>";
            } else {
                echo "Found ".mysqli_num_rows($result)." results for $nohtmlsearchq<br>";
                echo '<table><tr><th>Member ID</th><th>Full Name</th><th>Email Address</th><th>Phone No.</th></tr>';
                while ($row=mysqli_fetch_row($result)) {
                    echo str_replace($nohtmlsearchq, "<b>$nohtmlsearchq</b>","<tr><td>$row[0]</td><td>$row[1]</td><td>$row[2]</td><td>$row[2]</td></tr>");
                }
                echo '</table>';
            }
            mysqli_free_result($result);
        } else {
            die("There was an error searching the database:$connection->error");
        }

        // Close the connection
        $connection->close();
    }

    // Add a member to the members table.
    function addMember($fullname, $email, $phone, $cardkey) {
        // Encrypt the card key before inseting into the database
        $cardkeyhash = hash("sha512", $cardkey);
        
        // Create connection
        $connection = new mysqli($GLOBALS['server'], $GLOBALS['user'], $GLOBALS['pass'], $GLOBALS['dbname']);
        
        // Check connection
        if ($connection->connect_error) {
            die("Connection failed<br>$connection->connect_error");
        }

        // Form SQL query
        $sql = "INSERT INTO members (fullname, email, phone, cardkey) VALUES ('$fullname', '$email', '$phone', '$cardkeyhash')";

        // Try DB insertion, die on error.
        if ($connection->query($sql) !== TRUE) {
            die("Error adding member".$connection->error);
        }
    
        // Close the connection
        $connection->close();
    }

    // Update a member in the members table.
    function updateMember($memberid, $fullname, $email, $phone, $cardkey) {
        // Encrypt the card key before inseting into the database if set
        $cardkeyhash = "";
        if ($cardkey != "") {
            $cardkeyhash = hash("sha512", $cardkey);
        }
        
        // Create connection
        $connection = new mysqli($GLOBALS['server'], $GLOBALS['user'], $GLOBALS['pass'], $GLOBALS['dbname']);
        
        // Check connection
        if ($connection->connect_error) {
            die("Connection failed<br>$connection->connect_error");
        }

        // Form multiquery SQL
        $sql = "";
        if ($fullname != "") { $sql .= "UPDATE members SET fullname = '$fullname' WHERE id = '$memberid';"; }
        if ($email != "") { $sql .= "UPDATE members SET email = '$email' WHERE id = '$memberid';"; }
        if ($phone != "") { $sql .= "UPDATE members SET phone = '$phone' WHERE id = '$memberid';"; }
        if ($cardkeyhash != "") { $sql .= "UPDATE members SET cardkey = '$cardkeyhash' WHERE id = '$memberid';"; }


        // Try performing multi SQL query, die on error.
        if (mysqli_multi_query($connection, $sql) === FALSE) {
            die("Error adding member<br>$connection->error");
        }
        
        // Close the connection
        $connection->close();
    }
    
    // Delete a member from the members table.
    function removeMember($memberid) {
        // Create connection
        $connection = new mysqli($GLOBALS['server'], $GLOBALS['user'], $GLOBALS['pass'], $GLOBALS['dbname']);

        // Check connection
        if ($connection->connect_error) {
            die("Connection failed<br>$connection->connect_error");
        }

        // Form SQL query
        $sql = "DELETE FROM members WHERE id = '$memberid'";

        // Try DB delete, die on error.
        if ($connection->query($sql) !== TRUE) {
            die("Error deleting member<br>$connection->error");
        }

        // Close the connection
        $connection->close();
    }

    //// PRIVILEDGE TABLE FUNCTIONALITY //// 
    // Functions to include

    //// READER TABLE FUNCTIONALITY //// 
    // Functions to include
    // addReader($name,$group,$timeout)
    // updateReader($name,$group,$timeout)
    // removeReader($readerid)
    // listReaders()
    // searchReaders($searchq)

    //// LOG TABLE FUNCTIONALITY //// 
    // Functions to include
    // addEntry($memberid, $readerid, $datetime)
    // listEntries()
    // searchEntries($searchq)

}
?>