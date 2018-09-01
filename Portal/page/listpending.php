<?php
    include_once "../php/database.php";
    $database = new Database();
 ?>

<html>
<head>
    <title>Pending Readers</title>
    <link rel="stylesheet" type="text/css" href="../css/global-style.css">
    <link rel="stylesheet" type="text/css" href="../css/header.css">
    <link rel="stylesheet" type="text/css" href="../css/menu.css">
</head>
<body>
    <div id="content">
    <?php include "../component/header.php"; ?>
    <?php include "../component/menu.php"; ?>
      
          <table id="list-table">
          <tr>
            <th>ID</th>
            <th>Reader Code</th>
            <th>Approve</th>
          </tr>
          <?php echo $database->listPending(); ?>
        </table>


</div>
</body>
</html>
