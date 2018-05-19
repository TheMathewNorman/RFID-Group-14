<?php
    // List members code goes here
?>
<html>
<head>
  <title>List Members</title>
  <link rel="stylesheet" type="text/css" href="../css/global-style.css">
  <link rel="stylesheet" type="text/css" href="../css/menu.css">
</head>
<body>
<div id="content">
    
        <?php include "menu.php"; ?>
    

  <div style="text-align: right;">
    <form action="" method="POST">
      <input type="text" placeholder="Search.." name="search"> <input type="submit" value="Search">
    </form>
  </div>
  
    <table id="list-table">
      <tr>
        <th>ID</th>
        <th> Firstname</th>
        <th>Lastname</th>
        <th>Email</th>
        <th>Phone Number</th>
        <th>Update</th>
        <th>Delete</th>
      </tr>
      <?php
        // Echo table
      ?>
    </table>
  
      </div>
    </body>
  </html>
