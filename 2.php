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
    
    return $a;

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
    
    return $a;
    
}

try {
    
    $array = [['a' => 2,'b' => 1],['a' => 1,'b' => 3]];
    $index = 0;
    
    mySortForKey($array, $index);
    
} catch (Exception $ex) {
    echo 'Error: ', $ex->getMessage(), "\n";
}

Class Datebase{
    
    private $link;
    
    public function __construct(){
        
        $this->connect();
        
    }
    
    private function connect(){
        
        $this->link = new PDO("mysql:host=localhost;dbname=test_samson;charset=utf8" , "root", "root");
        
        return $this;
        
    }
    
    public function execute($sql){
        
        $sth = $this->link->prepare($sql);
        
        return $sth->execute();
        
    }
    
    public function query($sql){
        
        $sth = $this->link->prepare($sql);
        
        $sth->execute();
        
        $result = $sth->fetchAll(PDO::FETCH_ASSOC);
        
        if($result === false){
            return [];
        }
        
        return $result;
        
    }   
}

$file = 'xml.xml';

function importXml($a){
    
    $xml = simplexml_load_file($a) or die("Error: Cannot create object");

    $db = new Datebase();
    
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
        
        $db->execute("INSERT INTO a_product (code, product_name) VALUES ('$code', '$name')");
        
        echo "$name, $code has been added. </br>";
        
        foreach($row->children() as $subRow){
            
            $subAttributes = $subRow->attributes();
            
            $type = $subAttributes[$tagType];                
            
            if ($type !== NULL){ 
                
                $db->execute("INSERT INTO a_price (type, price, code) VALUES ('$type', '$subRow', '$code')");
        
                echo "$type, $subRow has been added. </br>";
            }
                
            foreach($subRow->children() as $subChild){
                
                $tag = $subChild->getName();
                $tagX = $subRow->getName();
                
                if($tagX === $tagProperty){
                    
                    $property = $tag . ' ' . $subChild;
                    $db->execute("INSERT INTO a_property (code, property) VALUES ('$code', '$property')");

                    echo "$code, $property has been added. </br>";

                }else{
                        
                    $db->execute("INSERT INTO a_category (code, c_name) VALUES ('$code', '$subChild')");
                
                    echo "$code, $subChild has been added. </br>";
                    
                }
            }               
        }
    }   
}

importXml($file);

$fileEx = 'export.xml';

$cCode = 7;

function exportXml($a, $b){
    
    $xml = simplexml_load_file($a) or die("Error: Cannot create object");
    $newElement = simplexml_load_string(file_get_contents($a)) or die("Error: Cannot create object");

    $db = new Datebase();
    
    if($db->connect_errno){ 
        die("Unable to connect to database: " . mysqli_connect_error());
    }
    
    $c_name = $db->query("SELECT c_name FROM a_category WHERE id_category = $b");          
    
    foreach($db->query("SELECT code FROM a_category WHERE c_name = '$c_name[c_name]'") as $code){
        
        $name = $db->query("SELECT product_name FROM a_product WHERE code = '$code[code]'");

        $product = $newElement->addChild("Товар");
        $product->addAttribute("Название", $name[name]);
        $product->addAttribute("Код", $code[code]);
        
        foreach($db->query("SELECT type, price FROM a_price WHERE code = '$code[code]'") as $price){

            $priceB = $product->addChild("Цена", $price[price]);
            $priceB->addAttribute("Тип", $price[type]);
            $priceM = $product->addChild("Цена", $price[price]);
            $priceM->addAttribute("Тип", $price[type]);
            
        }
        
        $properties = $product->addChild("Свойства");
        
        foreach($db->query("SELECT property FROM a_property WHERE code = '$code[code]'") as $property){
            
            $productProperty = $properties->addChild("$property[property]");
            
        }
        
        $categories = $product->addChild("Разделы");
        
        foreach($db->query("SELECT c_name FROM a_category WHERE code = '$code[code]'") as $category){
            
            $categoryProduct = $categories->addChild("Раздел", $category[c_name]);
            
        }

    }
    
}   


exportXml($fileEx, $cCode);