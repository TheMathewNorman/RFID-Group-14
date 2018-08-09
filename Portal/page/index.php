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
    ?>
            Hello <?php echo $_SESSION['fname']; ?>,
            <br><br>
            Welcome to the Creative Geelong member access management system.
            <br>
            Please use the above menu to navigate the system and remember to <a href="../php/logout.php" style="color:white;font-weight:bold">logout</a> when done.
            <br><br>
            Thank you.
    <?php
        } else {
            echo "This page is still under development.";
        }
    ?>
</div>
</body>
</html>