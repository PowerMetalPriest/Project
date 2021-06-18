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

    $link = mysqli_connect('localhost', 'root', 'root', 'test_samson');
    
    if($link->connect_errno){ 
        die("Unable to connect to database: " . mysqli_connect_error());
    }
    
    foreach($a->Товар['Название'] as $value){
        
        $sql = "INSETT TO a_product (product_name) VALUE ($value)";
        
        if (mysql_query($sql)){
            echo "Товар $value has been added..." ;
        }
    }
    
    foreach($a->Товар['Код'] as $value){
        
        $sql = "INSETT TO a_product (code) VALUE ($value)";
        
        if (mysql_query($sql)){
            echo "Код товара $value has been added..." ;
        } 
    }
    
    foreach($a->Товар->Цена['Тип'] as $value){
        
        $sql = "INSETT TO a_price (type) VALUE ($value)";
        
        if (mysql_query($sql)){
            echo "Тип цены $value has been added..." ;
        } 
    }
    
    foreach($a->Товар->Цена as $value){
        
        $sql = "INSETT TO a_price (price) VALUE ($value)";
        
        if (mysql_query($sql)){
            echo "Цена $value has been added..." ;
        } 
    }
    
    foreach($a->Товар->Свойства as $value){
        
        $sql = "INSETT TO a_property (property) VALUE ($value)";
        
        if (mysql_query($sql)){
            echo "Свойства $value has been added..." ;
        } 
    }
    
    foreach($a->Товар->Разделы->Раздел as $value){
        
        $sql = "INSETT TO a_category (c_name) VALUE ($value)";
        
        if (mysql_query($sql)){
            echo "Раздел $value has been added..." ;
        } 
    }
    
    mysqli_close($link);
    
}

importXml($xml);