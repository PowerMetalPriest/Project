<?php

function findSimple(int $a, int $b){
    
    $arr = [];
    
    if ($a > $b){
        
        throw new Exception("$a > $b");
     
        }
    
    foreach(range($a, $b) as $item){ 
        if(gmp_prob_prime($item) === 2) array_push($arr, $item);  
        
    }
    return (array)$arr;
}

try {
    
    $arr = findSimple(1, 10);
    
} catch (Exception $ex) {
    echo 'Error : ', $ex->getMessage(), "\n";
}


var_dump($arr);

echo '<br> <br>';


$a = [1, 2, 3, 4, 5, 6, 2, 2, 2];                                                // Массив для трапеции

function createTrapeze(array $a){

    if (count($a) % 3 !== 0){
    
        throw new Exception('Massive not % 3');
    }
    
    $a = array_chunk($a, 3);
        
    $keys = [
        'a' => 'a',
        'b' => 'b',
        'c' => 'c'
    ];
        
    foreach ($a as $key => $trapeze){
            
        $a[$key] = array_combine($keys, $trapeze);
    }
        
    return (array)$a;
}
try {
    
    $newTrapeze = createTrapeze($a);
    
} catch (Exception $ex) {
    echo 'Error : ', $ex->getMessage(), "\n";
}

var_dump($newTrapeze);

echo '<br> <br>';

function squareTrapeze(&$a = []){
    
    $i = 0;
    
    foreach($a as $trapeze){
        
                
        $a[$i]['s'] = (($trapeze['a'] + $trapeze['b']) / 2) * $trapeze['c'];
        $i++; 
    }
}

squareTrapeze($newTrapeze);

var_dump($newTrapeze);

echo '<br> <br>';

function getSizeForLimit(array $a, float $b){
    
    $maxS = 0;
    
    $limit = [];
    
    foreach($a as $trapeze){
        
        if($trapeze['s'] <= $b && $trapeze['s'] > $maxS){
            
            $maxS = $trapeze['s'];
            $limit = $trapeze;
            
        }
    }
    
    if($maxS == 0) echo 'There is no array satisfying the value $b = ' . $b;
    
    return (array)$limit;
    
}

$limit = getSizeForLimit($newTrapeze, 4);                                        // Вызов getSizeLimit 

var_dump($limit);

echo '<br> <br>';

function getMin($a = []){
    
    $min = null;
    
    foreach($a as $value){
        
        if($value < $min || $min == null) $min = $value;
        
    }
    
    return (float)$min;
}

$someArr = ['a' => 2, 1 => 6, 'e' => 5];                                         // Массив
$min = getMin($someArr);                                                                // Вызов getMin

var_dump($min);

echo '<br> <br>';

function printTrapeze($a = []){
        
        echo "<table border = '1' cellpadding = '6' cellspacing = '0'>";
        
        foreach($a as $trapeze){ 
            echo "<tr>";
            
            foreach($trapeze as $key => $item){
                
                if((($item % 2) != 0 || is_float($item) == true) && $key == 's'){
                     echo "<td> $item </td> <td bgcolor = 'red'> </td>"; 
                }else{
                     echo "<td> $item </td>";
                }
            }
            echo "</tr>";  
        }
        echo "</table>";     
}

printTrapeze($newTrapeze);                                                       // Вызов printTrapeze 

echo '<br> <br>';

abstract class BaseMath{
    
    public function exp1($a, $b, $c){
        
        $exp1 = $a * pow($b, $c);
        
        return $exp1;
        
    }
    
    public function exp2 ($a, $b, $c){
        
        $exp2 = pow($a / $b, $c);
        
        return $exp2;
        
    }
    
    abstract public function getValue();
    
}

class F1 extends BaseMath{
    
    public $a;
    public $b;
    public $c;
    
    public function __construct($a, $b, $c){
        
        $this->a = $a;
        $this->b = $b;
        $this->c = $c;
       
    }
    
    public function getValue(){
        
        $a = $this->a;
        $b = $this->b;
        $c = $this->c;
        
        $exp1 = parent::exp1($a, $b, $c);
        
        $f = ($exp1) + pow((pow($a/$c, $b) % 3), min($a, $b, $c));
        
        return $f;
        
    }  
    
}

$val = new F1(1, 2, 3);
echo 'F = ' .  $val->getValue();