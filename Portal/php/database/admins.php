<?php
class Admins extends Database {
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

    // Attempt to login with a given email and password
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
    
    // Fetch information for a given admin
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
}
?>