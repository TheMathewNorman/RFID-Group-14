<?php    
    if (isset($_GET['test'])) {
        if ($_GET['test'] == "good") {
            echo true;
        } else {
            echo false;
        }
    }
?>