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
      
      <table id="list-table">
        <tr>
          <th>Member ID</th>
          <th>Member</th>
          <th># of Visits</th>
          <th>Currently Active</th>
          <th>Hours</th>
          <th>Last Visit</th>
        </tr>
        <?php
          echo $database->getCheckinEntries();
        ?>
      </table>
  </div>
</html>