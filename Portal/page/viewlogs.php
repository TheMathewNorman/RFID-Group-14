<?php
  include_once '../php/database.php';
  $database = new Database();

  include_once '../php/validate.php';
  $validate = new Validate();

  if (isset($_GET['search'])) {
    $search = $validate->sanitizeString($_GET['search']);
  }
  
  if (isset($_GET['checkins'])) {
    if ($_GET['checkins']) {
      $checkins = true;
    } else {
      $checkins = false;
    }
  }

  
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

      <form action="" method="GET">
        <input type="text" placeholder="Search..." name="search"> <input type="submit" value="Search">
      </form>
      
      <table id="list-table">
        <tr>
          <th>#</th>
          <th>Member ID</th>
          <th>Member</th>
          <th>Reader ID</th>
          <th>Reader</th>
          <th>Date & Time</th>
        </tr>
        <?php
          if (isset($_GET['search']) || $search != "") {
            echo $database->searchLogEntries($search);
          } else if (isset($_GET['checkins']) && $checkins == true) {
            echo $database->getCheckinEntries();
          } else {
            echo $database->getLogEntries();
          }
        ?>
      </table>
  </div>
</html>