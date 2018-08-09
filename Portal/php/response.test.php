<?php    
    if (isset($_GET['test'])) {
        if ($_GET['test'] == "good") {
            // 200 OK
            http_response_code(200);
            echo "OK";
        } else {
            // 403 Forbidden
            http_response_code(403);
            echo "Forbidden";
        }
    }
?>