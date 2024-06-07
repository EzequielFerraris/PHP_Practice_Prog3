<?php
//Autor: Ferraris Ezequiel Manuel
/*
Aplicación No 7 (Mostrar impares)
Generar una aplicación que permita cargar los primeros 10 números impares en un Array.
Luego imprimir (utilizando la estructura for) cada uno en una línea distinta (recordar que el
salto de línea en HTML es la etiqueta <br/>). Repetir la impresión de los números
utilizando las estructuras while y foreach.
*/


$count = 0;
$numero = 1;
$numerosImpares = array();
$j = 0;

while ($count < 10) 
{
    if($numero % 2 == 1)
    {
        array_push($numerosImpares, $numero);
        $count++;
    }
    $numero++;
}

for ($i = 0; $i < 10; $i++)
{
    echo $numerosImpares[$i];
    echo "<br/>";
}

echo "<br/>";

while($j < 10)
{
    echo $numerosImpares[$j];
    echo "<br/>";
    $j++;
}

echo "<br/>";

foreach ($numerosImpares as $value)
{
    echo $value;
    echo "<br/>";
}

echo "<br/>";



?>