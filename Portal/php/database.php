<?php
include 'sqlcreds.php';

class Database {

    protected $_dbconn;
    private $_host;
    private $_username;
    private $_password;
    private $_database;
    private $_connsuccess = true;
    
    function __construct($dbhost = "", $dbname = "", $dbuser = "", $dbpass = "") {
        // Set connection variables
        if ($dbhost === "" && $dbname === "" && $dbuser === "" && $dbpass === "") {
            $this->_host = $GLOBALS['server'];
            $this->_database = $GLOBALS['dbname'];
            $this->_username = $GLOBALS['user'];
            $this->_password = $GLOBALS['pass'];
        } else {
            $this->_host = $dbhost;
            $this->_database = $dbname;
            $this->_username = $dbuser;
            $this->_password = $dbpass;
        }

        // Create PDO connection
        try {
            $this->_dbconn = new PDO("mysql:host=".$this->_host.";dbname=".$this->_database, $this->_username, $this->_password);
        } catch (PDOException $e) {
            $_connsuccess = false;
            return $_connsuccess;
        }
    }

    function __destruct() {
        $this->_dbconn = null;
    }

    //// GENERAL FUNCTIONALITY //// 
    // Create the tables.
    function createTables() {
        // Create the members table upon success
        $sql = "CREATE TABLE IF NOT EXISTS admins (
            id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
            firstname VARCHAR(30) NOT NULL,
            lastname VARCHAR(30) NOT NULL,
            email VARCHAR(50) NOT NULL,
            phone VARCHAR(10),
            passhash VARCHAR(128) NOT NULL
        ); CREATE TABLE IF NOT EXISTS members (
            id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
            firstname VARCHAR(30) NOT NULL,
            lastname VARCHAR(30) NOT NULL,
            email VARCHAR(50),
            phone VARCHAR(10),
            cardkey VARCHAR(128) NOT NULL
        ); CREATE TABLE IF NOT EXISTS privilege (
            id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
            member_id INT(6) NOT NULL,
            reader_id INT(6),
            reader_group INT(6)
        ); CREATE TABLE IF NOT EXISTS readers (
            id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
            reader_name VARCHAR(30) NOT NULL,
            reader_group INT(6) NOT NULL,
            signature VARCHAR(60) NOT NULL,
            approved BOOLEAN NOT NULL
        ); CREATE TABLE IF NOT EXISTS logs (
            id INT(10) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
            member_id INT(6) NOT NULL,
            reader_id INT(6) NOT NULL,
            access_date TIMESTAMP NOT NULL,
            check_in BOOL DEFAULT false
        )";
            
        try {
            $this->_dbconn->exec($sql);
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }

    // Test database connection.
    // DEPRECIATED:
    // Tests in __construct
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
    function listAdmins($userid, $searchq = "") {
        // Store output
        $output = "";
        
        $rowCount = 0;
        if ($searchq === "") {
            // Get number of rows
            $rowCount = $this->_dbconn->query("SELECT count(*) FROM admins")->fetchColumn();
            
            // Execute query
            $sql = "SELECT id, firstname, lastname, email, phone FROM admins";
            $stmt = $this->_dbconn->prepare($sql);
            $stmt->execute();
        } else {
            $params = array(':search' => $searchq, ':searchlike' => '%'.$searchq.'%');
            // Get number of rows
            $sql = "SELECT count(*) 
                    FROM admins
                    WHERE id = :search
                    OR firstname LIKE :searchlike
                    OR lastname LIKE :searchlike
                    OR email LIKE :searchlike
                    OR phone LIKE :searchlike";
            $stmt = $this->_dbconn->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
            $stmt->execute($params);
            $rowCount = $stmt->fetchColumn();
            
            // Execute query
            $sql = "SELECT id, firstname, lastname, email, phone 
                    FROM admins
                    WHERE id = :search
                    OR firstname LIKE :searchlike
                    OR lastname LIKE :searchlike
                    OR email LIKE :searchlike
                    OR phone LIKE :searchlike";
            
            $stmt = $this->_dbconn->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
            $stmt->execute($params);
        }

        // Create a table with any results and print to page
        if ($rowCount > 0) {
            // Create table
            $output.= '<table id="list-table"><tr><th>ID</th><th>Firstname</th><th>Lastname</th><th>Email</th><th>Phone Number</th><th>Update</th><th>Delete</th></tr>';

            // Fetch table rows
            $id = $fname = $lname = $email = $phone = '';
            while ($row=$stmt->fetch()) {
                $id = $row['id'];
                $fname = $row['firstname'];
                $lname = $row['lastname'];
                $email = $row['email'];
                $phone = $row['phone'];
                
                // Replace and remove the delete button functionality for the key admin.
                if ($id == 1) { $output.= "<tr><td>$id</td><td>$fname</td><td>$lname</td><td>$email</td><td>$phone</td><td><a href=\"updateadmin.php?id=".$id."\"><i class=\"fas fa-sync fa-lg\"></i></a></td><td><span title=\"You cannot delete the primary admin account.\"><i class=\"fa fa-key fa-lg\"></i></span></td></tr>"; }
                else if ($id == $userid) { $output.= "<tr><td>$id</td><td>$fname</td><td>$lname</td><td>$email</td><td>$phone</td><td><a href=\"updateadmin.php?id=".$id."\"><i class=\"fas fa-sync fa-lg\"></i></a></td><td><span title=\"You cannot delete the primary admin account.\"><i class=\"fa fa-ban fa-lg\"></i></span></td></tr>"; }
                else { $output.= "<tr><td>$id</td><td>$fname</td><td>$lname</td><td>$email</td><td>$phone</td><td><a href=\"updateadmin.php?id=".$id."\"><i class=\"fas fa-sync fa-lg\"></i></a></td><td><a href=\"../php/deleteuser.php?table=admin&id=".$row[0]."\"><i class=\"fas fa-trash fa-lg\"></i></a></td></tr>"; }
            }

            // Close table tag
            $output.= '</table>';
        } else {
            if ($searchq === "") {
                $output.= "There were no results.";
            } else {
                $output.= "There were no results for $searchq";
            }
        }

        echo $output;
    }
    
