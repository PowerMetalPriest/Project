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
    
    $tagName = iconv('cp1251', 'utf-8', 'Название');
    $tagCode = iconv('cp1251', 'utf-8', 'Код');
    $tagType = iconv('cp1251', 'utf-8', 'Тип');
    $tagProperty = iconv('cp1251', 'utf-8', 'Свойства');
    
    foreach ($xml->children() as $row) {
        
        $attributes = $row->attributes();
        
        $name = $attributes[$tagName];
        $code = $attributes[$tagCode];
        
        $sql = "INSERT INTO a_product (code, product_name) VALUES ('$code', '$name')";
        
        $result = mysqli_query($db, $sql) or die("Error: " . mysqli_error($db));
        
        echo "$name, $code has been added. </br>";
        
        foreach($row->children() as $subRow){
            
            $subAttributes = $subRow->attributes();
            
            $type = $subAttributes[$tagType];                
            
            if ($type !== NULL){ 
                
                $sql = "INSERT INTO a_price (type, price, code) VALUES ('$type', '$subRow', '$code')";
                $result = mysqli_query($db, $sql) or die("Error: " . mysqli_error($db));
        
                echo "$type, $subRow has been added. </br>";
            }
                
            foreach($subRow->children() as $subChild){
                
                $tag = $subChild->getName();
                $tagX = $subRow->getName();
                
                if($tagX === $tagProperty){
                    
                    $property = $tag . ' ' . $subChild;
                    $sql = "INSERT INTO a_property (code, property) VALUES ('$code', '$property')";
                    $result = mysqli_query($db, $sql) or die("Error: " . mysqli_error($db));
                
                    echo "$code, $property has been added. </br>";

                }else{
                        
                    $sql = "INSERT INTO a_category (code, c_name) VALUES ('$code', '$subChild')";
                    $result = mysqli_query($db, $sql) or die("Error: " . mysqli_error($db));
                
                    echo "$code, $subChild has been added. </br>";
                    
                }
            }        
                
        }
    }    

    
    mysqli_close($db);
    
}

importXml($file);


$fileEx = 'export.xml';

$cCode = 32;

function exportXml($a, $b){
    
    $xml = simplexml_load_file($a) or die("Error: Cannot create object");

    $db = mysqli_connect('localhost', 'root', 'root', 'test_samson');
    
    if($db->connect_errno){ 
        die("Unable to connect to database: " . mysqli_connect_error());
    }
    
    $id = $db->query("SELECT c_name FROM a_category WHERE id_category = $b");          
    $c_name = $id->fetch_assoc(); 
    
    var_dump($c_name[c_name]);
    
    $sql_c = $db->query("SELECT code FROM a_category WHERE c_name = '$c_name[c_name]'");
    $code = $sql_c->fetch_assoc();
    
    var_dump($code[code]);
    
    $sql_n = $db->query("SELECT product_name FROM a_product WHERE code = $code[code]");
    $name = $sql_n->fetch_assoc();
    
    var_dump($name[product_name]);
    
    $sql_price = $db->query("SELECT type, price FROM a_price WHERE code = $code[code]");
    $price = $sql_price->fetch_assoc();
        
    var_dump($price);
    
    $sql_property = $db->query("SELECT property FROM a_property WHERE code = $code[code]");
    $property = $sql_property->fetch_assoc();
    
    var_dump($property);
    
    mysqli_close($db);
    
}   


exportXml($fileEx, $cCode);