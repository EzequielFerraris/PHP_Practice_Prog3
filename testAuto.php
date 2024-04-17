<?php 

include_once "./ejercicio17.php";

$auto1 = new Auto("Volvo", "verde", 1000000);
$auto2 = new Auto("Volvo", "gris");
$auto3 = new Auto("Ferrari", "rojo", 1000000);
$auto4 = new Auto("Ferrari", "rojo", 1500000);
$auto5 = new Auto("Fitito", "amarillo", 20000);

/*
var_dump($auto1);
echo "<br/>";
var_dump($auto2);
echo "<br/>";
var_dump($auto3);   
echo "<br/>";
var_dump($auto4);
echo "<br/>";   
var_dump($auto5);  
*/

$auto3->AgregarImpuestos(1500);
$auto4->AgregarImpuestos(1500);
$auto5->AgregarImpuestos(1500);

/*
Auto::MostrarAuto($auto3);
echo "<br/>";
*/

$importeSumado = Auto::Add($auto1, $auto2);
echo "El importe del primer auto más el segundo es: $" . $importeSumado . "<br/>";

$result1;

if (Auto::Equals($auto1, $auto2))
{
    $result1 = "Si.";
}
else
{
    $result1 = "No.";
}

echo "<br/>";
echo "¿Son iguales el primer y el segundo auto? Rta: " . $result1;

$result2;

if (Auto::Equals($auto1, $auto5))
{
    $result2 = "Si.";
}
else
{
    $result2 = "No.";
}

echo "<br/>";
echo "¿Son iguales el primer y el quinto auto? Rta: " . $result2;


echo "<br/><br/>";
Auto::MostrarAuto($auto1);
echo "<br/>";
Auto::MostrarAuto($auto3);
echo "<br/>";
Auto::MostrarAuto($auto5);
echo "<br/>";


?>