    // Search the admins table.
    // DEPRECIATED
    function searchAdmins($searchq) {
        // $formattedsearchq = strtolower(htmlspecialchars($searchq));
        
        // // Create connection
        // $connection = new mysqli($GLOBALS['server'], $GLOBALS['user'], $GLOBALS['pass'], $GLOBALS['dbname']);
                
        // // Check connection
        // if ($connection->connect_error) {
        //     die("Connection failed<br>$connection->connect_error");
        // }

        // // Form SQL query
        

        // // Fetch each line and display in table
        // if ($result = mysqli_query($connection, $sql)) {
        //     if (mysqli_num_rows($result) === 0) {
        //         echo "The admins table contains no match for the search: <b>$searchq</b><br>";
        //     } else {
        //         echo "Found ".mysqli_num_rows($result)." results for $searchq<br>";
        //         while ($row=mysqli_fetch_row($result)) {
        //             // Replace and remove the delete button functionality for the key admin.
        //             if ($row[0] == 1) { echo str_replace($searchq, "<b>$searchq</b>","<tr><td>$row[0]</td><td>$row[1]</td><td>$row[2]</td><td>$row[3]</td><td>$row[4]</td><td><a href=\"updateadmin.php?id=".$row[0]."\"><i class=\"fas fa-sync fa-lg\"></i></a></td><td><span title=\"You cannot delete the primary admin account.\"><i class=\"fa fa-key fa-lg\"></i></span></td></tr>"); }
        //             else { echo str_replace($searchq, "<b>$searchq</b>","<tr><td>$row[0]</td><td>$row[1]</td><td>$row[2]</td><td>$row[3]</td><td>$row[4]</td><td><a href=\"updateadmin.php?id=".$row[0]."\"><i class=\"fas fa-sync fa-lg\"></i></a></td><td><a href=\"../php/deleteuser.php?table=admin&id=".$row[0]."\"><i class=\"fas fa-trash fa-lg\"></i></a></td></tr>"); }
        //         }
        //     }
        //     mysqli_free_result($result);
        // } else {
        //     die("There was an error searching the database:<br>$connection->error<br>");
        // }

        // // Close the connection
        // $connection->close();
    }

    // Attempt to login with a given email and password
    function loginAdmin($email, $password) {
        // Login response
        //[0] = False on failure || True on success.
        //[1] = Error description on failure.
        //['id'] = Admin's ID on success
        //['fname'] = Admin's first name on success
        $loginResponse[0] = false;

        // Hash password
        $passhash = hash("sha512", $password);

        // Set query parameters
        $params = array(':email'=>$email,':passhash'=>$passhash);

        // Get number of results
        $sql = "SELECT count(*) FROM admins WHERE email = :email AND passhash = :passhash";
        $stmt = $this->_dbconn->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $stmt->execute($params);
        $rowCount = $stmt->fetchColumn();

        // Store information
        if ($rowCount > 0) {
            // Get admin info
            $sql = "SELECT id, firstname FROM admins WHERE email = :email AND passhash = :passhash";
            $stmt = $this->_dbconn->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
            $stmt->execute($params);

            $info = $stmt->fetch();
            $loginResponse[0] = true;
            $loginResponse['id'] = $info['id'];
            $loginResponse['fname'] = $info['firstname'];
        } else {
            $loginResponse[1] = 'Email or password was incorrect.';
        }

        // Return login response
        return $loginResponse;
    }
    
    // Fetch information for a given admin
    function fetchAdminInfo($adminid) {
        // Set query parameters
        $params = array(':id'=> $adminid);

        // Execute the SQL query
        $sql = "SELECT firstname, lastname, email, phone FROM admins WHERE id = :id";
        $stmt = $this->_dbconn->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $stmt->execute($params);

        // Format raw data to return
        $rawData = $stmt->fetch();
        $userInfo = array('fname'=>$rawData['firstname'], 'lname'=>$rawData['lastname'], 'email'=>$rawData['email'], 'phone'=>$rawData['phone']);

        // Return user info
        return $userInfo;
    }

    // Add an admin to the admins table.
    function addAdmin($firstname, $lastname, $email, $phone="", $password) {
        // Encrypt the admin password before inserting into the database
        $passhash = hash("sha512", $password);

        // Set query parameters
        $params = array(':firstname' => $firstname, ':lastname' => $lastname, ':email' => $email, ':phone'=> $phone, ':passhash' => $passhash);

        // Execute query
        $sql = "INSERT INTO admins (firstname, lastname, email, phone, passhash) 
                VALUES (:firstname, :lastname, :email, :phone, :passhash)";
        $stmt = $this->_dbconn->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $stmt->execute($params);
    }

    // Update an admin in the admins table.
    function updateAdmin($adminid, $firstname, $lastname, $email, $phone, $pass) {
        // Encrypt the card key before inseting into the database if set
        $passhash = "";
        if ($pass != "") {
            $passhash = hash("sha512", $pass);
        }

        // Form query
        if ($passhash === "") {
            $params = array(':firstname'=> $firstname, ':lastname'=> $lastname, ':email'=> $email, ':phone'=> $phone, ':id'=> $adminid);
            $sql = "UPDATE admins SET firstname = :firstname, lastname = :lastname, email = :email, phone = :phone WHERE id = :id";
        } else {
            $params = array(':firstname'=> $firstname, ':lastname'=> $lastname, ':email'=> $email, ':phone'=> $phone, ':passhash'=> $passhash, ':id'=> $adminid);
            $sql = "UPDATE admins SET firstname = :firstname, lastname = :lastname, email = :email, phone = :phone, passhash = :passhash WHERE id = :id";
        }
        // Execute query
        $stmt = $this->_dbconn->prepare($sql);
        $stmt->execute($params);
    }

