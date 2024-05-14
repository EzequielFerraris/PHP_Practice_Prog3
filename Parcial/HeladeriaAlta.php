<?php

include_once "validador.php";
include_once "ArchivosJSON.php";
class Heladeria
{

    private string $_sabor;
    private float $_precio;
    private string $_tipo;
    private string $_vaso;
    private int $_stock = 0;
    private int $_ID;

    private bool $_exists =  false;
    private bool $_valid = true;


    public function __construct(string $sabor, float $precio, string $tipo, string $vaso, int $stock=0, int $ID = 0)
    {
        $p2 = ArchivosJSON::leerJSON("./", "heladeria.json");
       
        if(count($p2) > 0)
        {
            foreach ($p2 as $helado)
            {
                
                if($helado["sabor"] == $sabor && $helado["tipo"] == $tipo)
                {                 
                    $this->_stock = (int)$helado["stock"];
                    $this->_exists = true;
                    break;
                }        
            }
        }

        $this->_sabor = $sabor;
        $this->_precio = (float)$precio;
        $this->_tipo = $tipo;
        $this->_vaso = $vaso;
        if($this->_exists)
        {
            $sum = $this->setStock($stock); 
            if(!$sum)
            {
                $this->_valid = false;
            }
        }
        else
        {
            $this->_stock = $stock;
        }
        if($ID == 0)
        {
            $this->_ID = rand(1, 10000);
        }
        else
        {
            $this->_ID = $ID;
        }
        
    }
    public function getSabor() : string
    {
        return $this->_sabor;
    }

    public function getPrecio() : float
    {
        return $this->_precio;
    }

    public function getTipo() : string
    {
        return $this->_tipo;
    }

    public function getVaso() : string
    {
        return $this->_vaso;
    }

    public function getStock() : int
    {
        return $this->_stock;
    }

    public function getID() : int
    {
        return $this->_ID;
    }

    public function setStock(int $stock): bool
    {
        $result =  false;
        if(($this->_stock + $stock) >= 0)
        {
            $this->_stock += $stock;
            $result =  true;
        }
        return $result;
    }

    public function getData() : array
    {
        $data = array("sabor"=>$this->getSabor(), "precio"=>$this->getPrecio(), 
        "tipo"=>$this->getTipo(),  "vaso"=>$this->getVaso(), "stock"=>$this->getStock(), 
        "ID"=>$this->getID());
        
        return $data;
    }

    public function guardarHeladoJSON(string $ruta)
    {
        $nombreArchivo = "heladeria.json";
        $mensaje = "Uno o más de los campos está vacío o es incorrecto. Cambie la información necesaria e inténtelo nuevamente.";
        $listaHelados = ArchivosJSON::leerJSON("./", "heladeria.json");

        if($this->_exists && $this->_valid)
        {
            //SI EXISTE YA EN STOCK
            
            $data = $this->getData();
            $indice = Null; 
            foreach($listaHelados as $val => $p1)
            {
                
                if($data["sabor"] == $p1["sabor"] && $data["tipo"] == $p1["tipo"])
                {
                    $indice = $val;
                    break;
                }
 
            }

            $listaHelados[$indice]["stock"] = $data["stock"];
            $listaHelados[$indice]["precio"] = $data["precio"];
           
            $escribir = ArchivosJSON::escribirJSON($ruta, $nombreArchivo, $listaHelados);
            
            if($escribir){ echo "Producto ACTUALIZADO"; }

        }
        else if ($this->_valid)
        {
            //SI NO EXISTE
            if($this->_valid)
            {
                if(count($listaHelados) == 0)
                    {
                        $data = array($this->getData());
                        $listaHelados = $data;
                    }
                    else
                    {
                        $data = $this->getData();
                        array_push($listaHelados, $data);
                    }

                $escribir = ArchivosJSON::escribirJSON($ruta, $nombreArchivo, $listaHelados);
            
                if($escribir){ echo "Producto AGREGADO"; }
            }
            else
            {
                $mensaje = "NO SE PUDO REGISTRAR, PRODUCTO INVÁLIDO";
            }

            return $mensaje;
        }
    }
    

}

?>