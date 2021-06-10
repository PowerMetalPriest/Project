<?php

function convertString(&$a, $b){
    
    $count = substr_count($a, $b);
    
    if($count < 2){
        throw new Exception("Too few substring occurrences: $count");
    }
    
    $rev_b = strrev($b);
    
    $a = explode($b, $a, 2);
    
    $a[0] .= $b;
    
    $a[1] = explode($b, $a[1], 2);
    
    $a[1][0] .= $rev_b;
    
    $a[1] = implode('', $a[1]);
    $a = implode('', $a);
    
    print_r($a);

}

try {
    
    $a = "She saw murder, she saw killer.";
    
    $b = "saw";
    
    convertString($a, $b);
    
} catch (Exception $ex) {
    echo 'Error: ', $ex->getMessage(), "\n";
}