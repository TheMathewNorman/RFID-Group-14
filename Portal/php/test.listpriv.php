<?php
    include_once './database.php';
    $database = new Database();

    echo $database->listPrivilegeMembers();
?>