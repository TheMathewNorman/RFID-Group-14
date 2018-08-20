<?php
    class Card {
        // Scan a card
        function scan($cardID, $readerID) {
            // Retrieve header information.
            $headers = get_headers($GLOBALS['server']."?key=$cardID&reader=$readerID");
            $responseCode = substr($headers[0], 9, 3);

            if ($responseCode === '200') {
                return True;
            } else if ($responseCode === "403") {
                return False;
            }
        }

    }
?>