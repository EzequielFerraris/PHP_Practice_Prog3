<?php

class Impresor
{
    public static function imprimirArrayDeArrays(array $a) : void 
    {
        foreach($a as $x)
        {
            foreach($x as $k=>$v)
            {
                echo "" . $k . ": " . $v . "</br>";
            }
            echo "</br></br>";            
        }
    }
}


?>