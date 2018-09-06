<?php
    include_once "../php/database.php";
    $database = new Database();
?>

<html>
<head>
    <title>Member Access</title>
    <link rel="stylesheet" type="text/css" href="../css/global-style.css">
    <link rel="stylesheet" type="text/css" href="../css/header.css">
    <link rel="stylesheet" type="text/css" href="../css/menu.css">
</head>
<body>
    <div id="content">
    <?php include "../component/header.php"; ?>
    <?php include "../component/menu.php"; ?>
    
    <?php $database->listPrivilegeMembers(); ?>
    </div>
</body>
</html>
