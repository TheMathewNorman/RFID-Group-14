<?php

class Privilege extends Database {
    //// PRIVILEDGE TABLE FUNCTIONALITY //// 
    // Functions to include
    // addPriviledge($memberid,$readerid,$readergroup)
    // removePriviledge($id)
    // modifyPriviledge($id,$memberid,$readerid,$readergroup)
    // listPriviledges()
    
    // Check if member associated with key has been given access to the reader.
    function checkPrivilege($signature, $key) {
        $keyhash = hash('sha512', $key);
        
        // Create connection
        $connection = new mysqli($GLOBALS['server'], $GLOBALS['user'], $GLOBALS['pass'], $GLOBALS['dbname']);
                
        // Check connection and return status
        if ($connection->connect_error) {
            die('Connection failed:<br>'.$connection->connect_error);
        }

        // Find any results for given member and reader combination in the privilege table
        $sql = "SELECT privilege.id FROM ((privilege INNER JOIN members ON privilege.member_id = members.id) INNER JOIN readers ON privilege.reader_id = readers.id) WHERE readers.signature = '$readerid' AND readers.approved = 1 AND members.cardkey = '$keyhash'";

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
}

?>