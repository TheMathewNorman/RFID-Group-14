<?php    
    if (isset($_GET['test'])) {
        if ($_GET['test'] == "good") {
            // 200 OK
            http_response_code(200);
        } else {
            // 401 Unauthorized
            http_response_code(401);
        }
    }
?>