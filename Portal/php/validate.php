<?php

class Validate {
    // Validate email
    function validateEmail($input) {
        $result = false;
        
        // Remove any illegal characters
        $email = filter_var($input, FILTER_SANITIZE_EMAIL);

        // Validate
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $result = true;
        }

        // Return result
        return $result;
    }

    // Sanitize string
    function sanitizeString($input) {
        // Remove any illegal or uncommon characters
        $result = filter_var($input, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        
        // Return result
        return $result;
    }
}

?>