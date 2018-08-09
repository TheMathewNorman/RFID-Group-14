<html>
<head>
    <title>Home</title>
    <link rel="stylesheet" type="text/css" href="../css/global-style.css">
    <link rel="stylesheet" type="text/css" href="../css/header.css">
    <link rel="stylesheet" type="text/css" href="../css/menu.css">
</head>
<body>
    <div id="content">
    <?php include "../component/header.php"; ?>
    <?php include "../component/menu.php"; ?>
    

    <?php
        if (isset($_SESSION['userid'])) {
            echo "<p>Hello ".$_SESSION['fname'].",<br> welcome to the RFID management system.</p>";
        } else {
            echo "This page is still under development.";
        }
    ?>
</div>
</body>
</html>