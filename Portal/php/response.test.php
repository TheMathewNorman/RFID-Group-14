<?php    
    if (isset($_GET['test'])) {
        if ($_GET['test'] == "good") {
            // 401 Unauthorized
            http_response_code(401);
            echo "Unauthorized";
        } else {
            // 401 Unauthorized
            http_response_code(401);
            echo "Unauthorized";
        }
    }
?>