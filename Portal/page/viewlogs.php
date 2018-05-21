<html>
<head>
  <title>Logs</title>
  <link rel="stylesheet" type="text/css" href="../css/global-style.css">
  <link rel="stylesheet" type="text/css" href="../css/menu.css">
</head>
<body>
  <div id="content">
    <?php include "menu.php"; ?>
    <form action="" method="GET">
      <input type="text" placeholder="Search..." name="search"> <input type="submit" value="Search">
    </form>

    <table id="list-table">
      <tr>
        <th>ID</th>
        <th> Type</th>
        <th>Date</th>
        <th>Log Message</th>
      </tr>
    </table>
  </div>
</html>
