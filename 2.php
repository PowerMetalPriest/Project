<?php

mb_internal_encoding("UTF-8");

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
    
    $a = "She saw murder, she saw killer. </br>";
    
    $b = "saw";
    
    convertString($a, $b);
    
} catch (Exception $ex) {
    echo 'Error: ', $ex->getMessage(), "\n";
}

function mySortForKey(&$a, $b){
    
    if(!isset($a[$b])){
        throw new Exception("Index does not exist: $b");
    }
    
    asort($a[$b]);
    
    print_r($a);
    
}

try {
    
    $array = [['a' => 2,'b' => 1],['a' => 1,'b' => 3]];
    $index = 0;
    
    mySortForKey($array, $index);
    
} catch (Exception $ex) {
    echo 'Error: ', $ex->getMessage(), "\n";
}

$xml = simplexml_load_file('xml.xml');

function importXml($a){

    $db = mysqli_connect('localhost', 'root', 'root', 'test_samson');
    
    if($db->connect_errno){ 
        die('Error: Unable to connect to database.');
    }
    
    foreach($p = [$a->Товар['Название'], $a->Товар['Код']] as $product){
        
        $sql = "ISERT INTO a_product (product, code) VALUES ($product[0], $product[1])";
        
        mysqli_query($db, $sql);
        
    }
    
    foreach($p = [$a->Товар->Цена['Тип'], $a->Товар->Цена] as $price){
        
        $sql = "ISERT INTO a_price (type, price) VALUES ($price[0], $price[1])";
        
        mysqli_query($db, $sql);
        
    }
    
    foreach($c = $a->Товар->Разделы->Раздел as $category){
        
        $sql = "ISERT INTO a_category (c_name) VALUES ($category)";
        
        mysqli_query($db, $sql);
        
    }
    
    foreach($p = $a->Товар->Свойства as $property){
        
        $sql = "ISERT INTO a_property (property) VALUES ($property)";
        
        mysqli_query($db, $sql);
        
    }
    
    mysqli_close($db);
    
}

importXml($xml);