    // Delete an admin from the admins table.
    function removeAdmin($adminid) {
        // Prevent deletion of key admin account.
        if ($adminid > 1) { 
            // Set query parameters
            $params = array(':id' => $adminid);

            // Execute query
            $sql = "DELETE FROM admins WHERE id = :id";
            $stmt = $this->_dbconn->prepare($sql);
            $stmt->execute($params);
        }
    }

    //// MEMBERS TABLE FUNCTIONALITY //// 
    // List all members in the members table.
    function fetchMemberInfo($memberid) {
        // Set query parameters
        $params = array(':id'=> $memberid);

        // Execute the SQL query
        $sql = "SELECT firstname, lastname, email, phone FROM members WHERE id = :id";
        $stmt = $this->_dbconn->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $stmt->execute($params);

        // Format raw data to return
        $rawData = $stmt->fetch();
        $userInfo = array('fname'=>$rawData['firstname'], 'lname'=>$rawData['lastname'], 'email'=>$rawData['email'], 'phone'=>$rawData['phone']);

        // Return user info
        return $userInfo;
    }

    // List all members
    function listMembers($searchq = '') {
       // Store output
       $output = "";
        
       $rowCount = 0;
       if ($searchq === "") {
           // Get number of rows
           $rowCount = $this->_dbconn->query("SELECT count(*) FROM members")->fetchColumn();
           
           // Execute query
           $sql = "SELECT id, firstname, lastname, email, phone FROM members";
           $stmt = $this->_dbconn->prepare($sql);
           $stmt->execute();
       } else {
           $params = array(':search' => $searchq, ':searchlike' => '%'.$searchq.'%');
           // Get number of rows
           $sql = "SELECT count(*) 
                   FROM members
                   WHERE id = :search
                   OR firstname LIKE :searchlike
                   OR lastname LIKE :searchlike
                   OR email LIKE :searchlike
                   OR phone LIKE :searchlike";
           $stmt = $this->_dbconn->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
           $stmt->execute($params);
           $rowCount = $stmt->fetchColumn();
           
           // Execute query
           $sql = "SELECT id, firstname, lastname, email, phone 
                   FROM members
                   WHERE id = :search
                   OR firstname LIKE :searchlike
                   OR lastname LIKE :searchlike
                   OR email LIKE :searchlike
                   OR phone LIKE :searchlike";
           
           $stmt = $this->_dbconn->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
           $stmt->execute($params);
       }

       // Create a table with any results and print to page
       if ($rowCount > 0) {
           // Create table
           $output.= '<table id="list-table"><tr><th>ID</th><th>Firstname</th><th>Lastname</th><th>Email</th><th>Phone Number</th><th>Update</th><th>Delete</th></tr>';

           // Fetch table rows
           $id = $fname = $lname = $email = $phone = '';
           while ($row=$stmt->fetch()) {
               $id = $row['id'];
               $fname = $row['firstname'];
               $lname = $row['lastname'];
               $email = $row['email'];
               $phone = $row['phone'];
               
               // Replace and remove the delete button functionality for the key admin.
               $output.= "<tr><td>$id</td><td>$fname</td><td>$lname</td><td>$email</td><td>$phone</td><td><a href=\"updatemember.php?id=".$id."\"><i class=\"fas fa-sync fa-lg\"></i></a></td><td><a href=\"../php/deleteuser.php?table=member&id=".$row[0]."\"><i class=\"fas fa-trash fa-lg\"></i></a></td></tr>";
           }

           // Close table tag
           $output.= '</table>';
       } else {
           if ($searchq === "") {
               $output.= "There were no results.";
           } else {
               $output.= "There were no results for $searchq";
           }
       }

       echo $output;
    }

    // Search the members table
    // DEPRECIATED
    function searchMembers($searchq) {
        // $formattedsearchq = strtolower(htmlspecialchars($searchq));
        
        // // Create connection
        // $connection = new mysqli($GLOBALS['server'], $GLOBALS['user'], $GLOBALS['pass'], $GLOBALS['dbname']);
                
        // // Check connection
        // if ($connection->connect_error) {
        //     die("Connection failed<br>$connection->connect_error");
        // }

        // // Form SQL query
        // $sql = "SELECT * FROM members
        // WHERE id LIKE '%$formattedsearchq%'
        // OR LOWER(firstname) LIKE '%$formattedsearchq%'
        // OR LOWER(lastname) LIKE '%$formattedsearchq%'
        // OR LOWER(email) LIKE '%$formattedsearchq%'
        // OR LOWER(phone) LIKE '%$formattedsearchq%'";

        // // Fetch each line and display in table
        // if ($result = mysqli_query($connection, $sql)) {
        //     if (mysqli_num_rows($result) === 0) {
        //         echo "The members table contains no match for the search: <b>$searchq</b><br>";
        //     } else {
        //         echo "Found ".mysqli_num_rows($result)." results for $searchq<br>";
        //         while ($row=mysqli_fetch_row($result)) {
        //             echo str_replace($searchq, "<b>$searchq</b>","<tr><td>$row[0]</td><td>$row[1]</td><td>$row[2]</td><td>$row[3]</td><td>$row[4]</td><td><a href=\"updatemember.php?id=".$row[0]."\"><i class=\"fas fa-sync fa-lg\"></i></a></td><td><a href=\"../php/deleteuser.php?table=member&id=".$row[0]."\"><i class=\"fas fa-trash fa-lg\"></i></a></td></tr>");
        //         }
        //     }
        //     mysqli_free_result($result);
        // } else {
        //     die("There was an error searching the database:<br>$connection->error<br>");
        // }

        // // Close the connection
        // $connection->close();
    }

    // Add a member to the members table.
    function addMember($firstname, $lastname, $email="", $phone="", $cardkey) {
        // Encrypt the card key before inserting into the database
        $cardkeyhash = hash("sha512", $cardkey);

        // Set query parameters
        $params = array(':firstname' => $firstname, ':lastname' => $lastname, ':email' => $email, ':phone'=> $phone, ':keyhash' => $cardkeyhash);

        // Execute query
        $sql = "INSERT INTO members (firstname, lastname, email, phone, cardkey) 
                VALUES (:firstname, :lastname, :email, :phone, :keyhash)";
        $stmt = $this->_dbconn->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $stmt->execute($params);
    }

