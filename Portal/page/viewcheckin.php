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

      <form action="" method="GET">
        <input type="text" <?php if (isset($_GET['search']) && $search !=="") { echo 'value="'.$search.'"'; } else { echo 'placeholder="Search..."'; } ?> name="search"> <input type="submit" value="Search">
      </form>
      
      <table id="list-table">
        <tr>
          <th>Member ID</th>
          <th>Member</th>
          <th># of Visits</th>
          <th>Currently Active</th>
        </tr>
        <?php
          echo $database->getCheckinEntries();
        ?>
      </table>
  </div>
</html>