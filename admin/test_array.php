<?php
    $array_1 = array("ão", utf8_decode("báã"),"ões");
    $array_2 = array("ão", "õe", "báã");
        
    $a = array_diff($array_1, $array_2);
    print_r($a);
    echo sizeof($a);    
?>
