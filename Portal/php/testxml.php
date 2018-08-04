<?php
    $file = '/var/www/html/file.txt';
    $handle = fopen($my_file, 'w') or die('Cannot open file:  '.$my_file); //implicitly creates file

?>