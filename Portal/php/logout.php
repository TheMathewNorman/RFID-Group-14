<?php
session_start();

include_once "sessions.php";
$sessions = new Sessions();

$sessions->endSession(); // End current session
header("Location: ../index.php?message=logout"); // Redirecting To Home Page

?>
