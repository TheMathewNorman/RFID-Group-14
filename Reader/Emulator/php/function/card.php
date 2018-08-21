<?php
    // Used to handle card-server interactions
    class Card {
        // Scan a card
        function scan($cardID, $readerID) {
            // Retrieve page text
            $result = file_get_contents($GLOBALS['server']."?key=$cardID&reader=$readerID");

            // Return true if result == "1", otherwise false.
            return $result == "1" ? True : False;
        }

    }
?>