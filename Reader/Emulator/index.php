<?php
    $GLOBALS['server'] = "http://therfid.men/php/reader/authenticate.php";
    echo "Server is: <i>".$GLOBALS['server']."</i><br><br><br>";

    function printResult($info) {
        $result = $info['result'] ? "Valid.<br>" : "Invalid.<br>";
        echo "<b>New Scan</b><br>";
        echo "Card ID: ".$info['card']."<br>";
        echo "Reader ID: ".$info['reader']."<br>";
        echo "Result plain: ". $info['result']."<br>";
        echo "Result worded: ". $result;
        echo "<hr><br>";
    }

    include_once './function/card.php';
    $card = new Card;

    
    $info['card'] = 9988;
    $info['reader'] = 10;
    $info['result'] = $card->scan($info['card'], $info['reader']);
    printResult($info);

    $info['card'] = rand(1000, 9999);
    $info['reader'] = rand(1, 99);
    $info['result'] = $card->scan($info['card'], $info['reader']);
    printResult($info);

?>
