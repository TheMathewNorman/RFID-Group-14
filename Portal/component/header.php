<?php
    include_once "../php/sessions.php";
    $sessions = new Sessions();
    $sessions->checkLogin();
?>
<div id="header">
    <div class="header-content" id="header-logo"><a href="index.php"><img src="../img/logo.png" alt="Brand logo"></a></div>
    <div class="header-content" id="header-title">Access Control System</div>
</div>