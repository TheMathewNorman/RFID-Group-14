<?php
    class Members extends Database {
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
    }

    ?>