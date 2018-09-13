<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/config.php';

class Database {

    protected $_dbconn;

    private $_dbhost;
    private $_dbname;
    private $_dbuser;
    private $_dbpass;

    public $connsuccess = true;
    public $connerror = "";
    
    function __construct($dbhost="", $dbname="", $dbuser="", $dbpass="") {
        if (!empty($dbhost) && !empty($dbname) && !empty($dbuser) && !empty($dbpass)) {
            $this->_dbhost = $dbhost;
            $this->_dbname = $dbname;
            $this->_dbuser = $dbuser;
            $this->_dbpass = $dbpass;
        } else {
            $this->_dbhost = DB_HOST;
            $this->_dbname = DB_NAME;
            $this->_dbuser = DB_USER;
            $this->_dbpass = DB_PASS;
        }

        $this->connect();
    }

    function __destruct() {
        $this->_dbconn = null;
    }

    private function connect() {
        // Create PDO connection
        try {
            // Attempt to create a connection using provided parameters
            $conn = new PDO("mysql:host=$this->_dbhost;dbname=$this->_dbname", $this->_dbuser, $this->_dbpass);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // If an exception has not been thrown, set _dbconn to connection
            $this->_dbconn = $conn;
            
        } catch (Exception $e) {
            // Set success state to false
            $this->connsuccess = false;
            
            // Set error
            $this->connerror = $e;
        }
    }

    private $_expectedTables = array('admins', 'logs', 'members', 'privilege', 'readers');
    // Check that all the expected tables exist within the database.
    function checkTablesExist() {
        $return = true;
        
        // Get a list of tables that exist in the database
        $actualTables = array();
        try {
            $sql = "SHOW TABLES FROM $this->_dbname";

            if ($this->connsuccess) {
                $stmt = $this->_dbconn->prepare($sql);
                $stmt->execute();
                while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
                    array_push($actualTables, $row[0]);
                }
            }
        } catch (Exception $e) {
            $return = false;
        }

        // If a table is missing from the tables array, return false
        foreach ($this->_expectedTables as $table) {
            if (!in_array($table, $actualTables)) { $return = false; }
        } 

        // If there is no admin in the admins table return false
        if ($return) {
            $sql = "SELECT count(*) FROM admins";
            $stmt = $this->_dbconn->prepare($sql);
            $stmt->execute();
            
            if ($stmt->fetchColumn() < 1) {
                $return = false;
            }
        }

        return $return;
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

    // Attempt to login with a given email and password
    function loginAdmin($email, $password) {
        // Login response
        //['id'] = Admin's ID on success
        //['fname'] = Admin's first name on success

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
            // $loginResponse[0] = true;
            $loginResponse['id'] = $info['id'];
            $loginResponse['fname'] = $info['firstname'];
        } else {
            // $loginResponse[1] = 'Email or password was incorrect.';
            throw new Exception("Email or password was incorrect.");
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
        try {
        $stmt->execute($params);
        } catch (Exception $e) {
            return false;
        }
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
            $output.= '<table id="list-table"><thead><tr><th>#</th><th>Member ID</th><th>Member</th><th>Reader ID</th><th>Reader</th><th>Date & Time</th></tr></thead><tbody>';

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
            $output.= "</tbody></table>";
        } else {
            if ($searchq === "") {
                $output.= "There were no results.";
            } else {
                $output.= "There were no results for $searchq";
            }
        }

        echo $output;
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
        $params = array(':memberid' => $memberid);
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
            $stmt->execute($params);

            // Form table
            $output.= '<table id="list-table"><tr><th>PID</th><th>Reader ID</th><th>Reader Name</th><th>Remove</th></tr>';


            // Fetch each line and display in table.
            $pid = $rid = $readername = '';
            while ($row = $stmt->fetch()) {
                $pid = $row['PID'];
                $rid = $row['RID'];
                $readername = $row['ReaderName'];
                $output.= "<tr><td>$pid</td><td>$rid</td><td>$readername</td><td><a href=\"../php/deleteaccess.php?id=".$pid."&member=$memberid\"><i class=\"fas fa-minus-circle fa-lg\"></i></a></td></tr>";
            }
        
