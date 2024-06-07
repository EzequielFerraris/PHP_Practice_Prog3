<?php

include_once "validador.php";
include_once "ArchivosJSON.php";
class Heladeria
{
    public static string $nombreArchivo = "heladeria.json";
    public static string $rutaImagenes = './ImagenesDeHelados/2024/';
    public static $ruta = "./";
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
        $p2 = ArchivosJSON::leerJSON(Heladeria::$ruta, Heladeria::$nombreArchivo);
       
        if(count($p2) > 0)
        {
            foreach ($p2 as $helado)
            {
                
                if($helado["sabor"] == $sabor && $helado["tipo"] == $tipo && $helado["vaso"] == $vaso)
                {                 
                    $this->_exists = true;
                    $this->_ID = $helado["ID"];
                    break;
                }        
            }
            if(!$this->_exists)
            {
                $this->_ID = end($p2)["ID"] + 1;
            }
        }
        else
        {
            $this->_ID = 1; 
        }

        if(!Validador::es_string($sabor) || !Validador::es_float($precio) || 
            !Validador::es_string($tipo) || !Validador::es_string($vaso) || 
            !Validador::es_entero($stock))
        {
            $this->_valid = false;
        }

        $this->_sabor = $sabor;
        $this->_precio = $precio;
        $this->_tipo = $tipo;
        $this->_vaso = $vaso;
        $this->_stock = $stock; 
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

    public function getValid() : bool
    {
        return $this->_valid;
    }

    public function getExiste() : bool
    {
        return $this->_exists;
    }

    public function setStock(int $stock): bool
    {
        $result =  false;
        if(Validador::es_entero($stock))
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

    public function guardarHeladoJSON() : bool
    {
        $result = false;
        
        $listaHelados = ArchivosJSON::leerJSON(Heladeria::$ruta, Heladeria::$nombreArchivo);

        if($this->_exists && $this->_valid)
        {
            //SI EXISTE YA EN STOCK
            $data = $this->getData();
            $indice = Null; 
            foreach($listaHelados as $val => $p1)
            {
                
                if($data["sabor"] == $p1["sabor"] && $data["tipo"] == $p1["tipo"] && $data["vaso"] == $p1["vaso"])
                {
                    $indice = $val;
                    break;
                }
 
            }

            $condicion2 = ($data["stock"] < 0) && (-($data["stock"]) <= $listaHelados[$indice]["stock"]);
            
            if($data["stock"] > 0 || $condicion2)
            {
                $listaHelados[$indice]["stock"] += $data["stock"];
                $listaHelados[$indice]["precio"] = $data["precio"];
                $escribir = ArchivosJSON::escribirJSON(Heladeria::$ruta, Heladeria::$nombreArchivo, $listaHelados);
            
                if($escribir)
                { 
                    echo "Producto ACTUALIZADO</br>"; 
                    $result = true;
                }
                
            }
            else
            {
                echo "No se cuenta con la cantidad necesaria para actualizar el stock</br>";
            }

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

                $escribir = ArchivosJSON::escribirJSON(Heladeria::$ruta, Heladeria::$nombreArchivo, $listaHelados);
            
                if($escribir)
                { 
                    echo "Producto AGREGADO</br>"; 
                    $result = true;
                }
                
            }
            else
            {
                echo "NO SE PUDO REGISTRAR, PRODUCTO INVÁLIDO</br>";
            }
 
        }
        return $result;
    }
    

}

?>