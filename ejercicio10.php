<?php
//Autor: Ferraris Ezequiel Manuel
/*
Aplicación No 10 (Arrays de Arrays)
Realizar las líneas de código necesarias para generar un Array asociativo y otro indexado que
contengan como elementos tres Arrays del punto anterior cada uno. Crear, cargar y mostrar los
Arrays de Arrays.
*/


$keys = array("color", "marca", "trazo", "precio");
$values1 = array("rojo", "Bic", "fino", 500);
$values2 = array("verde", "Pilot", "grueso", 700);
$values3 = array("azul", "Micro", "fino", 1000);

$lapicera1 = array();
$lapicera2 = array();
$lapicera3 = array();

for ($i = 0; $i < 4; $i++)
{
    $lapicera1[$keys[$i]] = $values1[$i];
    $lapicera2[$keys[$i]] = $values2[$i];
    $lapicera3[$keys[$i]] = $values3[$i];
}

$cartuchera1 = array($lapicera1, $lapicera2, $lapicera3);
$cartuchera2 = array("lapicera1" => $lapicera1, "lapicera2" =>$lapicera2, "lapicera3" =>$lapicera3);


var_dump($cartuchera1);
echo "<br/><br/>";
var_dump($cartuchera2);

?>