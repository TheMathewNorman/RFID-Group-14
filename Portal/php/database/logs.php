<?php
    class Logs extends Database {
        //// LOG TABLE FUNCTIONALITY //// 
        // Add a new entry to the Log
        function addLogEntry($readerid, $key, $checkin=0) {
            $keyhash = hash('sha512', $key);

            $return = true;

            // Create connection
            $connection = new mysqli($GLOBALS['server'], $GLOBALS['user'], $GLOBALS['pass'], $GLOBALS['dbname']);

            // Check connection.
            if ($connection->connect_error) {
                $return = false;
            }

            // Get member id from key
            $memberid;
            $sql = "SELECT id FROM members WHERE cardkey = '$keyhash'";
            if ($result = mysqli_query($connection, $sql)) {
                if ($row = mysqli_fetch_row($result)) {
                    $memberid = $row[0];
                }
            } else {
                $return = false;
            }

            // Add log entry
            $sql = "INSERT INTO logs (member_id, reader_id, check_in) VALUES ($memberid, $readerid, $checkin)";
            if (!$connection->query($sql)) {
                $return = false;
            }

            // Close the connection
            $connection->close();

            return $return;
        }
        
        // Get all entries in the logs table
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
                INNER JOIN readers ON logs.reader_id = readers.id)
                WHERE logs.check_in = 0
                ORDER BY logs.access_date DESC";

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
        
        // Search the logs table
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
                WHERE logs.check_in = 0
                AND (logs.id = '$searchq'
                OR logs.member_id = '$searchq'
                OR logs.reader_id = '$searchq'
                OR members.firstname LIKE '%$searchq%'
                OR members.lastname LIKE '%$searchq%'
                OR readers.reader_name LIKE '%$searchq%'
                XOR DATE_FORMAT(logs.access_date, '%Y-%m-%e') = '$searchq'
                XOR DATE_FORMAT(logs.access_date, '%e-%m-%Y %r') LIKE '%$searchq%'
                XOR DATE_FORMAT(logs.access_date, '%e-%m-%Y %T') LIKE '%$searchq%'
                XOR DATE_FORMAT(logs.access_date, '%e-%m-%y %r') LIKE '%$searchq%'
                XOR DATE_FORMAT(logs.access_date, '%e-%m-%Y %T') LIKE '%$searchq%'
                XOR DATE_FORMAT(logs.access_date, '%r %T %e %m %M %Y') LIKE '%$searchq%')
                ORDER BY logs.access_date DESC";

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
                    $logHTML.="There was an error getting log information from the database: ".mysqli_error($connection);
                }
            }
            // Close the connection
            $connection->close();
            
            // Return table or message
            return $logHTML;
        }

        // Display a list of check-ins
        function getCheckinEntries() {
            $logHTML = "";

            // Create connection
            $connection = new mysqli($GLOBALS['server'], $GLOBALS['user'], $GLOBALS['pass'], $GLOBALS['dbname']);

            // Check connection.
            if ($connection->connect_error) {
                $logHTML.="Connection failed<br>$connection->connect_error";
            } else {

                // Form SQL query
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

                // Fetch each line and display in table.
                if ($result = mysqli_query($connection, $sql)) {
                    while ($row = mysqli_fetch_row($result)) {

                        // Highlight rows where the member is currently on site
                        if ($row[3] == 1 && $row[5] < 10) {
                            $logHTML.= '<tr style="background-color: rgba(0,100,0,0.7)">';
                        } else if ($row[3] == 1 && $row[5] > 10) {
                            $logHTML.= '<tr style="background-color: rgba(125,100,0,0.6)">';
                        } else {
                            $logHTML.= '<tr style="background-color: rgba(100,0,0,0.2)">';
                        }
                        $logHTML.= "<td>".$row[0]."</td>";
                        $logHTML.= "<td>".$row[1]."</td>";
                        $logHTML.= "<td>".$row[2]."</td>";
                        if ($row[3] == 1 && $row[5] < 10) {
                            $logHTML.= '<td>YES</td>';
                        } else if ($row[3] == 1 && $row[5] > 10) {
                            $logHTML.= '<td>MAYBE</td>';
                        } else {
                            $logHTML.= '<td>No</td>';
                        }
                        $logHTML.= "<td>".$row[5]." hours ago</td>";
                        $logHTML.= "<td>".$row[4]."</td>";
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
    }
?>