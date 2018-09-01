<?php
class Readers extends Database {
    //// READER TABLE FUNCTIONALITY //// 
    function updateReader($readerid, $name,$group) {
        // Create connection
        $connection = new mysqli($GLOBALS['server'], $GLOBALS['user'], $GLOBALS['pass'], $GLOBALS['dbname']);
                
        // Check connection.
        if ($connection->connect_error) {
            die("Connection failed<br>$connection->connect_error");
        }

        // Form SQL query
        $sql = "UPDATE readers SET reader_name = '$name', reader_group = $group, approved = 1 WHERE id = $readerid";

        // Remove the reader.
        if (!mysqli_query($connection, $sql)) {
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
                    <td><a href=\"approvereader.php?id=".$row[0]."\"><i class=\"fas fa-check fa-lg\"></i></a></td>
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
                    <td><a href=\"../php/deletereader.php?table=member&id=".$row[0]."\"><i class=\"fas fa-trash fa-lg\"></i></a></td>
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
                    <td><a href=\"../php/deletereader.php?table=member&id=".$row[0]."\"><i class=\"fas fa-trash fa-lg\"></i></a></td>
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
        $sql = "DELETE FROM readers WHERE id = $readerid";

        // Remove the reader.
        if (!mysqli_query($connection, $sql)) {
            die("There was an error removing the reader from the database:<br>$connection->error<br>");
        }

        // Close the connection
        $connection->close();
    }
}
?>