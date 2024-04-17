<?php
//Autor: Ferraris Ezequiel Manuel
/*
Aplicación No 9 (Arrays asociativos)
Realizar las líneas de código necesarias para generar un Array asociativo $lapicera, que
contenga como elementos: ‘color’, ‘marca’, ‘trazo’ y ‘precio’. Crear, cargar y mostrar tres
lapiceras.
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

var_dump($lapicera1);
echo "<br/>";
var_dump($lapicera2);
echo "<br/>";
var_dump($lapicera3);
echo "<br/>";

?>