    // Update a member in the members table.
    function updateMember($memberid = "", $firstname = "", $lastname = "", $email = "", $phone = "", $cardkey = "") {
        // Encrypt the card key before inseting into the database if set
        $cardkeyhash = "";
        if ($cardkey != "") {
            $cardkeyhash = hash("sha512", $cardkey);
        }

        // Form query
        if ($cardkeyhash === "") {
            $params = array(':firstname'=> $firstname, ':lastname'=> $lastname, ':email'=> $email, ':phone'=> $phone, ':id'=> $memberid);
            $sql = "UPDATE members SET firstname = :firstname, lastname = :lastname, email = :email, phone = :phone WHERE id = :id";
        } else {
            $params = array(':firstname'=> $firstname, ':lastname'=> $lastname, ':email'=> $email, ':phone'=> $phone, ':keyhash'=> $cardkeyhash, ':id'=> $memberid);
            $sql = "UPDATE members SET firstname = :firstname, lastname = :lastname, email = :email, phone = :phone, cardkey = :keyhash WHERE id = :id";
        }
        // Execute query
        $stmt = $this->_dbconn->prepare($sql);
        $stmt->execute($params);
    }
    
    // Delete a member from the members table.
    function removeMember($memberid) {
        // Set query parameters
        $params = array(':id' => $memberid);

        // Execute query
        $sql = "DELETE FROM members WHERE id = :id";
        $stmt = $this->_dbconn->prepare($sql);
        $stmt->execute($params);
    }

    //// LOG TABLE FUNCTIONALITY //// 
    // Add a new entry to the Log
    function addLogEntry($signature, $key, $checkin=0) {
        $keyhash = hash('sha512', $key);

        $return = false;

        // Get member id from key
        $memberid;
        $params = array(':cardkeyhash' => $keyhash);
        $sql = "SELECT id FROM members WHERE cardkey = :cardkeyhash";
        $stmt = $this->_dbconn->prepare($sql);
        $stmt->execute($params);
        if ($memberid = $stmt->fetchColumn()) {
            // Get reader id from signature
            $readerid;
            $params = array(':signature' => $signature);
            $sql = "SELECT id FROM readers WHERE signature = :signature";
            $stmt = $this->_dbconn->prepare($sql);
            $stmt->execute($params);
            if ($readerid = $stmt->fetchColumn()) {
                
                // Add log entry
                $params = array(':memberid' => $memberid, ':readerid' => $readerid, ':checkin' => $checkin);
                $sql = "INSERT INTO logs (member_id, reader_id, check_in) VALUES (:memberid, :readerid, :checkin)";
                $stmt = $this->_dbconn->prepare($sql);
                if ($stmt->execute($params)) {
                    $return = true;
                }
            }
        }

        return $return;
    }
    
