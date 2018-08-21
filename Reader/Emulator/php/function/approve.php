<?php
    // Used to seek approval for a new reader
    class Approve {
        // Check approve status
        function checkApproval() {
            // Check reader's approval status
            $result = file_get_contents($GLOBALS['server'].$GLOBALS['appr']."?reader=$readerID");

            // Return a result based on reader's status
            return $result == "1" ? "Reader has been approved." : "Reader is awaiting approval, or has been denied.";
        }
    }

?>