            $output.= "</table>";
        } else {
            $output = "This member hasn't yet been assigned any privileges. <br>Click the button below to assign one.";
        }

        echo $output;
    }
    
    // List all readers that can be assigned to a member
    function listPrivilegeReaders() {
        $output = '';
        
        // Get row count
        $rowCount;
        $sql = "SELECT count(*) FROM readers WHERE approved = 1";
        $stmt = $this->_dbconn->prepare($sql);
        $stmt->execute();
        $rowCount = $stmt->fetchColumn();
        
        if ($rowCount > 0) {
            // Execute query
            $sql = "SELECT id, reader_name AS ReaderName FROM readers WHERE approved = 1";
            $stmt = $this->_dbconn->prepare($sql);
            $stmt->execute();

            // Form table
            $output.= '<table id="list-table"><tr><th>Reader ID</th><th>Reader Name</th><th>Add</th></tr>';

            // Fetch each line and display in table.
            $id = $readername = '';
            while ($row = $stmt->fetch()) {
                $id = $row['id'];
                $readername = $row['ReaderName'];
                $output.= "<tr><td>$id</td><td>$readername</td><td><input type=\"checkbox\" name=\"$id\" value=\"$id\"></td></tr>";
            }
        
            $output.= "</table>";
        } else {
            $output = "There are no readers. <br>Please connect a reader before trying to assign member access.";
        }

        echo $output;
    }

    // Revoke a member's privilege
    function removePrivilege($privilegeid) {
        // Execute query
        $params = array(':privilegeid' => $privilegeid);
        $sql = "DELETE FROM privilege WHERE id = :privilegeid";
        $stmt = $this->_dbconn->prepare($sql);
        $stmt->execute($params);
    }

    
    
    //// READER TABLE FUNCTIONALITY //// 
    // Update a reader
    function updateReader($readerid, $readername = "", $readergroup = "", $approved = "") {
        // Execute query
        $params = array(':readername'=> $readername, ':readergroup'=> $readergroup, ':approved'=> $approved, ':readerid'=> $readerid);
        $sql = "UPDATE readers SET reader_name = :readername, reader_group = :readergroup, approved = :approved WHERE id = :readerid";
        $stmt = $this->_dbconn->prepare($sql);
        $stmt->execute($params);
    }

    // Approve a reader
    function approveReader($readerid) {
        // Form SQL query
        $params = array(':readerid' => $readerid);
        $sql = "UPDATE readers SET approved = 1 WHERE id = :readerid";
        $stmt = $this->_dbconn->prepare($sql);
        $stmt->execute($params);
    }

    // Get number of pending readers
    function getPendingCount() {
        $pendingCount = 0;
        
        // Execute query
        $sql = "SELECT count(*) FROM readers WHERE approved = 0";
        $stmt = $this->_dbconn->prepare($sql);
        $stmt->execute();
        $pendingCount = $stmt->fetchColumn();

        // Return number of pending readers
        return $pendingCount;
    }

    // Get reader information
    function fetchReaderInfo($readerid) {
        // Execute query
        $params = array(':readerid' => $readerid);
        $sql = "SELECT reader_name, reader_group, approved, signature FROM readers WHERE id = :readerid";
        $stmt = $this->_dbconn->prepare($sql);
        $stmt->execute($params);

        $readerInfo = $stmt->fetch();
        
        return $readerInfo;
    }

    // List all readers pending approval
    function listPending() {
        $output = '';

        // Get number of rows
        $rowCount = 0;
        $sql = "SELECT count(*) FROM readers WHERE approved = 0 ORDER BY id";
        $stmt = $this->_dbconn->prepare($sql);
        $stmt->execute();
        $rowCount = $stmt->fetchColumn();
        
        if ($rowCount > 0) {
            // Execute SQL query
            $sql = "SELECT id, signature FROM readers WHERE approved = 0 ORDER BY id";
            $stmt = $this->_dbconn->prepare($sql);
            $stmt->execute();

            // Form table 
            $output.= '<table id="list-table"><tr><th>ID</th><th>Reader Code</th><th>Approve</th></tr>';

            $id = $readercode = $approve = '';
            while ($row = $stmt->fetch()) {
                $id = $row['id'];
                $readercode = $row['signature'];

                $output.= "<tr><td>$id</td><td>$readercode</td><td><a href=\"../php/approvereader.php?id=$id\"><i class=\"fas fa-check fa-lg\"></i></a></td></tr>";
            }

            $output.= "</table>";
        } else {
            $output = "There are no readers pending approval.";
        }

        echo $output;
    }

    // List all approved readers
    function listReaders($searchq = "") {
        $output = '';

        if ($searchq == '') {
            // Get number of rows
            $rowCount = 0;
            $sql = "SELECT count(*) FROM readers WHERE approved = 1 ORDER BY id";
            $stmt = $this->_dbconn->prepare($sql);
            $stmt->execute();
            $rowCount = $stmt->fetchColumn();
        } else {
            // Get number of rows
            $rowCount = 0;
            $params = array(':searchlike' => '%'.$searchq.'%');
            $sql = "SELECT count(*) FROM readers WHERE approved = 1 AND reader_name LIKE :searchlike ORDER BY id";
            $stmt = $this->_dbconn->prepare($sql);
            $stmt->execute($params);
            $rowCount = $stmt->fetchColumn();
        }
        
        if ($rowCount > 0) {
            if ($searchq == '') {
                // Execute SQL query
                $sql = "SELECT id, reader_name, reader_group, signature FROM readers WHERE approved = 1 ORDER BY id";
                $stmt = $this->_dbconn->prepare($sql);
                $stmt->execute();
            } else {
                // Execute SQL query
                $sql = "SELECT id, reader_name, reader_group, signature FROM readers WHERE approved = 1 AND reader_name LIKE :searchlike ORDER BY id";
                $stmt = $this->_dbconn->prepare($sql);
                $stmt->execute($params);
            }

            // Form table 
            $output.= '<table id="list-table"><tr><th>Reader ID</th><th>Reader Name</th><th>Reader Group</th><th>Signature</th><th>Update</th><th>Remove</th></tr>';

            // Add table rows from database
            $id = $readername = $readergroup = $readersig = '';
            while ($row = $stmt->fetch()) {
                $id = $row['id'];
                $readername = $row['reader_name'];
                $readergroup = $row['reader_group'];
                $readercode = $row['signature'];

                $output.= "<tr><td>$id</td><td>$readername</td><td>$readergroup</td><td>$readercode</td><td><a href=\"updatereader.php?id=$id\"><i class=\"fas fa-sync fa-lg\"></i></a></td><td><a href=\"../php/deletereader.php?id=$id\"><i class=\"fas fa-trash fa-lg\"></i></a></td></tr>";
            }

            $output.= "</table>";
        } else {
            $output = "There are no readers. <br>Please connect and approve a new reader first.";
        }

        echo $output;
    }

    // Remove a reader
    function removeReader($readerid) {
        // Execute query
        $params = array(':readerid' => $readerid);
        $sql = "DELETE FROM readers WHERE id = :readerid; DELETE FROM privilege WHERE reader_id = :readerid";
        $stmt = $this->_dbconn->prepare($sql);
        $stmt->execute($params);
    }
}

