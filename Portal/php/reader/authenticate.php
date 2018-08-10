<?php
    // Readers will validate cards here.
    if (isset($_GET['reader']) && isset($_GET['key'])) {
        $reader = $_GET['reader'];
        $key = $_GET['key'];
    if ($reader == 10 && $key == 9988) {
        http_status_code(200);
        echo "OK";
    } else {
        http_status_code(403);
        echo "Forbidden";
    }
} else {
    http_status_code(403);
    echo "Forbidden";
}
?>
