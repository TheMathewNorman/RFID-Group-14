<?php

class Validate {
    // email
    function validateEmail($input) {
        $sanInput = filter_var($input, FILTER_VALIDATE_EMAIL);

        return $sanInput;
    }

    // string
    function validateString($input) {

    }
}

?>