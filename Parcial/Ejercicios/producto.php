<?php

class Producto 
{

    private $_codigo = "";
    private $_nombre = "";
    private $_tipo = "";
    private $_stock = 0;
    private $_precio = 0;
    private $_ID;
    private $_valid = true;
    private $_exists = false;
    private $_actualizado = false;
    

    public function __construct(string $codigo, string $nombre, string $tipo, int $stock, float $precio, int $ID=0)
    {
        $p2 = Producto::mostrarProductosJson();
       
        if(count($p2) > 0)
        {
            foreach ($p2 as $producto)
            {
                
                if($producto["codigo"] == $codigo & $producto["nombre"] == $nombre & $producto["tipo"] == $tipo)
                {                 
                    $this->_exists = true;
                    break;
                }        
            }
        }
        
        $parameters = array($codigo, $nombre, $tipo, $stock, $precio, $ID); 

            for ($i = 0; $i < count($parameters); $i++)
            {
                switch ($i)
                {
                    case "0":
                        if(is_string($parameters[$i]) & strlen($codigo) == 6)
                        {
                            $this->_codigo = $codigo;
                        }
                        else
                        {
                            $this->_valid = false;
                        }
                    break;
                    case "1":
                        if(is_string($parameters[$i]) & strlen($codigo) > 0)
                        {
                            $this->_nombre = $parameters[$i];
                        }
                        else
                        {
                            $this->_valid = false;
                        }
                    break;
                    case "2":
                        if(is_string($parameters[$i]) & strlen($codigo) > 0)
                        {
                            $this->_tipo = $parameters[$i];
                        }
                        else
                        {
                            $this->_valid = false;
                        }
                    break;
                    case "3":
                        if(is_int($parameters[$i]) & $parameters[$i] > 0)
                        {
                            $this->_stock = $parameters[$i];
                        }
                        else
                        {
                            $this->_valid = false;
                        }
                    break;
                    case "4":
                        if(is_float($parameters[$i]) & $parameters[$i] >= 0)
                        {
                            $this->_precio = $parameters[$i];
                        }
                        else
                        {
                            $this->_valid = false;
                        }
                    break;
                    case "5":
                        if($parameters[$i] == 0)
                        {
                            $this->_ID = rand(1, 10000);
                        }
                        else 
                        {
                            $this->_ID = $ID;
                        }
                    break;
                    default:
                        $this->_valid = false;
                    break;     
                }
        
            }
    }

    //GETTERS
    public function getCodigo(): string
    {
        return $this->_codigo;
    }

    public function getNombre(): string
    {
        return $this->_nombre;
    }

    public function getTipo(): string
    {
        return $this->_tipo;
    }

    public function getStock(): int
    {
        return $this->_stock;
    }   

    public function getPrecio(): int
    {
        return $this->_precio;
    }

    public function getID(): int
    {
        return $this->_ID;
    }

    public function getValid(): bool
    {
        return $this->_valid;
    }

    public function setStock(int $stock): void
    {
        if($stock <= $this->_stock)
        {
            $this->_stock += $stock;
        }
    }

    public function getData() : array
    {
        $data = array("codigo"=>$this->getCodigo(), "nombre"=>$this->getNombre(), 
        "tipo"=>$this->getTipo(), "stock"=>$this->getStock(), "precio"=>$this->getPrecio(), 
        "ID"=>$this->getID());
        
        return $data;
    }

    public function guardarProductoJSON(string $ruta)
    {
        $nombreArchivo = "productos.json";
        $mensaje = "Uno o más de los campos está vacío o es incorrecto. Cambie la información necesaria e inténtelo nuevamente.";
        
        if($this->_exists)
        {
            //SI EXISTE YA EN STOCK
            
            $listaProductos = Producto::mostrarProductosJson();

            $data = $this->getData();
            $indice = Null; 
            foreach($listaProductos as $val => $p1)
            {
                
                if($data["codigo"] == $p1["codigo"] & $data["nombre"] == $p1["nombre"] & $data["tipo"] == $p1["tipo"])
                {
                    $indice = $val;
                    break;
                }
 
            }

            $listaProductos[$indice]["stock"] = $data["stock"];
            $listaProductos[$indice]["precio"] = $data["precio"];
           

            try
                {
                    $file = fopen($ruta . $nombreArchivo, "w");
                    $json = json_encode($listaProductos);
                    $chars = fwrite($file, $json);

                    fclose($file);

                    if($chars > 0)
                    {
                        $mensaje = "Producto ACTUALIZADO.";
                    }

                }
                catch(Exception $e)
                {
                    echo "<br/>";
                    echo "". $e->getMessage() ."";
                    echo "<br/>";
                }
        }
        else
        {
            $listaProductos = Producto::mostrarProductosJson();
            //SI NO EXISTE
            if($this->_valid)
            {
                try
                {
                    $file = fopen($ruta . $nombreArchivo, "w");
                    if(count($listaProductos) == 0)
                    {
                        $data = array($this->getData());
                        $listaProductos = $data;
                    }
                    else
                    {
                        $data = $this->getData();
                        array_push($listaProductos, $data);
                    }

                    $json = json_encode($listaProductos);
                    $chars = fwrite($file, $json);

                    fclose($file);

                    if($chars > 0)
                    {
                        $mensaje = "Producto AGREGADO.";
                    }

                }
                catch(Exception $e)
                {
                    echo "<br/>";
                    echo "". $e->getMessage() ."";
                    echo "<br/>";
                }
            }
            else
            {
                $mensaje = "NO SE PUDO REGISTRAR";
            }      
        }

        return $mensaje;
    }

    public static function mostrarProductosJson()
    {
        $resultado = array();

        if (file_exists("./productos.json"))
        {
            try
            {
                $file = fopen("./productos.json", "r");
    
                $contenido = fread($file, filesize("./productos.json"));
                $arrayDeProductos = json_decode($contenido, true);
                
                               
                fclose($file);
                
                $resultado = $arrayDeProductos;

            }
            catch(Exception $e)
            {
                echo "<br/>";
                echo "". $e->getMessage() ."";
                echo "<br/>";
            }
        }
        
        return $resultado;
    }
    
    public static function productoDesdeArray(array $productoNuevo)
    {
        switch(count($productoNuevo))
        {
            case 5:
                $productoInstanciado = new Producto($productoNuevo["codigo"], $productoNuevo["nombre"], $productoNuevo["tipo"], $productoNuevo["stock"], $productoNuevo["precio"]);
            break;
            case 6:
                $productoInstanciado = new Producto($productoNuevo["codigo"], $productoNuevo["nombre"], $productoNuevo["tipo"], $productoNuevo["stock"], $productoNuevo["precio"], $productoNuevo["ID"]);
            break;
            default:
                echo "Error. Parámetro desconocido.";
            break;            
        }
        return $productoInstanciado;
    }

    public static function obtenerUnProductoParaVender(string $codigo)
    {
        $productoEncontrado = null;
        $listaProductos = Producto::mostrarProductosJson();
        foreach ($listaProductos as $producto)
        {
            if ($producto["codigo"] == $codigo)
            { 
                $productoEncontrado= Producto::productoDesdeArray($producto);
            }
        }
        
        return $productoEncontrado;
    }

}