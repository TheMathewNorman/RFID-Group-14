<?php

?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8" />
<title>Access Control Management System</title>

<link rel="stylesheet" href="../css/setup.css">
</head>
<body>
    <div id="setup-container">
        <div id="setup-heading">
            First Run Setup
        </div>
        <div id="setup-text">
            Some information is required before being able to use this system.
        </div>
        <?php
            if ($_GET['page'] == '2') {
                include "newadmin.php";
            } else {
                include "database.php";
            }
        ?>

    </div>
</body>
</html>
