<html>
<head>
    <title>Pending Readers</title>
    <link rel="stylesheet" type="text/css" href="../css/global-style.css">
    <link rel="stylesheet" type="text/css" href="../css/header.css">
    <link rel="stylesheet" type="text/css" href="../css/menu.css">
</head>
<body>
    <div id="content">
    <?php include "../component/header.php"; ?>
    <?php include "../component/menu.php"; ?>
    
    
    <form action="" method="GET" name="search" onsubmit="validateInput()">
        <input type="text" placeholder="Search..." name="searchInput"> <input type="submit" value="Search">
      </form>
      
          <table id="list-table">
          <tr>
            <th>Member ID</th>
            <th> Data</th>
            <th>Approve</th>
      
          </tr>
        </table>


</div>
</body>
</html>