<?php
//AUTOR: FERRARIS EZEQUIEL MANUEL
/*
Aplicación No 12 (Invertir palabra)
Realizar el desarrollo de una función que reciba un Array de caracteres y que invierta el orden
de las letras del Array.
Ejemplo: Se recibe la palabra “HOLA” y luego queda “ALOH”.
*/

function invertir($arr)
{
    $newArr = array();

    foreach ($arr as $value) 
    {
        array_unshift($newArr, $value);
    }

    return $newArr;
}

$randomArr = array("a", "b", "c");

$print = invertir($randomArr);

var_dump($print);

?>