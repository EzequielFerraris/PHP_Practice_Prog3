<?php 
//AUTOR FERRARIS EZEQUIEL MANUEL 
/*
Aplicación No 18 (Auto - Garage)
Crear la clase Garage que posea como atributos privados:

_razonSocial (String)
_precioPorHora (Double)
_autos (Autos[], reutilizar la clase Auto del ejercicio anterior)
Realizar un constructor capaz de poder instanciar objetos pasándole como parámetros: 
i. La razón social.
ii. La razón social, y el precio por hora.

Realizar un método de instancia llamado “MostrarGarage”, que no recibirá parámetros y
que mostrará todos los atributos del objeto.

Crear el método de instancia “Equals” que permita comparar al objeto de tipo Garaje con un
objeto de tipo Auto. Sólo devolverá TRUE si el auto está en el garaje.

Crear el método de instancia “Add” para que permita sumar un objeto “Auto” al “Garage”
(sólo si el auto no está en el garaje, de lo contrario informarlo).
Ejemplo: $miGarage->Add($autoUno);

Crear el método de instancia “Remove” para que permita quitar un objeto “Auto” del
“Garage” (sólo si el auto está en el garaje, de lo contrario informarlo). Ejemplo:
$miGarage->Remove($autoUno);

En testGarage.php, crear autos y un garage. Probar el buen funcionamiento de todos
los métodos.
*/
include_once "./ejercicio17.php"; 

class Garage 
{
    private $_razonSocial;
    private $_precioPorHora;
    private $_autos = array();
    
    public function __construct(string $_razonSocial, float $_precioPorHora = 0) 
    {
        $this->_razonSocial = $_razonSocial;
        $this->_precioPorHora = $_precioPorHora;
    }

    public function get_razonSocial()
    {
        return $this->_razonSocial;
    }
    public function get_precioPorHora()
    {
        return $this->_precioPorHora;
    }
    public function get_autos()
    {
        return $this->_autos;
    }

    public function MostrarGarage()
    {
        echo "<br/>";
        echo $this->get_razonSocial() . "<br/>";
        echo $this->get_precioPorHora() . "<br/>";
        echo "Autos: <br/>";
        foreach($this->_autos as $auto)
        {
            Auto::MostrarAuto($auto);
        }
        echo "<br/>";
    }

    public function Equals($auto)
    {
        if($auto instanceof Auto)
        {
            $yaEnGarage = false;

            foreach($this->_autos as $vehiculo)
            {
                if(Auto::Equals($vehiculo, $auto))
                {
                    $yaEnGarage = true;
                    break;
                }
            }
            
            return $yaEnGarage;
        }
    }
    public function Add(auto $auto)
    {
        $yaEnGarage = $this->Equals($auto);

        if($yaEnGarage)
        {
            echo "El auto ya se encuentra en el Garage.";
            echo "<br/>";
        }
        else
        {
            array_push($this->_autos, $auto);
            echo "El auto se ha incorporado al Garage.";
            echo "<br/>";
        }        
        
    }

    public function Remove(auto $auto)
    {
        $yaEnGarage = $this->Equals($auto);

        if($yaEnGarage)
        {
            foreach($this->_autos as $key => $vehiculo)
            {
                if(Auto::Equals($vehiculo, $auto))
                {
                    array_splice($this->_autos, $key, 1);
                    break;
                }
            }
            echo "El auto ha sido removido del Garage.";
            echo "<br/>";
        }
        else
        {
            echo "El auto no se encuentra en el Garage.";
            echo "<br/>";
        }        
        
    }

}

?>