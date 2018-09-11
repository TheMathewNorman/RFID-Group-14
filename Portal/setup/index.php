<?php
$page = '';
    if (isset($_GET['issue'])) {
        $page = $_GET['issue'];
    } else {
        header("Location: ../index.php");
    }
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8" />
<title>Access Control Management System</title>

<link rel="stylesheet" href="../css/global-style.css">
</head>
<body>
    <div id="content" class="setup-content">
        <?php
            include 'content.php';
        ?>
    </div>
</body>
</html>
