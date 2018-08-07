<?php
include 'sqlcreds.php';

class Database {

    //// GENERAL FUNCTIONALITY //// 
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
            email VARCHAR(50) NOT NULL,
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

    // Test database connection.
    function testConnection($server="", $dbuser="", $dbpass="", $dbname="") {
        if ($server=="") {
            $server = $GLOBALS['server'];
        }
        if ($dbuser=="") {
            $dbuser = $GLOBALS['user'];
        }
        if ($dbpass=="") {
            $dbpass = $GLOBALS['pass'];
        }
        if ($dbname=="") {
            $dbname = $GLOBALS['dbname'];
        }
        
        // Create connection
        $connection = new mysqli($server, $dbuser, $dbpass, $dbname);
                
        // Check connection and return status
        if ($connection->connect_error) {
            $connection->close();
            return False;
        } else {
            $connection->close();
            return True;
        }
    }

    //// ADMIN TABLE FUNCTIONALITY //// 
    // List all admins in the admin table.
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
                echo "The admins table is empty.";
            } else {
                while ($row=mysqli_fetch_row($result)) {
                    // Replace and remove the delete button functionality for the key admin.
                    if ($row[0] == 1) { echo "<tr><td>$row[0]</td><td>$row[1]</td><td>$row[2]</td><td>$row[3]</td><td>$row[4]</td><td><a href=\"updateadmin.php?id=".$row[0]."\"><i class=\"fas fa-sync fa-lg\"></i></a></td><td><span title=\"You cannot delete the primary admin account.\"><i class=\"fa fa-key fa-lg\"></i></span></td></tr>"; }
                    else { echo "<tr><td>$row[0]</td><td>$row[1]</td><td>$row[2]</td><td>$row[3]</td><td>$row[4]</td><td><a href=\"updateadmin.php?id=".$row[0]."\"><i class=\"fas fa-sync fa-lg\"></i></a></td><td><a href=\"../php/deleteuser.php?table=admin&id=".$row[0]."\"><i class=\"fas fa-trash fa-lg\"></i></a></td></tr>"; }
                }
            }
            mysqli_free_result($result);
        } else {
            die("There was an error retreiving a list of admins from the database:<br>$connection->error<br>");
        }

        // Close the connection
        $connection->close();
    }
    
    // Search the admins table.
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
                while ($row=mysqli_fetch_row($result)) {
                    // Replace and remove the delete button functionality for the key admin.
                    if ($row[0] == 1) { echo str_replace($searchq, "<b>$searchq</b>","<tr><td>$row[0]</td><td>$row[1]</td><td>$row[2]</td><td>$row[3]</td><td>$row[4]</td><td><a href=\"updateadmin.php?id=".$row[0]."\"><i class=\"fas fa-sync fa-lg\"></i></a></td><td><span title=\"You cannot delete the primary admin account.\"><i class=\"fa fa-key fa-lg\"></i></span></td></tr>"); }
                    else { echo str_replace($searchq, "<b>$searchq</b>","<tr><td>$row[0]</td><td>$row[1]</td><td>$row[2]</td><td>$row[3]</td><td>$row[4]</td><td><a href=\"updateadmin.php?id=".$row[0]."\"><i class=\"fas fa-sync fa-lg\"></i></a></td><td><a href=\"../php/deleteuser.php?table=admin&id=".$row[0]."\"><i class=\"fas fa-trash fa-lg\"></i></a></td></tr>"); }
                }
            }
            mysqli_free_result($result);
        } else {
            die("There was an error searching the database:<br>$connection->error<br>");
        }

        // Close the connection
        $connection->close();
    }

    function loginAdmin($email, $pass) {
        
        $passhash = hash("sha512", $pass);
        
        
        include_once "./php/sessions.php";
        $sessions = new Sessions();

        // return "Sessions class has been included";

        // Create connection
        $connection = new mysqli($GLOBALS['server'], $GLOBALS['user'], $GLOBALS['pass'], $GLOBALS['dbname']);
                
        // Check connection
        if ($connection->connect_error) {
            die("Connection failed<br>$connection->connect_error");
        }

        $sql = "SELECT * FROM admins WHERE email = '$email' AND password = '$passhash'";

        if ($result = mysqli_query($connection, $sql)) {
            if (!(mysqli_num_rows($results) <> 1)) {
                return mysqli_fetch_row($result);
            }
        }
        

        // // If there are no users matching the email/passhash in the admins db, return false otherwise create session & return true.
        // if ($result = mysqli_query($connection, $sql)) {
        //     if (mysqli_num_rows($result) > 0) {
        //         // Get user details
        //         $row = mysqli_fetch_row($result);
        //         // Create session
        //         $sessions->startSession($row[0]. $row[1]);
        //         // Close mysqli connection
        //         $connection->close();
        //         return true;
        //     } else {
        //         $connection->close();
        //         return false;
        //     }
        // }

    }
    
    function fetchAdminInfo($adminid) {
        // Create connection
        $connection = new mysqli($GLOBALS['server'], $GLOBALS['user'], $GLOBALS['pass'], $GLOBALS['dbname']);
                
        // Check connection
        if ($connection->connect_error) {
            die("Connection failed<br>$connection->connect_error");
        }

        // Form SQL query
        $sql = "SELECT firstname, lastname, email, phone FROM admins WHERE id = '$adminid'";

        $userInfo;

        // Fetch each line and display in table
        if ($result = mysqli_query($connection, $sql)) {
            $row=mysqli_fetch_row($result);
            
            $userInfo = array("fname"=>$row[0], "lname"=>$row[1], "email"=>$row[2], "phone"=>$row[3]);

            mysqli_free_result($result);
        } else {
            die("There was an error retreiving a list of admins from the database:<br>$connection->error<br>");
        }

        $connection->close();

        return $userInfo;
    }

    // Add an admin to the admins table.
    function addAdmin($firstname, $lastname, $email, $phone="", $password) {
        // Encrypt the admin password before inserting into the database
        $passhash = hash("sha512", $password);
        
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

    // Update an admin in the admins table.
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

    // Delete an admin from the admins table.
    function removeAdmin($adminid) {
        // Prevent deletion of key admin account.
        if ($adminid <= 1) { 

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
    }
    

    //// MEMBERS TABLE FUNCTIONALITY //// 
    // List all members in the members table.
    function fetchMemberInfo($memberid) {
        // Create connection
        $connection = new mysqli($GLOBALS['server'], $GLOBALS['user'], $GLOBALS['pass'], $GLOBALS['dbname']);
                
        // Check connection
        if ($connection->connect_error) {
            die("Connection failed<br>$connection->connect_error");
        }

        // Form SQL query
        $sql = "SELECT firstname, lastname, email, phone FROM members WHERE id = '$memberid'";

        $userInfo;

        // Fetch each line and display in table
        if ($result = mysqli_query($connection, $sql)) {
            $row=mysqli_fetch_row($result);
            
            $userInfo = array("fname"=>$row[0], "lname"=>$row[1], "email"=>$row[2], "phone"=>$row[3]);

            mysqli_free_result($result);
        } else {
            die("There was an error retreiving a list of admins from the database:<br>$connection->error<br>");
        }

        return $userInfo;

        $connection->close();
    }

    function listMembers() {
        // Create connection
        $connection = new mysqli($GLOBALS['server'], $GLOBALS['user'], $GLOBALS['pass'], $GLOBALS['dbname']);
                
        // Check connection.
        if ($connection->connect_error) {
            die("Connection failed<br>$connection->connect_error");
        }

        // Form SQL query
        $sql = "SELECT id, firstname, lastname, email, phone FROM members ORDER BY id";

        // Fetch each line and display in table.
        if ($result = mysqli_query($connection, $sql)) {
            if (mysqli_num_rows($result) === 0) {
                echo "The members table is empty.<br>";
            } else {
                while ($row=mysqli_fetch_row($result)) {
                    echo "<tr><td>$row[0]</td><td>$row[1]</td><td>$row[2]</td><td>$row[3]</td><td>$row[4]</td><td><a href=\"updatemember.php?id=".$row[0]."\"><i class=\"fas fa-sync fa-lg\"></i></a></td><td><a href=\"../php/deleteuser.php?table=member&id=".$row[0]."\"><i class=\"fas fa-trash fa-lg\"></i></a></td></tr>";
                }
            }
            mysqli_free_result($result);
        } else {
            die("There was an error listing the members from the database:<br>$connection->error<br>");
        }

        // Close the connection
        $connection->close();
    }

    // Search the members table
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
                while ($row=mysqli_fetch_row($result)) {
                    echo str_replace($searchq, "<b>$searchq</b>","<tr><td>$row[0]</td><td>$row[1]</td><td>$row[2]</td><td>$row[3]</td><td>$row[4]</td><td><a href=\"updatemember.php?id=".$row[0]."\"><i class=\"fas fa-sync fa-lg\"></i></a></td><td><a href=\"../php/deleteuser.php?table=member&id=".$row[0]."\"><i class=\"fas fa-trash fa-lg\"></i></a></td></tr>");
                }
            }
            mysqli_free_result($result);
        } else {
            die("There was an error searching the database:<br>$connection->error<br>");
        }

        // Close the connection
        $connection->close();
    }

    // Add a member to the members table.
    function addMember($firstname, $lastname, $email="", $phone="", $cardkey) {
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

    //// LOG TABLE FUNCTIONALITY //// 
    // Functions to include
    // addEntry($memberid, $readerid, $datetime)
    // listEntries()
    // searchEntries($searchq)
    function listEntries() {
        // Create connection
        $connection = new mysqli($GLOBALS['server'], $GLOBALS['user'], $GLOBALS['pass'], $GLOBALS['dbname']);
                
        // Check connection.
        if ($connection->connect_error) {
            die("Connection failed<br>$connection->connect_error");
        }

        // Form SQL query
        $sql = "SELECT memberid, readerid, access_date FROM logs ORDER BY id";

        // Fetch each line and display in table.
        if ($result = mysqli_query($connection, $sql)) {
            // Output results as table rows.
        } else {
            die("There was an error listing the members from the database:<br>$connection->error<br>");
        }

        // Close the connection
        $connection->close();
    }


    //// PRIVILEDGE TABLE FUNCTIONALITY //// 
    // Functions to include
    // addPriviledge($memberid,$readerid,$readergroup)
    // removePriviledge($id)
    // modifyPriviledge($id,$memberid,$readerid,$readergroup)
    // listPriviledges()

    //// READER TABLE FUNCTIONALITY //// 
    // Functions to include
    // addReader($name,$group,$timeout)
    // updateReader($name,$group,$timeout)
    // removeReader($readerid)
    // listReaders()
    // searchReaders($searchq)

    

}
?>