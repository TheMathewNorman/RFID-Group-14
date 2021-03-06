<?php
  include_once '../php/database.php';
  $database = new Logs();

  $search = "";
  if (isset($_GET['search'])) {
    $search = $_GET['search'];
  } else if (isset($_GET['today'])) {
    $today = getdate();
    
    $search.= $today['year'].'-';
    // Pad when less then 10
    if ($today['mon'] < 10) {
      $search.= '0'.$today['mon'].'-';
    } else {
      $search.= $today['mon'].'-';
    }
    $search.= $today['mday'];
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

      <?php
        if (!isset($_GET['today'])) {
      ?>
      <form action="" method="GET">
        <input type="text" <?php if (isset($_GET['search']) && $search !=="") { echo 'value="'.$search.'"'; } else { echo 'placeholder="Search..."'; } ?> name="search"> <input type="submit" value="Search">
      </form>
      <?php
        }
      ?>
    
      <?php
        if ($search !== "") {
          $database->getLogEntries($search);
        } else {
          $database->getLogEntries();
        }
      ?>
  </div>
</html>
