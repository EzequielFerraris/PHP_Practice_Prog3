
<?php

/*Realizar un programa que en base al valor numérico de una variable $num, pueda mostrarse
por pantalla, el nombre del número que tenga dentro escrito con palabras, para los números
entre el 20 y el 60.
Por ejemplo, si $num = 43 debe mostrarse por pantalla “cuarenta y tres”.*/

$num = 25;
$result = "Numero fuera de rango.";

$num2 = (string)$num;

if($num > 60 | $num2 < 20) 
{
    echo $result;
    return;
}

switch ($num2[0]) 
{
    case "2":
        if (strcmp($num2[1], "0") == 0)
        {
            $result = "Veinte";
        }
        else
        {
            $result = "Veinti";
        }
    break;
    case "3":
        $result = "Treinta";
    break;
    case "4":
        $result = "Cuarenta";
    break;
    case "5":
        $result = "Cincuenta";
    break;
    case "6":
        echo $result = "Sesenta";
    break;
}

if (strcmp($num2[0], "2") != 0 && strcmp($num2[1], "0") != 0)
{
    $result .= " y ";
}

switch ($num2[1])
{
    case "0":
    break;    
    case "1":
        $result .= "uno";
    break;
    case "2":
        $result .= "dos";
    break;
    case "3":
        $result .= "tres";
    break;
    case "4":
        $result .= "cuatro";
    break;
    case "5":
        $result .= "cinco";
    break;
    case "6":
        $result .= "seis";
    break;
    case "7":
        $result .= "siete";
    break;
    case "8":
        $result .= "ocho";
    break;
    case "9":
        $result .= "nueve";
    break;
}

echo $result;

?>