<?php 

include_once "./ejercicio17.php";
include_once "./ejercicio18.php";

$auto1 = new Auto("Volvo", "verde", 1000000);
$auto2 = new Auto("Volvo", "gris");
$auto3 = new Auto("Ferrari", "rojo", 1000000);
$auto4 = new Auto("Ferrari", "azul", 1500000);
$auto5 = new Auto("Fitito", "amarillo", 20000);
$auto6 = new Auto("Volvo", "verde", 1000000);

$miGarage = new Garage("Los hermanos srl.", 2000);

$miGarage->Add($auto1);
$miGarage->Add($auto2);
$miGarage->Add($auto3);
$miGarage->Add($auto4);
$miGarage->Add($auto5);
$miGarage->Add($auto6);

$miGarage->MostrarGarage();

$miGarage->Remove($auto1);
$miGarage->Add($auto1);
$miGarage->Add($auto6);
$miGarage->Remove($auto6);
$miGarage->Remove($auto6);

$miGarage->MostrarGarage();

?>