<?php
    // List members code goes here
    include_once "../php/database.php";
    $database = new Database();
?>
<html>
<head>
  <title>List Admins</title>
  <link rel="stylesheet" type="text/css" href="../css/global-style.css">
  <link rel="stylesheet" type="text/css" href="../css/header.css">
  <link rel="stylesheet" type="text/css" href="../css/menu.css">
</head>
<body>
<div id="content">
    
    <?php include "../component/header.php"; ?>
    <?php include "../component/menu.php"; ?>
    
    <!-- Not yet functional
      <form action="" method="GET">
        <input type="text" placeholder="Search..." name="search"> <input type="submit" value="Search">
      </form>
    -->
    <table id="list-table">
      <tr>
        <th>ID</th>
        <th>Firstname</th>
        <th>Lastname</th>
        <th>Email</th>
        <th>Phone Number</th>
        <th>Update</th>
        <th>Delete</th>
      </tr>
      <?php
        $database->listAdmins();
      ?>
    </table>
  
      </div>
    </body>
  </html>
