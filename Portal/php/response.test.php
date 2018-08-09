<?php    
    if (isset($_GET['test'])) {
        if ($_GET['test'] == "good") {
            // 200
            http_response_code(200);
        } else if ($_GET['test'] == "bad") {
            // 400
            http_response_code(400);
        } else {
            // 404
            http_response_code(404);
        }
    }
?>