class Reader extends Database {
    // Add log entry
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
    
    // Check if the reader is approved.
    function checkReaderApproved($signature) {
        $return = false; 
        
        // Check if reader already exists, whether pending or not
        $rowCount;
        $params = array(':signature' => $signature);
        $sql = "SELECT COUNT(*) FROM readers WHERE signature = :signature";
        $stmt = $this->_dbconn->prepare($sql);
        $stmt->execute($params);
        $rowCount = $stmt->fetchColumn();

        // If the reader does not exist add to pending
        if ($rowCount == 0) {
            $sql = "INSERT INTO readers (reader_name, reader_group, approved, signature) VALUES ('', 0, 0, :signature)";
            $stmt = $this->_dbconn->prepare($sql);
            $stmt->execute($params);
        } else { // If the reader exists, and approved, set return value to true
            $sql = "SELECT COUNT(*) FROM readers WHERE approved = 1 AND signature = :signature";
            $stmt = $this->_dbconn->prepare($sql);
            $stmt->execute($params);
            $rowCount = $stmt->fetchColumn();
            if ($rowCount >= 1) {
                $return = true;
            }
        }

        return $return;
    }

    // Check if member associated with key has been given access to the reader.
    function checkPrivilege($signature, $key) {
        $keyhash = hash('sha512', $key);
        
        $return = false;

        $params = array(':signature' => $signature, ':cardkey' => $keyhash);
        $sql = "SELECT COUNT(*) FROM ((privilege INNER JOIN members ON privilege.member_id = members.id) INNER JOIN readers ON privilege.reader_id = readers.id) WHERE readers.signature = :signature AND readers.approved = 1 AND members.cardkey = :cardkey";
        $stmt = $this->_dbconn->prepare($sql);
        $stmt->execute($params);
        
        // If there are any results returntrue
        if ($rowCount = $stmt->fetchColumn()) {
            $return = true;
        }

        return $return;
    }
}


class Logs extends Database {
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
            $output.= '<table id="list-table"><thead><tr><th>#</th><th>Member ID</th><th>Member</th><th>Reader ID</th><th>Reader</th><th>Date & Time</th></tr></thead><tbody>';

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
            $output.= "</tbody></table>";
        } else {
            if ($searchq === "") {
                $output.= "There were no results.";
            } else {
                $output.= "There were no results for $searchq";
            }
        }

        echo $output;
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
}

?>
