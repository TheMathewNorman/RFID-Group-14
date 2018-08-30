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
            reader_group INT(6) NOT NULL,
            approved BOOL NOT NULL
        ); CREATE TABLE logs (
            id INT(10) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
            member_id INT(6) NOT NULL,
            reader_id INT(6) NOT NULL,
            access_date TIMESTAMP NOT NULL
        )";
            
        if (mysqli_multi_query($connection, $sql) === FALSE) {
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
        WHERE id LIKE '%$formattedsearchq%'
        OR LOWER(firstname) LIKE '%$formattedsearchq%'
        OR LOWER(lastname) LIKE '%$formattedsearchq%'
        OR LOWER(email) LIKE '%$formattedsearchq%'
        OR LOWER(phone) LIKE '%$formattedsearchq%'";

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
        // Login response
        //[0] = False on failure || True on success.
        //[1] = Error description on failure.
        //['id'] = Admin ID on success
        //['fname'] = Admin First Name on success
        $loginResponse[0] = false;

        // SHA512 hash for password
        $passhash = hash("sha512", $pass);

        // Create connection
        $connection = new mysqli($GLOBALS['server'], $GLOBALS['user'], $GLOBALS['pass'], $GLOBALS['dbname']);
                
        // Check connection
        if ($connection->connect_error) {
            $loginResponse[1] = "Failed to connect to database.";
        } else {

            // SQL Query to match admin account with same email and passhash as entered
            $sql = "SELECT * FROM admins WHERE email = '$email' AND passhash = '$passhash'";

            if ($result = mysqli_query($connection, $sql)) {
                if (mysqli_num_rows($result) == 1) {
                    // LOGIN SUCCESS
                    $loginResponse[0] = true;

                    // Pass login information
                    $userDetails = mysqli_fetch_array($result);
                    $loginResponse[1] = "Login successful.";
                    $loginResponse['id'] = $userDetails['id'];
                    $loginResponse['fname'] = $userDetails['firstname'];

                    // Return successful login response
                    return $loginResponse;
                
                } else if (mysqli_num_rows($result) > 1) {
                    $loginResponse[0] = false;
                    $loginResponse[1] = "There is more than one admin with the email address: $email";
                } else {
                    $loginResponse[0] = false;
                    $loginResponse[1] = "Email or password was incorrect.";
                }
            } else {
                $loginResponse[0] = false;
                $loginResponse[1] = "Failed to run query.";
            }
        }

        // Return login response
        return $loginResponse;
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
        if ($adminid > 1) { 

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
        WHERE id LIKE '%$formattedsearchq%'
        OR LOWER(firstname) LIKE '%$formattedsearchq%'
        OR LOWER(lastname) LIKE '%$formattedsearchq%'
        OR LOWER(email) LIKE '%$formattedsearchq%'
        OR LOWER(phone) LIKE '%$formattedsearchq%'";

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
    function updateMember($memberid = "", $firstname = "", $lastname = "", $email = "", $phone = "", $cardkey = "") {
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
        if (!$connection->query($sql)) {
            die("Error deleting member:<br>$connection->error");
        }

        // Close the connection
        $connection->close();
    }

    //// LOG TABLE FUNCTIONALITY //// 
    // Functions to include
    // addLogEntry($memberid, $readerid, $datetime)
    function addLogEntry($readerid, $key) {
        $keyhash = hash('sha512', $key);

        // Create connection
        $connection = new mysqli($GLOBALS['server'], $GLOBALS['user'], $GLOBALS['pass'], $GLOBALS['dbname']);

        // Check connection.
        if ($connection->connect_error) {
            die("Connection failed<br>$connection->connect_error");
        }

        // Get member id from key
        $sql = "SELECT id FROM members WHERE cardkey = '$keyhash'";
        if ($result = mysqli_query($connection, $sql)) {
            if ($row = mysqli_fetch_row($result)) {
                $memberid = $row[0];
                
                // Add log entry
                $sql = "INSERT INTO logs (member_id, reader_id) VALUES ($memberid, $readerid)";
                if (!mysqli_query($connection, $sql)) {
                    return false;
                }
            }
        }

        // Close the connection
        $connection->close();
    }
    function getLogEntries() {
        $logHTML = "";

        // Create connection
        $connection = new mysqli($GLOBALS['server'], $GLOBALS['user'], $GLOBALS['pass'], $GLOBALS['dbname']);

        // Check connection.
        if ($connection->connect_error) {
            $logHTML.="Connection failed<br>$connection->connect_error";
        } else {

            // Form SQL query
            $sql = "SELECT logs.id AS ID, members.id AS MID, CONCAT(members.firstname, ' ', members.lastname) AS Member, readers.id AS RID, readers.reader_name AS Reader, DATE_FORMAT(logs.access_date, '%e/%m/%Y at %r') AS Date
            FROM ((logs
            INNER JOIN members ON logs.member_id = members.id)
            INNER JOIN readers ON logs.reader_id = readers.id)";

            // Fetch each line and display in table.
            if ($result = mysqli_query($connection, $sql)) {
                while ($row = mysqli_fetch_row($result)) {
                    $logHTML.= "<tr>";
                    $logHTML.= "<td>".$row[0]."</td>";
                    $logHTML.= "<td>".$row[1]."</td>";
                    $logHTML.= "<td>".$row[2]."</td>";
                    $logHTML.= "<td>".$row[3]."</td>";
                    $logHTML.= "<td>".$row[4]."</td>";
                    $logHTML.= "<td>".$row[5]."</td>";
                    $logHTML.= "</tr>";
                }
            } else {
                $logHTML.="There was an error getting log information from the database.";
            }
        }
        // Close the connection
        $connection->close();
        
        // Return table or message
        return $logHTML;
    }
    function searchLogEntries($searchq) {
        $logHTML = "";

        // Create connection
        $connection = new mysqli($GLOBALS['server'], $GLOBALS['user'], $GLOBALS['pass'], $GLOBALS['dbname']);

        // Check connection.
        if ($connection->connect_error) {
            $logHTML.="Connection failed<br>$connection->connect_error";
        } else {

            // Form SQL query
            $sql = "SELECT logs.id AS ID, members.id as MID, CONCAT(members.firstname, ' ', members.lastname) AS Member, readers.id AS RID, readers.reader_name AS Reader, DATE_FORMAT(logs.access_date, '%e/%m/%Y at %r') AS Date 
            FROM ((logs 
            INNER JOIN members ON logs.member_id = members.id) 
            INNER JOIN readers ON logs.reader_id = readers.id) 
            WHERE logs.id = '$searchq'
            OR logs.member_id = '$searchq'
            OR logs.reader_id = '$searchq'
            OR members.firstname LIKE '%$searchq%'
            OR members.lastname LIKE '%$searchq%'
            OR readers.reader_name LIKE '%$searchq%'
            OR DATE_FORMAT(logs.access_date, '%r %T %e %m %M %Y') LIKE '%$searchq%'";

            // Fetch each line and display in table.
            if ($result = mysqli_query($connection, $sql)) {
                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_row($result)) {
                        $logHTML.= "<tr>";
                        $logHTML.= "<td>".$row[0]."</td>";
                        $logHTML.= "<td>".$row[1]."</td>";
                        $logHTML.= "<td>".$row[2]."</td>";
                        $logHTML.= "<td>".$row[3]."</td>";
                        $logHTML.= "<td>".$row[4]."</td>";
                        $logHTML.= "<td>".$row[5]."</td>";
                        $logHTML.= "</tr>";
                    }
                } else {
                    $logHTML.= "There were no results.";
                }
            } else {
                $logHTML.="There was an error getting log information from the database. ".mysqli_error($connection);
            }
        }
        // Close the connection
        $connection->close();
        
        // Return table or message
        return $logHTML;
    }
    function getCheckinEntries() {
        $logHTML = "";

        // Create connection
        $connection = new mysqli($GLOBALS['server'], $GLOBALS['user'], $GLOBALS['pass'], $GLOBALS['dbname']);

        // Check connection.
        if ($connection->connect_error) {
            $logHTML.="Connection failed<br>$connection->connect_error";
        } else {

            // Form SQL query
            $sql = "SELECT logs.id AS ID, members.id AS MID, CONCAT(members.firstname, ' ', members.lastname) AS Member, readers.id AS RID, readers.reader_name AS Reader, DATE_FORMAT(logs.access_date, '%e/%m/%Y at %r') AS Date
            FROM ((logs
            INNER JOIN members ON logs.member_id = members.id)
            INNER JOIN readers ON logs.reader_id = readers.id)";

            // Fetch each line and display in table.
            if ($result = mysqli_query($connection, $sql)) {
                while ($row = mysqli_fetch_row($result)) {
                    $logHTML.= "<tr>";
                    $logHTML.= "<td>".$row[0]."</td>";
                    $logHTML.= "<td>".$row[1]."</td>";
                    $logHTML.= "<td>".$row[2]."</td>";
                    $logHTML.= "<td>".$row[3]."</td>";
                    $logHTML.= "<td>".$row[4]."</td>";
                    $logHTML.= "<td>".$row[5]."</td>";
                    $logHTML.= "</tr>";
                }
            } else {
                $logHTML.="There was an error getting log information from the database.";
            }
        }
        // Close the connection
        $connection->close();
        
        // Return table or message
        return $logHTML;  
    }

    //// PRIVILEDGE TABLE FUNCTIONALITY //// 
    // Functions to include
    // addPriviledge($memberid,$readerid,$readergroup)
    // removePriviledge($id)
    // modifyPriviledge($id,$memberid,$readerid,$readergroup)
    // listPriviledges()
    function checkPrivilege($readerid, $key) {
        $keyhash = hash('sha512', $key);
        
        // Create connection
        $connection = new mysqli($GLOBALS['server'], $GLOBALS['user'], $GLOBALS['pass'], $GLOBALS['dbname']);
                
        // Check connection and return status
        if ($connection->connect_error) {
            die('Connection failed:<br>'.$connection->connect_error);
        }

        // Find any results for given member and reader combination in the privilege table
        $sql = "SELECT privilege.id FROM ((privilege INNER JOIN members ON privilege.member_id = members.id) INNER JOIN readers ON privilege.reader_id = readers.id) WHERE readers.id = $readerid AND members.cardkey = '$keyhash'";

        if (!($result = mysqli_query($connection, $sql))) {
            die(mysqli_error($connection));
        }

        // Return true or false
        if (mysqli_num_rows($result) > 0) {
            return true;
        } else {
            return false;
        }
    }

    //// READER TABLE FUNCTIONALITY //// 
    // Functions to include
    // addReader($name,$group,$timeout)
    // approveReader($id)
    // updateReader($name,$group,$timeout)
    // removeReader($readerid)
    // listReaders()
    // searchReaders($searchq)

    

}
?>