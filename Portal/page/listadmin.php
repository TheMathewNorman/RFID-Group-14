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
  <script type="text/javascript" src="../js/input.js"></script>
</head>
<body>
<div id="content">
    
    <?php include "../component/header.php"; ?>
    <?php include "../component/menu.php"; ?>
    
      <form action="" method="GET" name="search" onsubmit="validateInput()">
        <input type="text" placeholder="Search..." name="searchInput"> <input type="submit" value="Search">
      </form>
    
      <?php
        if (isset($_GET['searchInput'])) {
          $database->listAdmins($_SESSION['userid'], $_GET['searchInput']);
        } else {
          $database->listAdmins($_SESSION['userid']);
        }
      ?>
  
      </div>
    </body>
  </html>
