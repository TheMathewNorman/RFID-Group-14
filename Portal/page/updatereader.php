<?php
    if (!isset($_GET['id'])) {
        header("Location: listreaders.php");
    }

    include_once "../php/database.php";
    include_once "../php/validate.php";
    $database = new Database();
    $validate = new Validate();

    // Used form with information as placeholder text.
    $readerInfo = $database->fetchReaderInfo($_GET['id']);

    // Get default state for "approved" checkbox
    $approved = "";
    if ($readerInfo['approved'] == 1) {
        $approved = "checked";
    }

    if (isset($_POST['delete']) && $_POST['delete'] === "true") {
        header("Location: ../php/deletereader.php?id=".$_GET['id']);
    }
    
    if (isset($_POST['reader_name']) || isset($_POST['reader_group']) || isset($_POST['approved'])) {
        // Set approved to 0 if the value is not 1
        if ($_POST['approved'] != 1) { $_POST['approved'] = 0; }
        
        // Sanitize the reader_name input
        $_POST['reader_name'] = $validate->sanitizeString($_POST['reader_name']);
        
        // Update the reader information and redirect
        $database->updateReader($_GET['id'], $_POST['reader_name'], $_POST['reader_group'], $_POST['approved']);
        header("Location: listreaders.php");
    } 
?>
<html>
    <head>
        <title>Update Reader</title>
        <link rel="stylesheet" type="text/css" href="../css/global-style.css">
        <link rel="stylesheet" type="text/css" href="../css/header.css">
        <link rel="stylesheet" type="text/css" href="../css/menu.css">
    </head>
    <body>
        <div id="content">
        
        <?php include "../component/header.php"; ?>
        <?php include "../component/menu.php"; ?>
            
            <form action="" method="POST">
                <table class="form-table">
                <tr><td style="text-align:right">Reader name: </td><td><input type="text" name="reader_name" value="<?php echo $readerInfo['reader_name']; ?>" required></td></tr>
                <tr><td style="text-align:right">Reader group: </td><td><input type="number" name="reader_group" min="1" max="99" value="<?php echo $readerInfo['reader_group']; ?>"></td></tr>
                <tr><td style="text-align:right">Approved:</td><td><input type="checkbox" name="approved" value="1" <?php echo $approved; ?>></td></tr>
                <tr><td></td></tr>
                <tr><td style="text-align:right">Delete:</td><td><input type="checkbox" name="delete" value="true"></td></tr>
                <tr><td colspan="2" style="text-align:right"><input type="submit" value="Update Reader"> <input type="reset" value="Clear"></td></tr>
                </table>
            </form>
        </div>
    </body>
</html>