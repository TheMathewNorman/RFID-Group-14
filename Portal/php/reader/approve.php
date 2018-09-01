<?php
    // Unverified readers will create an approval request here.
    
    

    // function checkApproveReader($id){
    //     $connection = new mysqli($GLOBALS['server'], $GLOBALS['user'], $GLOBALS['pass'], $GLOBALS['dbname']);

    //     // Check connection
    //     if ($connection->connect_error) {
    //         die("Connection failed<br>$connection->connect_error");
    //     }
    //     // Form SQL query
    //     $sql = "SELECT * FROM readers WHERE id = '".$id."'";

    //     if ($result = mysqli_query($connection, $sql)) {
    //         if (mysqli_num_rows($result) === 0) {
    //             return false;
    //         } else {
    //             while ($row=mysqli_fetch_row($result)) {
    //                 // Replace and remove the delete button functionality for the key admin.
    //                 if ($row[3]) { 
    //                     return true;    
    //                 }
    //                 else { 
    //                     return false;
    //                 }
    //             }
    //         }
    //         mysqli_free_result($result);
    //     } else {
    //         return false;
    //     }
    // }

    // if (isset($_GET['id'])) {
    //     $reader = $_GET['id'];

    //     if (checkApproveReader($reader)) {
    //         echo 1;
    //     } else {
    //         echo 0;
    //     }
    // } else {
    //     echo 0;
    // }


?>
