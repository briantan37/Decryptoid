<?php
    $tmp = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";

    echo encrypt("Ahimynameisbrian", str_shuffle($tmp));

    function encrypt($input, $key) {
        $output = "";
        $testArr = array(3)
        $keyArr = str_split($key);
        $inArr = str_split(strtoupper($input));
        foreach ($inArr as $element) {
            if(ctype_alpha($element)) {
                $asciiValue = ord($element);
                $output .= $keyArr[$asciiValue - 65];
            }
            else {
                $output .= $element;
            }
        }
        return $output;
    }
?>