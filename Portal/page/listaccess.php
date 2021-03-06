<?php
    if (!isset($_GET['id'])) {
        header('Location: memberaccess.php');
    }
    
    // List members code goes here
    include_once "../php/database.php";
    $database = new Database();
?>
<html>
<head>
  <title>List Access</title>
  <link rel="stylesheet" type="text/css" href="../css/global-style.css">
  <link rel="stylesheet" type="text/css" href="../css/header.css">
  <link rel="stylesheet" type="text/css" href="../css/menu.css">
  <script type="text/javascript" src="../js/input.js"></script>
</head>
<body>
<div id="content">
    
    <?php include "../component/header.php"; ?>
    <?php include "../component/menu.php"; ?>
    
    <?php
      $database->listMemberPrivilege($_GET['id']);
    ?>
    
    <div id="add-new-button">  
        <a href="addaccess.php?id=<?php echo $_GET['id']; ?>"><i class="fas fa-plus-circle fa-lg"></i></a>

    </div>
  
      </div>
    </body>
  </html>