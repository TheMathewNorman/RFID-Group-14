<?php 
  if (!isset($GET_['id'])) {
    header("location: listreaders.php");
  }

  include_once "../php/database.php";
  $database = new Database();

?>

<html>
    <head>
        <title>Update Member</title>
        <link rel="stylesheet" type="text/css" href="../css/global-style.css">
        <link rel="stylesheet" type="text/css" href="../css/header.css">
        <link rel="stylesheet" type="text/css" href="../css/menu.css">
    </head>
    <body bgcolor="#f5f5f5">
        <div id="content">
        
        <?php include "../component/header.php"; ?>
        <?php include "../component/menu.php"; ?>
            
            <form action="" method="POST">
                <table class="form-table">
     
                  
                </table>
            </form>
        </div>
    </body>
</html>
