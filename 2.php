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

$file = 'xml.xml';

function importXml($a){
    
    $xml = simplexml_load_file($a) or die("Error: Cannot create object");

    $db = mysqli_connect('localhost', 'root', 'root', 'test_samson');
    
    if($db->connect_errno){ 
        die("Unable to connect to database: " . mysqli_connect_error());
    }
    
    var_dump($xml);
    
    foreach ($xml->children() as $row){
        
        $name = $row->Товар['Название'];
        $code = $row->Товар['Код'];
        $price_type = $row->Цена['Тип'];
        $price = $row->Цена;
        $property = $row->Свойства;
        $category = $row->Раздеы->Раздел;
        
        $sql = "INSERT TO a_product (code, product_name) VALUES ($code, $name)";
        
        $result = mysqli_query($db, $sql);
        
        if(!empty($result)){
            echo "$name $code has been added. </br>";
        }
        
        $sql = "INSERT TO a_price (type, price) VALUES ($price_type, $price)";
        
        $result = mysqli_query($db, $sql);
        
        if(!empty($result)){
            echo "$price has been added. </br>";
        } 
        
        $sql = "INSERT TO a_property (property) VALUES ($property)";
        
        $result = mysqli_query($db, $sql);
        
        if(!empty($result)){
            echo "$property has been added. </br>";
        }
        
        $sql = "INSERT TO a_category (c_name) VALUES ($category)";
        
        $result = mysqli_query($db, $sql);
        
        if(!empty($result)){
            echo "$category has been added. </br>";
        }
        
    }
    
    mysqli_close($db);
    
}

importXml($file);