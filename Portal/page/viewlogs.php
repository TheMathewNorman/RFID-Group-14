<?php
  include_once '../php/database.php';
  $database = new Database();

  include_once '../php/validate.php';
  $validate = new Validate();

  if (isset($_GET['search'])) {
    $search = $validate->sanitizeString($_GET['search']);
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
          <th>Message</th>
          <th>Reader</th>
          <th>Date & Time</th>
        </tr>
        <?php
          if (!isset($_GET['search']) || $search == "") {
            echo $database->getLogEntries();
          } else {
            echo $database->searchLogEntries($search);
          }
        ?>
      </table>
  </div>
</html>