    // Get all entries in the logs table
    function getLogEntries($searchq = '') {
        // Store output
        $output = "";

        $rowCount = 0;
        if ($searchq === '') {
            // Get number of rows
            $rowCount = $this->_dbconn->query("SELECT count(*)
            FROM ((logs
            INNER JOIN members ON logs.member_id = members.id)
            INNER JOIN readers ON logs.reader_id = readers.id)
            WHERE logs.check_in = 0
            ORDER BY logs.access_date DESC")->fetchColumn();

            // Execute query
            $sql = "SELECT logs.id AS ID, members.id AS MID, CONCAT(members.firstname, ' ', members.lastname) AS Member, readers.id AS RID, readers.reader_name AS Reader, DATE_FORMAT(logs.access_date, '%e/%m/%Y at %r') AS Date
            FROM ((logs
            INNER JOIN members ON logs.member_id = members.id)
            INNER JOIN readers ON logs.reader_id = readers.id)
            WHERE logs.check_in = 0
            ORDER BY logs.access_date DESC";
            $stmt = $this->_dbconn->prepare($sql);
            $stmt->execute();
        } else {
            // Set query parameters
            $params = array(':search' => $searchq, ':searchlike' => '%'.$searchq.'%');
            
            // Get number of rows
            $sql = "SELECT count(*) 
            FROM ((logs 
            INNER JOIN members ON logs.member_id = members.id) 
            INNER JOIN readers ON logs.reader_id = readers.id) 
            WHERE logs.check_in = 0
            AND (logs.member_id = :search
            OR logs.reader_id = :search
            OR members.firstname LIKE :searchlike
            OR members.lastname LIKE :searchlike
            OR readers.reader_name LIKE :searchlike)
            ORDER BY logs.access_date DESC";
            $stmt = $this->_dbconn->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
            $stmt->execute($params);
            $rowCount = $stmt->fetchColumn();

            // Execute query
            $sql = "SELECT logs.id AS ID, members.id as MID, CONCAT(members.firstname, ' ', members.lastname) AS Member, readers.id AS RID, readers.reader_name AS Reader, DATE_FORMAT(logs.access_date, '%e/%m/%Y at %r') AS Date 
            FROM ((logs 
            INNER JOIN members ON logs.member_id = members.id) 
            INNER JOIN readers ON logs.reader_id = readers.id) 
            WHERE logs.check_in = 0
            AND (logs.member_id = :search
            OR logs.reader_id = :search
            OR members.firstname LIKE :searchlike
            OR members.lastname LIKE :searchlike
            OR readers.reader_name LIKE :searchlike)
            ORDER BY logs.access_date DESC";
            $stmt = $this->_dbconn->prepare($sql);
            $stmt->execute($params);
        }

        // Fetch table rows
        $id = $mid = $member = $rid = $reader = $date = '';
        if ($rowCount > 0) {
            // Create table
            $output.= '<table id="list-table"><tr><th>#</th><th>Member ID</th><th>Member</th><th>Reader ID</th><th>Reader</th><th>Date & Time</th></tr>';

            while ($row = $stmt->fetch()) {
                $id = $row['ID'];
                $mid = $row['MID'];
                $member = $row['Member'];
                $rid = $row['RID'];
                $reader = $row['Reader'];
                $date = $row['Date'];

                $output.= "<tr>
                            <td>$id</td>
                            <td>$mid</td>
                            <td>$member</td>
                            <td>$rid</td>
                            <td>$reader</td>
                            <td>$date</td>
                           </tr>";
            }
            $output.= "</table>";
        } else {
            if ($searchq === "") {
                $output.= "There were no results.";
            } else {
                $output.= "There were no results for $searchq";
            }
        }

        echo $output;
    }
    
    // Search the logs table
    // DEPRECIATED
    function searchLogEntries($searchq) {
        // $logHTML = "";

        // // Create connection
        // $connection = new mysqli($GLOBALS['server'], $GLOBALS['user'], $GLOBALS['pass'], $GLOBALS['dbname']);

        // // Check connection.
        // if ($connection->connect_error) {
        //     $logHTML.="Connection failed<br>$connection->connect_error";
        // } else {

        //     // Form SQL query
        //     $sql = "SELECT logs.id AS ID, members.id as MID, CONCAT(members.firstname, ' ', members.lastname) AS Member, readers.id AS RID, readers.reader_name AS Reader, DATE_FORMAT(logs.access_date, '%e/%m/%Y at %r') AS Date 
        //     FROM ((logs 
        //     INNER JOIN members ON logs.member_id = members.id) 
        //     INNER JOIN readers ON logs.reader_id = readers.id) 
        //     WHERE logs.check_in = 0
        //     AND (logs.id = '$searchq'
        //     OR logs.member_id = '$searchq'
        //     OR logs.reader_id = '$searchq'
        //     OR members.firstname LIKE '%$searchq%'
        //     OR members.lastname LIKE '%$searchq%'
        //     OR readers.reader_name LIKE '%$searchq%'
        //     XOR DATE_FORMAT(logs.access_date, '%Y-%m-%e') = '$searchq'
        //     XOR DATE_FORMAT(logs.access_date, '%e-%m-%Y %r') LIKE '%$searchq%'
        //     XOR DATE_FORMAT(logs.access_date, '%e-%m-%Y %T') LIKE '%$searchq%'
        //     XOR DATE_FORMAT(logs.access_date, '%e-%m-%y %r') LIKE '%$searchq%'
        //     XOR DATE_FORMAT(logs.access_date, '%e-%m-%Y %T') LIKE '%$searchq%'
        //     XOR DATE_FORMAT(logs.access_date, '%r %T %e %m %M %Y') LIKE '%$searchq%')
        //     ORDER BY logs.access_date DESC";

        //     // Fetch each line and display in table.
        //     if ($result = mysqli_query($connection, $sql)) {
        //         if (mysqli_num_rows($result) > 0) {
        //             while ($row = mysqli_fetch_row($result)) {
        //                 $logHTML.= "<tr>";
        //                 $logHTML.= "<td>".$row[0]."</td>";
        //                 $logHTML.= "<td>".$row[1]."</td>";
        //                 $logHTML.= "<td>".$row[2]."</td>";
        //                 $logHTML.= "<td>".$row[3]."</td>";
        //                 $logHTML.= "<td>".$row[4]."</td>";
        //                 $logHTML.= "<td>".$row[5]."</td>";
        //                 $logHTML.= "</tr>";
        //             }
        //         } else {
        //             $logHTML.= "There were no results.";
        //         }
        //     } else {
        //         $logHTML.="There was an error getting log information from the database: ".mysqli_error($connection);
        //     }
        // }
        // // Close the connection
        // $connection->close();
        
        // // Return table or message
        // return $logHTML;
    }

    // Display a list of check-ins
    function getCheckinEntries() {
        $output = "";
        
        // Get number of rows
        $rowCount;
        $sql = "SELECT count(*)
        FROM ((logs
        INNER JOIN members ON logs.member_id = members.id)
        INNER JOIN (SELECT member_id, MAX(access_date) as visit_date FROM logs WHERE check_in = 1 GROUP BY logs.member_id) last_visit ON logs.member_id = last_visit.member_id)
        WHERE logs.check_in = 1
        GROUP BY logs.member_id";
        $stmt = $this->_dbconn->prepare($sql);
        $stmt->execute();
        $rowCount = $stmt->fetchColumn();

        // Execute query
        $sql = "SELECT 
        members.id AS MID, 
        CONCAT(members.firstname, ' ', members.lastname) AS Member,
        FLOOR(count(check_in) / 2) AS Checkins,
        CASE
            when count(logs.check_in) MOD 2 = 0 then 0
            when count(logs.check_in) MOD 2 = 1 then 1
        END AS Active,
        DATE_FORMAT(last_visit.visit_date, '%e/%m/%Y at %r') AS LastCheckin,
        TIMESTAMPDIFF(HOUR, last_visit.visit_date, NOW()) AS TimeSince
        FROM ((logs
        INNER JOIN members ON logs.member_id = members.id)
        INNER JOIN (SELECT member_id, MAX(access_date) as visit_date FROM logs WHERE check_in = 1 GROUP BY logs.member_id) last_visit ON logs.member_id = last_visit.member_id)
        WHERE logs.check_in = 1
        GROUP BY logs.member_id
        ORDER BY Active DESC";
        $stmt = $this->_dbconn->prepare($sql);
        $stmt->execute();

        if ($rowCount > 0) {

            // Create table
            $output.= '<table id="list-table"><tr><th>Member ID</th><th>Member</th><th># of Visits</th><th>Currently Active</th><th>Last Activity</th><th>Last Visit</th></tr>';      

            // Get table rows
            $memberid = $membername = $numofvisits = $active = $lastactivity = $lastvisit = '';
            while ($row = $stmt->fetch()) {
                $memberid = $row['MID'];
                $membername = $row['Member'];
                $numofvisits = $row['Checkins'];
                $lastactivity = $row['TimeSince'];
                $lastvisit = $row['LastCheckin'];

                $rowStyle = "";
                $active;
                // Adjust table row colour and active status based on check-in trends.
                if ($row['Active'] == 1 && $lastactivity < 12) {
                    $active = "YES";
                    $rowStyle = 'style="background-color:rgba(0,100,0,0.7)"';
                } else if ($row['Active'] == 1 && $lastactivity > 12) {
                    $active = "MAYBE";
                    $rowStyle = 'style="background-color:rgba(125,100,0,0.6)"';
                } else {
                    $active = "NO";
                    $rowStyle = 'style="background-color: rgba(100,0,0,0.2)"';
                }

                // Format last activity text
                if ($lastactivity > 1) {
                    $lastactivity.= ' hours ago';
                } else if ($lastactivity == 1) {
                    $lastactivity.= ' hour ago';
                } else {
                    $lastactivity = '<1 hour ago';
                }

                // Form table row
                $output.= "<tr $rowStyle>";
                $output.= "<td>$memberid</td><td>$membername</td><td>$numofvisits</td><td>$active</td><td>$lastactivity</td><td>$lastvisit</td>";
                $output.= "</tr>";
            }
            $output.= "</table>";
        } else {
            echo "There have been no check-ins.";
        }

        // Print output
        echo $output; 
    }

    //// PRIVILEDGE TABLE FUNCTIONALITY //// 
    // Add member privilege
    function addPrivilege($memberid, $readerid, $readergroup = "") {
        // Check table for existing entries
        $rowCount;
        $params = array(':memberid' => $memberid, ':readerid' => $readerid);
        $sql = "SELECT count(*) FROM privilege WHERE member_id = :memberid AND reader_id = :readerid";
        $stmt = $this->_dbconn->prepare($sql);
        $stmt->execute($params);
        $rowCount = $stmt->fetchColumn();

        // If there are no existing entries for this combination in the privilege table, create a new one.
        if ($rowCount == 0) {
            $sql = "INSERT INTO privilege(member_id, reader_id) VALUES (:memberid, :readerid)";
            $stmt = $this->_dbconn->prepare($sql);
            $stmt->execute($params);
        }
    }
    
    // List all members
    function listPrivilegeMembers() {
        $output = '';
        
        // Get row count
        $rowCount;
        $sql = "SELECT count(*) FROM members ORDER BY id";
        $stmt = $this->_dbconn->prepare($sql);
        $stmt->execute();
        $rowCount = $stmt->fetchColumn();
        
        if ($rowCount > 0) {
            // Execute query
            $sql = "SELECT id, CONCAT(firstname, ' ', lastname) AS MemberName FROM members ORDER BY id";
            $stmt = $this->_dbconn->prepare($sql);
            $stmt->execute();

            // Form table
            $output.= '<table id="list-table"><tr><th>Member ID</th><th>Name</th><th>Modify Access</th></tr>';

            // Fetch each line and display in table.
            $id = $membername = '';
            while ($row = $stmt->fetch()) {
                $id = $row['id'];
                $membername = $row['MemberName'];
                $output.= "<tr><td>$id</td><td>$membername</td><td><a href=\"listaccess.php?id=".$id."\"><i class=\"fas fa-edit fa-lg\"></i></a></td></tr>";
            }
        
            $output.= "</table>";
        } else {
            $output = "There are no members. Please add a member before assigning access.";
        }

        echo $output;
    }
    
    // List all privileges associated with a member
    function listMemberPrivilege($memberid) {
        $output = '';
        
        // Get row count
        $rowCount;
        $params = array(':id' => $memberid);
        $sql = "SELECT count(*)
        FROM (privilege 
        INNER JOIN readers ON privilege.reader_id = readers.id)
        WHERE privilege.member_id = :memberid";
        $stmt = $this->_dbconn->prepare($sql);
        $stmt->execute($params);
        $rowCount = $stmt->fetchColumn();
        
        if ($rowCount > 0) {
            // Execute query
            $sql = "SELECT privilege.id AS PID, readers.id AS RID, readers.reader_name AS ReaderName
            FROM (privilege 
            INNER JOIN readers ON privilege.reader_id = readers.id)
            WHERE privilege.member_id = :memberid";
            $stmt = $this->_dbconn->prepare($sql);
            $stmt->execute();

            // Form table
            $output.= '<table id="list-table"><tr><th>Member ID</th><th>Name</th><th>Modify Access</th></tr>';

            // Fetch each line and display in table.
            $pid = $rid = $readername = '';
            while ($row = $stmt->fetch()) {
                $pid = $row['PID'];
                $rid = $row['RID'];
                $readername = $row['ReaderName'];
                $output.= "<tr><td>$pid</td><td>$rid</td><td>$readername</td><td><td><a href=\"../php/deleteaccess.php?id=".$pid."&member=$memberid\"><i class=\"fas fa-minus-circle fa-lg\"></i></a></td></tr>";
            }
        
            $output.= "</table>";
        } else {
            $output = "This member hasn't yet been assigned any privileges. <br>Click the button below to assign one.";
        }

        echo $output;
    }
    
    // List all readers that can be assigned to a member
    function listPrivilegeReaders() {
        // Create connection
        $connection = new mysqli($GLOBALS['server'], $GLOBALS['user'], $GLOBALS['pass'], $GLOBALS['dbname']);
        
        // Check connection.
        if ($connection->connect_error) {
            die("Connection failed<br>$connection->connect_error");
        }

        // Form SQL query
        $sql = "SELECT id, reader_name FROM readers WHERE approved = 1";

        // Fetch each line and display in table.
        if ($result = mysqli_query($connection, $sql)) {
            if (mysqli_num_rows($result) === 0) {
                echo "There are no readers.<br>";
            } else {
                while ($row=mysqli_fetch_row($result)) {
                    echo "<tr>
                    <td>$row[0]</td>
                    <td>$row[1]</td>
                    <td><input type=\"checkbox\" name=\"".$row[0]."\" value=\"".$row[0]."\"></td>
                    </tr>";
                }
            }
            mysqli_free_result($result);
        } else {
            die("There was an error listing the data in the readers table:<br>$connection->error<br>");
        }

        // Close the connection
        $connection->close();
    }

    // Revoke a member's privilege
    function removePrivilege($id) {
        // Create connection
        $connection = new mysqli($GLOBALS['server'], $GLOBALS['user'], $GLOBALS['pass'], $GLOBALS['dbname']);
                        
        // Check connection.
        if ($connection->connect_error) {
            die("Connection failed<br>$connection->connect_error");
        }

        // Form SQL query
        $sql = "DELETE FROM privilege WHERE id = $id";

        // Remove the reader.
        if (!mysqli_query($connection, $sql)) {
            die("There was an error removing the reader from the database:<br>$connection->error<br>");
        }

        // Close the connection
        $connection->close();
    }

    // Check if member associated with key has been given access to the reader.
    function checkPrivilege($signature, $key) {
        $keyhash = hash('sha512', $key);
        
        $return = false;

        // Create connection
        $connection = new mysqli($GLOBALS['server'], $GLOBALS['user'], $GLOBALS['pass'], $GLOBALS['dbname']);
                
        // Check connection and return status
        if ($connection->connect_error) {
            die('Connection failed:<br>'.$connection->connect_error);
        }

        // Find any results for given member and reader combination in the privilege table
        $sql = "SELECT privilege.id FROM ((privilege INNER JOIN members ON privilege.member_id = members.id) INNER JOIN readers ON privilege.reader_id = readers.id) WHERE readers.signature = '$signature' AND readers.approved = 1 AND members.cardkey = '$keyhash'";

        if ($result = mysqli_query($connection, $sql)) {
            // Return true or false
            if (mysqli_num_rows($result) > 0) {
                $return = true;
            } else {
                $return = false;
            }
        } else {
            die(mysqli_error($connection));
        }

        $connection->close();

        return $return;
    }
    
    //// READER TABLE FUNCTIONALITY //// 
    // Update a reader
    function updateReader($readerid, $name = "", $group = "", $approved = "") {
        // Create connection
        $connection = new mysqli($GLOBALS['server'], $GLOBALS['user'], $GLOBALS['pass'], $GLOBALS['dbname']);
                
        // Check connection.
        if ($connection->connect_error) {
            die("Connection failed<br>$connection->connect_error");
        }

        // Form SQL query
        $sql = "";

        if ($name !== "") { $sql.= "UPDATE readers SET reader_name = '$name' WHERE id = $readerid;"; }
        if ($group !== "") { $sql.= "UPDATE readers SET reader_group = $group WHERE id = $readerid;"; }
        if ($approved !== "") { $sql.= "UPDATE readers SET approved = $approved WHERE id = $readerid;"; }

        // Update the reader.
        if (!mysqli_multi_query($connection, $sql)) {
            die("There was an error updating the reader:<br>$connection->error<br>");
        }

        // Close the connection
        $connection->close();
    }

    // Check if the reader is approved.
    function checkReaderApproved($id) {
        $return = false; 
        
        // Create connection
        $connection = new mysqli($GLOBALS['server'], $GLOBALS['user'], $GLOBALS['pass'], $GLOBALS['dbname']);
                
        // Check connection.
        if ($connection->connect_error) {
            die("Connection failed<br>$connection->connect_error");
        }
 
        // Form SQL query
        $sql = "SELECT * FROM readers WHERE approved = 0 AND signature = '$id' ORDER BY id";
         
        // Check if reader is in pending
        if ($result = mysqli_query($connection, $sql)) {
            if (mysqli_num_rows($result) === 0) {

                $sql = "SELECT * FROM readers WHERE approved = 1 AND signature = '$id' ORDER BY id";
                // Check if reader is in approved
                if ($result = mysqli_query($connection, $sql)) {
                    if (mysqli_num_rows($result) === 0) {
                        // Add to pending readers
                        $sql = "INSERT INTO readers(reader_name, reader_group, approved, signature) VALUES ('', 0, 0, '$id')";
                        if (!mysqli_query($connection, $sql)) {
                            die("There was an error adding the reader to pending. ".mysqli_error());
                        }
                    } else {
                        $return = true;
                    }
                } else {
                    die("Error accessing readers. ".mysqli_error());
                }
            }
        } else {
            die("There was an error running the query. ".mysqli_error());
        }
         
        $connection->close();
        return $return;
    }

    // Approve a reader
    function approveReader($id) {
        // Create connection
        $connection = new mysqli($GLOBALS['server'], $GLOBALS['user'], $GLOBALS['pass'], $GLOBALS['dbname']);
                        
        // Check connection.
        if ($connection->connect_error) {
            die("Connection failed<br>$connection->connect_error");
        }

        // Form SQL query
        $sql = "UPDATE readers SET approved = 1 WHERE id = $id";

        // Remove the reader.
        if (!mysqli_query($connection, $sql)) {
            die("There was an error approving the reader:<br>$connection->error<br>");
        }

        // Close the connection
        $connection->close();
    }

    // Get number of pending readers
    function getPendingCount() {
        $pendingCount = 0;
        
        // Create connection
        $connection = new mysqli($GLOBALS['server'], $GLOBALS['user'], $GLOBALS['pass'], $GLOBALS['dbname']);
                
        // Check connection.
        if ($connection->connect_error) {
            die("Connection failed<br>$connection->connect_error");
        }

        // Form SQL query
        $sql = "SELECT count(*) FROM readers WHERE approved = 0";

        // Get number of pending readers
        if ($result = mysqli_query($connection, $sql)) {
            $pendingCount = mysqli_fetch_row($result)[0][0];
        } else {
            die("Failed to get pending count");
        }

        // Close connection
        $connection->close();

        // Return number of pending readers
        return $pendingCount;
    }

    // Get reader information
    function fetchReaderInfo($readerid) {
        // Create connection
        $connection = new mysqli($GLOBALS['server'], $GLOBALS['user'], $GLOBALS['pass'], $GLOBALS['dbname']);
                        
        // Check connection
        if ($connection->connect_error) {
            die("Connection failed<br>$connection->connect_error");
        }

        // Form SQL query
        $sql = "SELECT reader_name, reader_group, approved, signature FROM readers WHERE id = '$readerid'";

        $readerinfo;

        // Fetch each line and display in table
        if ($result = mysqli_query($connection, $sql)) {
            $row=mysqli_fetch_row($result);
            
            $readerInfo = array("reader_name"=>$row[0], "reader_group"=>$row[1], "approved"=>$row[2], "signature"=>$row[3]);

            mysqli_free_result($result);
        } else {
            die("There was an error retreiving a list of admins from the database:<br>$connection->error<br>");
        }
        
        $connection->close();
        
        return $readerInfo;
    }

    // List all readers pending approval
    function listPending() {
        // Create connection
        $connection = new mysqli($GLOBALS['server'], $GLOBALS['user'], $GLOBALS['pass'], $GLOBALS['dbname']);
                
        // Check connection.
        if ($connection->connect_error) {
            die("Connection failed<br>$connection->connect_error");
        }

        // Form SQL query
        $sql = "SELECT id, signature FROM readers WHERE approved = 0 ORDER BY id";

        // Fetch each line and display in table.
        if ($result = mysqli_query($connection, $sql)) {
            if (mysqli_num_rows($result) === 0) {
                echo "The pending readers table is empty.<br>";
            } else {
                while ($row=mysqli_fetch_row($result)) {
                    echo "<tr>
                    <td>$row[0]</td>
                    <td>$row[1]</td>
                    <td><a href=\"../php/approvereader.php?id=".$row[0]."\"><i class=\"fas fa-check fa-lg\"></i></a></td>
                    </tr>";
                }
            }
            mysqli_free_result($result);
        } else {
            die("There was an error listing the readers from the database:<br>$connection->error<br>");
        }

        // Close the connection
        $connection->close();
    }

    // List all approved readers
    function listReaders() {
        // Create connection
        $connection = new mysqli($GLOBALS['server'], $GLOBALS['user'], $GLOBALS['pass'], $GLOBALS['dbname']);
                
        // Check connection.
        if ($connection->connect_error) {
            die("Connection failed<br>$connection->connect_error");
        }

        // Form SQL query
        $sql = "SELECT id, reader_name, reader_group, signature FROM readers WHERE approved = 1 ORDER BY id";

        // Fetch each line and display in table.
        if ($result = mysqli_query($connection, $sql)) {
            if (mysqli_num_rows($result) === 0) {
                echo "The readers table is empty.<br>";
            } else {
                while ($row=mysqli_fetch_row($result)) {
                    echo "<tr>
                    <td>$row[0]</td>
                    <td>$row[1]</td>
                    <td>$row[2]</td>
                    <td>$row[3]</td>
                    <td><a href=\"updatereader.php?id=".$row[0]."\"><i class=\"fas fa-sync fa-lg\"></i></a></td>
                    <td><a href=\"../php/deletereader.php?id=".$row[0]."\"><i class=\"fas fa-trash fa-lg\"></i></a></td>
                    </tr>";
                }
            }
            mysqli_free_result($result);
        } else {
            die("There was an error listing the readers from the database:<br>$connection->error<br>");
        }

        // Close the connection
        $connection->close();
    }

    // Search all approved readers
    function searchReaders($searchq) {
        // Create connection
        $connection = new mysqli($GLOBALS['server'], $GLOBALS['user'], $GLOBALS['pass'], $GLOBALS['dbname']);
                        
        // Check connection.
        if ($connection->connect_error) {
            die("Connection failed<br>$connection->connect_error");
        }

        // Form SQL query
        $sql = "SELECT id, reader_name, reader_group, signature
                FROM readers
                WHERE approved = 1
                AND (
                    reader_name LIKE '%$searchq%'
                    OR id LIKE '%$searchq%'
                )
                ORDER BY id";

        // Fetch each line and display in table.
        if ($result = mysqli_query($connection, $sql)) {
            if (mysqli_num_rows($result) === 0) {
                echo "The readers table is empty.<br>";
            } else {
                while ($row=mysqli_fetch_row($result)) {
                    echo "<tr>
                    <td>$row[0]</td>
                    <td>$row[1]</td>
                    <td>$row[2]</td>
                    <td>$row[3]</td>
                    <td><a href=\"updatereader.php?id=".$row[0]."\"><i class=\"fas fa-sync fa-lg\"></i></a></td>
                    <td><a href=\"../php/deletereader.php?id=".$row[0]."\"><i class=\"fas fa-trash fa-lg\"></i></a></td>
                    </tr>";
                }
            }
            mysqli_free_result($result);
        } else {
            die("There was an error listing the readers from the database:<br>$connection->error<br>");
        }

        // Close the connection
        $connection->close();
    }

    // Remove a reader
    function removeReader($readerid) {
        // Create connection
        $connection = new mysqli($GLOBALS['server'], $GLOBALS['user'], $GLOBALS['pass'], $GLOBALS['dbname']);
                        
        // Check connection.
        if ($connection->connect_error) {
            die("Connection failed<br>$connection->connect_error");
        }

        // Form SQL query
        $sql = "DELETE FROM readers WHERE id = $readerid; DELETE FROM privilege WHERE reader_id = $readerid";

        // Remove the reader.
        if (!mysqli_multi_query($connection, $sql)) {
            die("There was an error removing the reader from the database:<br>$connection->error<br>");
        }

        // Close the connection
        $connection->close();
    }

}

class Reader extends Database {
    function checkPrivlege() {}

    function checkReaderApproved() {}

    function addLogEntry() {}
}

?>
