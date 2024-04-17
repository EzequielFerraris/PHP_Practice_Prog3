<?php
//Autor: Ferraris Ezequiel Manuel
/*
Definir un Array de 5 elementos enteros y asignar a cada uno de ellos un número (utilizar la
función rand). Mediante una estructura condicional, determinar si el promedio de los números
son mayores, menores o iguales que 6. Mostrar un mensaje por pantalla informando el
resultado.
*/

$datos = array();

for($i=0;$i<5;$i++)
{   
    array_push($datos, rand());
}

$promedio = array_sum($datos) / count($datos);

if($promedio > 6)
{
    echo "El promedio es mayor que 6.";
    echo "<br/>";
    echo "Promedio: " . (string)$promedio;
}
else
{
    echo "El promedio es menor que 6.";
    echo "<br/>";
    echo "Promedio: " . (string)$promedio;
}

?>