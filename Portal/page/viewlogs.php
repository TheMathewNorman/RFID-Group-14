<?php
  include_once '../php/database.php';
  $database = new Database();
?>
<html>
  <head>
    <title>Logs</title>
    <link rel="stylesheet" type="text/css" href="../css/global-style.css">
    <link rel="stylesheet" type="text/css" href="../css/header.css">
    <link rel="stylesheet" type="text/css" href="../css/menu.css">
  </head>
  <body>
    <div id="content">
      <?php include "../component/header.php"; ?>
      <?php include "../component/menu.php"; ?>
    
      This page is still under development.

      <!-- Commented out until functional 
      <form action="" method="GET">
        <input type="text" placeholder="Search..." name="search"> <input type="submit" value="Search">
      </form>
      -->
      <table id="list-table">
        <tr>
          <th>ID</th>
          <th>Member</th>
          <th>Reader</th>
          <th>Date & Time</th>
        </tr>
        <?php
          echo "getting log entries";
          echo $database->logEntries();
          echo "done";
        ?>
      </table>
    


  </div>
</html>