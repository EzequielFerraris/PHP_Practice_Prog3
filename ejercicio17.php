<?php
//AUTOR: FERRARIS EZEQUIEL MANUEL

/*
Aplicación No 17 (Auto)
Realizar una clase llamada “Auto” que posea los siguientes atributos

privados: _color (String)
_precio (Double)
_marca (String).
_fecha (DateTime)

Realizar un constructor capaz de poder instanciar objetos pasándole como

parámetros: i. La marca y el color.
ii. La marca, color y el precio.
iii. La marca, color, precio y fecha.

Realizar un método de instancia llamado “AgregarImpuestos”, que recibirá un doble
por parámetro y que se sumará al precio del objeto.
Realizar un método de clase llamado “MostrarAuto”, que recibirá un objeto de tipo “Auto”
por parámetro y que mostrará todos los atributos de dicho objeto.
Crear el método de instancia “Equals” que permita comparar dos objetos de tipo “Auto”. Sólo
devolverá TRUE si ambos “Autos” son de la misma marca.
Crear un método de clase, llamado “Add” que permita sumar dos objetos “Auto” (sólo si son
de la misma marca, y del mismo color, de lo contrario informarlo) y que retorne un Double con
la suma de los precios o cero si no se pudo realizar la operación.
Ejemplo: $importeDouble = Auto::Add($autoUno, $autoDos);

En testAuto.php:
● Crear dos objetos “Auto” de la misma marca y distinto color.
● Crear dos objetos “Auto” de la misma marca, mismo color y distinto precio.
● Crear un objeto “Auto” utilizando la sobrecarga restante.

● Utilizar el método “AgregarImpuesto” en los últimos tres objetos, agregando $ 1500
al atributo precio.
● Obtener el importe sumado del primer objeto “Auto” más el segundo y mostrar el
resultado obtenido.
● Comparar el primer “Auto” con el segundo y quinto objeto e informar si son iguales o
no.
● Utilizar el método de clase “MostrarAuto” para mostrar cada los objetos impares (1, 3,
5)
*/

date_default_timezone_set("America/Argentina/Buenos_Aires");

class Auto
{
    private $_color;
    private $_precio;
    private $_marca;
    private $_fecha;

    public function __construct(string $marca, string $color, float $precio = 0.0, $fecha = new DateTime)
    {
        $this->_color = $color;
        $this->_precio = $precio;
        $this->_marca = $marca;
        $this->_fecha = $fecha;
    }

    public function get_color()
    {
        return $this->_color;
    }

    public function get_precio()
    {
        return $this->_precio;
    }

    public function get_marca()
    {
        return $this->_marca;
    }

    
    public function get_fecha()
    {
        return $this->_fecha->format("d/m/Y");
    }
    

    public function AgregarImpuestos(int $valor)
    {
        $this->_precio += $valor;
    }

    public static function MostrarAuto(Auto $auto)
    {
        echo $auto->get_marca() . "<br />";
        echo $auto->get_color() . "<br />";
        echo $auto->get_precio() . "<br />";
        echo $auto->get_fecha() . "<br />";
    }

    public static function Equals(Auto $auto1, Auto $auto2)
    {
        $result = false;

        if (strcmp($auto1->get_marca(), $auto2->get_marca()) == 0 && strcmp($auto1->get_color(), $auto2->get_color()) == 0) 
        {
            $result = true;
        }

        return $result;
    }

    public static function Add(Auto $autoUno, Auto $autoDos)
    {
        $result = 0;

        if (strcmp($autoUno->get_marca(), $autoDos->get_marca()) == 0 & strcmp($autoUno->get_color(), $autoDos->get_color()) == 0)
        {
            $result = $autoUno->get_precio() + $autoDos->get_precio();
        }

        return $result;
    }
}


?>