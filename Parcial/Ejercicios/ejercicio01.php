<?php
/*
Confeccionar un programa que sume todos los números enteros desde 1 mientras la suma no
supere a 1000. Mostrar los números sumados y al finalizar el proceso indicar cuantos números
se sumaron.
*/

    $numeros = 0;
    $suma = 0; 
    while ($suma + $numeros < 1000) 
    {
        $numeros++;
        $suma += $numeros;
    }

    echo "Se sumaron " . $numeros . " números.";
    echo "<br/>";
    echo "La suma es de " . $suma;
?>