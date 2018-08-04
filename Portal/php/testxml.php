<?php
    $doc = new DOMDocument('1.0');

    $doc->formatOutput = true;

    $root = $doc->createElement('connection');
    $root = $doc->appendChild($root);

    $server = $doc->createElement('server');
    $server = $root->appendChild($server);
    $serverAddress = $doc->createTextNode('10.0.0.0');
    $serverAddress = $server->appendChild($serverAddress);

    $user = $doc->createElement('dbuser');
    $user = $root->appendChild($user);
    $dbUser = $doc->createTextNode('root');
    $dbUser = $server->appendChild($dbUser);

    $pass = $doc->createElement('dbpass');
    $pass = $root->appendChild($pass);
    $dbPass = $doc->createTextNode('Password1');
    $dbPass = $server->appendChild($dbPass);

echo 'Result';

    

    

?>