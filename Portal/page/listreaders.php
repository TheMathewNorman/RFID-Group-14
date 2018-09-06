<?php
    include_once "../php/database.php";
    $database = new Database();
?>
<html>
<head>
    <title>Readers</title>
    <link rel="stylesheet" type="text/css" href="../css/global-style.css">
    <link rel="stylesheet" type="text/css" href="../css/header.css">
    <link rel="stylesheet" type="text/css" href="../css/menu.css">
</head>
<body>
    <div id="content">
    <?php include "../component/header.php"; ?>
    <?php include "../component/menu.php"; ?>
    
    
    <form action="" method="GET" name="search" onsubmit="validateInput()">
        <input type="text" placeholder="Search..." name="searchInput"> <input type="submit" value="Search">
      </form>
        
        <?php 
            if (isset($_GET['searchInput']) && $_GET['searchInput'] != '') {
                $database->listReaders($_GET['searchInput']);
            } else {
                $database->listReaders();
            } 
        ?>


</div>
</body>
</html>