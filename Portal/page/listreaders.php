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
      
          <table id="list-table">
          <tr>
            <th>Reader ID</th>
            <th>Reader Name</th>
            <th>Reader Group</th>
            <th>Signature</th>
            <th>Update</th>
            <th>Remove</th>
          </tr>
          <?php echo $database->listReaders(); ?>
        </table>


</div>
</body>
</html>