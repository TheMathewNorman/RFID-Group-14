<?php
    $file = '/var/www/html/file.txt';
    $handle = fopen($file, 'w') or die('Cannot open file:  '.$file); //implicitly creates file

?>