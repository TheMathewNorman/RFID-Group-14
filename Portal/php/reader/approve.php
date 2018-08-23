<?php
    // Unverified readers will create an approval request here.
    <?php

    if (isset($_GET['id'])) {
        $reader = $_GET['id'];

        if (existReader($reader)) {
            if(approvedReader($reader)){
                echo 1;
            }
            else{
                echo 0;
            }

        } else {
            echo 0;
        }
    } else {
        echo 0;
    }
    ?>
?>
