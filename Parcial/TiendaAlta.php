<?php

include_once "validador.php";
include_once "ArchivosJSON.php";
class TiendaAlta
{
    
    public static string $nombreArchivo = "tienda.json";
    public static string $rutaImagenes = './ImagenesDeRopa/2024/';
    public static $ruta = "./";
    private string $_nombre;
    private float $_precio;
    private string $_tipo;
    private string $_talla;
    private string $_color;
    private int $_stock = 0;
    private int $_ID;

    private bool $_exists =  false;
    private bool $_valid = true;

    public function __construct(string $nombre, float $precio, string $tipo, string $talla, string $color, int $stock=0)
    {
        $p2 = ArchivosJSON::leerJSON(self::$ruta, self::$nombreArchivo);
       
        if(count($p2) > 0)
        {
            foreach ($p2 as $prenda)
            {
                
                if($prenda["nombre"] == $nombre && $prenda["tipo"] == $tipo && $prenda["talla"] == $talla 
                    && $prenda["color"] == $color)
                {                 
                    $this->_exists = true;
                    $this->_ID = $prenda["ID"];
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

        if(!Validador::es_string($nombre) || !Validador::es_float($precio) || 
            !Validador::es_string($tipo) || !Validador::es_string($talla) || 
            !Validador::es_string($color) || !Validador::es_entero($stock))
        {
            $this->_valid = false;
        }

        $this->_nombre = $nombre;
        $this->_precio = $precio;
        $this->_tipo = $tipo;
        $this->_talla = $talla;
        $this->_color = $color;
        $this->_stock = $stock; 
    }

    public function getNombre() : string
    {
        return $this->_nombre;
    }

    public function getPrecio() : float
    {
        return $this->_precio;
    }

    public function getTipo() : string
    {
        return $this->_tipo;
    }

    public function getTalla() : string
    {
        return $this->_talla;
    }

    public function getColor() : string
    {
        return $this->_color;
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
        $data = array("nombre"=>$this->getNombre(), "precio"=>$this->getPrecio(), 
        "tipo"=>$this->getTipo(),  "talla"=>$this->getTalla(), "color"=>$this->getColor(), 
        "stock"=>$this->getStock(), "ID"=>$this->getID());
        
        return $data;
    }
    
    public function guardarPrendaJSON() : bool
    {
        $result = false;
        
        $listaPrendas = ArchivosJSON::leerJSON(self::$ruta, self::$nombreArchivo);

        if($this->_exists && $this->_valid)
        {
            //SI EXISTE YA EN STOCK
            $data = $this->getData();
            $indice = Null; 
            foreach($listaPrendas as $val => $p1)
            {
                
                if($data["nombre"] == $p1["nombre"] && $data["tipo"] == $p1["tipo"] 
                    && $data["talla"] == $p1["talla"] && $data["color"] == $p1["color"])
                {
                    $indice = $val;
                    break;
                }
 
            }

            $condicion2 = ($data["stock"] < 0) && (-($data["stock"]) <= $listaPrendas[$indice]["stock"]);
            
            if($data["stock"] > 0 || $condicion2)
            {
                $listaPrendas[$indice]["stock"] += $data["stock"];
                $listaPrendas[$indice]["precio"] = $data["precio"];
                $escribir = ArchivosJSON::escribirJSON(self::$ruta, self::$nombreArchivo, $listaPrendas);
            
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
                if(count($listaPrendas) == 0)
                    {
                        $data = array($this->getData());
                        $listaPrendas = $data;
                    }
                    else
                    {
                        $data = $this->getData();
                        array_push($listaPrendas, $data);
                    }

                $escribir = ArchivosJSON::escribirJSON(self::$ruta, self::$nombreArchivo, $listaPrendas);
            
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
/*
Nombre, Precio, Tipo (“Camiseta” o “Pantalón”), Talla (“S”, “M”,
“L”), Color, Stock (unidades).

*/

?>