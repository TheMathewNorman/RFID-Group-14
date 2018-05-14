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
        $sql = "CREATE TABLE admins (
            id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
            firstname VARCHAR(30) NOT NULL,
            lastname VARCHAR(30) NOT NULL,
            email VARCHAR(50),
            phone VARCHAR(10),
            passhash VARCHAR(128) NOT NULL
        ); CREATE TABLE members (
            id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
            firstname VARCHAR(30) NOT NULL,
            lastname VARCHAR(30) NOT NULL,
            email VARCHAR(50),
            phone VARCHAR(10),
            cardkey VARCHAR(128) NOT NULL
        ); CREATE TABLE priviledge (
            id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
            member_id INT(6) NOT NULL,
            reader_id INT(6),
            reader_group INT(6)
        ); CREATE TABLE readers (
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
    // List all admins in the admin table
    function listAdmins() {
        // Create connection
        $connection = new mysqli($GLOBALS['server'], $GLOBALS['user'], $GLOBALS['pass'], $GLOBALS['dbname']);
        
        // Check connection
        if ($connection->connect_error) {
            die("Connection failed<br>$connection->connect_error");
        }

        // Form SQL query
        $sql = "SELECT id, firstname, lastname, email, phone FROM admins ORDER BY id";

        // Fetch each line and display in table
        if ($result = mysqli_query($connection, $sql)) {
            if (mysqli_num_rows($result) === 0) {
                echo "The adminss table is empty.<br>";
            } else {
                echo '<table><tr><th>Admin ID</th><th>First Name</th><th>Last Name</th><th>Email Address</th><th>Phone No.</th></tr>';
                while ($row=mysqli_fetch_row($result)) {
                    echo "<tr><td>$row[0]</td><td>$row[1]</td><td>$row[2]</td><td>$row[3]</td><td>$row[4]</td></tr>";
                }
                echo '</table>';
            }
            mysqli_free_result($result);
        } else {
            die("There was an error retreiving a list of admins from the database:<br>$connection->error<br>");
        }

        // Close the connection
        $connection->close();
    }
    
    function searchAdmins($searchq) {
        $formattedsearchq = strtolower(htmlspecialchars($searchq));
        
        // Create connection
        $connection = new mysqli($GLOBALS['server'], $GLOBALS['user'], $GLOBALS['pass'], $GLOBALS['dbname']);
                
        // Check connection
        if ($connection->connect_error) {
            die("Connection failed<br>$connection->connect_error");
        }

        // Form SQL query
        $sql = "SELECT * FROM admins
        WHERE id LIKE '%$searchq%'
        OR LOWER(firstname) LIKE '%$searchq%'
        OR LOWER(lastname) LIKE '%$searchq%'
        OR LOWER(email) LIKE '%$searchq%'
        OR LOWER(phone) LIKE '%$searchq%'";

        // Fetch each line and display in table
        if ($result = mysqli_query($connection, $sql)) {
            if (mysqli_num_rows($result) === 0) {
                echo "The admins table contains no match for the search: <b>$searchq</b><br>";
            } else {
                echo "Found ".mysqli_num_rows($result)." results for $searchq<br>";
                echo '<table><tr><th>Admin ID</th><th>First Name</th><th>Last Name</th><th>Email Address</th><th>Phone No.</th></tr>';
                while ($row=mysqli_fetch_row($result)) {
                    echo str_replace($searchq, "<b>$searchq</b>","<tr><td>$row[0]</td><td>$row[1]</td><td>$row[2]</td><td>$row[3]</td><td>$row[4]</td></tr>");
                }
                echo '</table>';
            }
            mysqli_free_result($result);
        } else {
            die("There was an error searching the database:<br>$connection->error<br>");
        }

        // Close the connection
        $connection->close();
    }
    
    function addAdmin($firstname, $lastname, $email, $phone, $pass) {
        // Encrypt the admin password before inserting into the database
        $passhash = hash("sha512", $pass);
        
        // Create connection
        $connection = new mysqli($GLOBALS['server'], $GLOBALS['user'], $GLOBALS['pass'], $GLOBALS['dbname']);
        
        // Check connection
        if ($connection->connect_error) {
            die("Connection failed<br>$connection->connect_error");
        }

        // Form SQL query
        $sql = "INSERT INTO admins (firstname, lastname, email, phone, passhash) VALUES ('$firstname', '$lastname', '$email', '$phone', '$passhash')";

        // Try DB insertion, die on error.
        if ($connection->query($sql) !== TRUE) {
            die("Error adding admin:<br>$connection->error<br>");
        }
    
        // Close the connection
        $connection->close();
    }

    function updateAdmin($adminid, $firstname, $lastname, $email, $phone, $pass) {
        // Encrypt the card key before inseting into the database if set
        $passhash = "";
        if ($pass != "") {
            $passhash = hash("sha512", $pass);
        }

        // Create connection
        $connection = new mysqli($GLOBALS['server'], $GLOBALS['user'], $GLOBALS['pass'], $GLOBALS['dbname']);

        // Check connection
        if ($connection->connect_error) {
            die("Connection failed:<br>$connection->connect_error");
        }

        // Form multiquery SQL
        $sql = "";
        if ($firstname != "") { $sql .= "UPDATE admins SET firstname = '$firstname' WHERE id = '$adminid';"; }
        if ($lastname != "") { $sql .= "UPDATE admins SET lastname = '$lastname' WHERE id = '$adminid';"; }
        if ($email != "") { $sql .= "UPDATE admins SET email = '$email' WHERE id = '$adminid';"; }
        if ($phone != "") { $sql .= "UPDATE admins SET phone = '$phone' WHERE id = '$adminid';"; }
        if ($passhash != "") { $sql .= "UPDATE admins SET passhash = '$passhash' WHERE id = '$adminid';"; }


        // Try performing multi SQL query, die on error.
        if (mysqli_multi_query($connection, $sql) === FALSE) {
            die("Error updating admin:<br>$connection->error");
        }

        // Close the connection
        $connection->close();
    }

    function removeAdmin($adminid) {
        // Create connection
        $connection = new mysqli($GLOBALS['server'], $GLOBALS['user'], $GLOBALS['pass'], $GLOBALS['dbname']);

        // Check connection
        if ($connection->connect_error) {
            die("Connection failed<br>$connection->connect_error");
        }

        // Form SQL query
        $sql = "DELETE FROM admins WHERE id = '$adminid'";

        // Try DB delete, die on error.
        if ($connection->query($sql) !== TRUE) {
            die("Error deleting admin:<br>$connection->error");
        }

        // Close the connection
        $connection->close();
    }

    // Functions to include
    // addAdmin($firstname, $lastname, $email, $phone, $pass)
    // updateAdmin($id, $firstname, $lastname, $email, $phone, $pass)
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
        $sql = "SELECT id, firstname, lastname, email, phone FROM members ORDER BY id";

        // Fetch each line and display in table
        if ($result = mysqli_query($connection, $sql)) {
            if (mysqli_num_rows($result) === 0) {
                echo "The members table is empty.<br>";
            } else {
                echo '<table><tr><th>Member ID</th><th>First Name</th><th>Last Name</th><th>Email Address</th><th>Phone No.</th></tr>';
                while ($row=mysqli_fetch_row($result)) {
                    echo "<tr><td>$row[0]</td><td>$row[1]</td><td>$row[2]</td><td>$row[3]</td><td>$row[4]</td></tr>";
                }
                echo '</table>';
            }
            mysqli_free_result($result);
        } else {
            die("There was an error listing the members from the database:<br>$connection->error<br>");
        }

        // Close the connection
        $connection->close();
    }

    function searchMembers($searchq) {
        $formattedsearchq = strtolower(htmlspecialchars($searchq));
        
        // Create connection
        $connection = new mysqli($GLOBALS['server'], $GLOBALS['user'], $GLOBALS['pass'], $GLOBALS['dbname']);
                
        // Check connection
        if ($connection->connect_error) {
            die("Connection failed<br>$connection->connect_error");
        }

        // Form SQL query
        $sql = "SELECT * FROM members
        WHERE id LIKE '%$searchq%'
        OR LOWER(firstname) LIKE '%$searchq%'
        OR LOWER(lastname) LIKE '%$searchq%'
        OR LOWER(email) LIKE '%$searchq%'
        OR LOWER(phone) LIKE '%$searchq%'";

        // Fetch each line and display in table
        if ($result = mysqli_query($connection, $sql)) {
            if (mysqli_num_rows($result) === 0) {
                echo "The members table contains no match for the search: <b>$searchq</b><br>";
            } else {
                echo "Found ".mysqli_num_rows($result)." results for $searchq<br>";
                echo '<table><tr><th>Member ID</th><th>First Name</th><th>Last Name</th><th>Email Address</th><th>Phone No.</th></tr>';
                while ($row=mysqli_fetch_row($result)) {
                    echo str_replace($searchq, "<b>$searchq</b>","<tr><td>$row[0]</td><td>$row[1]</td><td>$row[2]</td><td>$row[3]</td><td>$row[4]</td></tr>");
                }
                echo '</table>';
            }
            mysqli_free_result($result);
        } else {
            die("There was an error searching the database:<br>$connection->error<br>");
        }

        // Close the connection
        $connection->close();
    }

    // Add a member to the members table.
    function addMember($firstname, $lastname, $email, $phone, $cardkey) {
        // Encrypt the card key before inserting into the database
        $cardkeyhash = hash("sha512", $cardkey);
        
        // Create connection
        $connection = new mysqli($GLOBALS['server'], $GLOBALS['user'], $GLOBALS['pass'], $GLOBALS['dbname']);
        
        // Check connection
        if ($connection->connect_error) {
            die("Connection failed<br>$connection->connect_error");
        }

        // Form SQL query
        $sql = "INSERT INTO members (firstname, lastname, email, phone, cardkey) VALUES ('$firstname', '$lastname', '$email', '$phone', '$cardkeyhash')";

        // Try DB insertion, die on error.
        if ($connection->query($sql) !== TRUE) {
            die("Error adding member:<br>$connection->error<br>");
        }
    
        // Close the connection
        $connection->close();
    }

    // Update a member in the members table.
    function updateMember($memberid, $firstname, $lastname, $email, $phone, $cardkey) {
        // Encrypt the card key before inseting into the database if set
        $cardkeyhash = "";
        if ($cardkey != "") {
            $cardkeyhash = hash("sha512", $cardkey);
        }
        
        // Create connection
        $connection = new mysqli($GLOBALS['server'], $GLOBALS['user'], $GLOBALS['pass'], $GLOBALS['dbname']);
        
        // Check connection
        if ($connection->connect_error) {
            die("Connection failed:<br>$connection->connect_error");
        }

        // Form multiquery SQL
        $sql = "";
        if ($firstname != "") { $sql .= "UPDATE members SET firstname = '$firstname' WHERE id = '$memberid';"; }
        if ($lastname != "") { $sql .= "UPDATE members SET lastname = '$lastname' WHERE id = '$memberid';"; }
        if ($email != "") { $sql .= "UPDATE members SET email = '$email' WHERE id = '$memberid';"; }
        if ($phone != "") { $sql .= "UPDATE members SET phone = '$phone' WHERE id = '$memberid';"; }
        if ($cardkeyhash != "") { $sql .= "UPDATE members SET cardkey = '$cardkeyhash' WHERE id = '$memberid';"; }


        // Try performing multi SQL query, die on error.
        if (mysqli_multi_query($connection, $sql) === FALSE) {
            die("Error updating member:<br>$connection->error");
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
            die("Error deleting member:<br>$connection->error");
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