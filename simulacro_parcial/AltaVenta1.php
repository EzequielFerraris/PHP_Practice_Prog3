<?php 

include_once "HeladeriaAlta.php";
include_once "ArchivosJSON.php";
include_once "validador.php";

date_default_timezone_set("America/Argentina/Buenos_Aires");
class Venta
{
    public static string $ruta = "./";
    public static string $nombreArchivo = "ventas.json";
    public static string $rutaImagenes = './ImagenesDeLaVenta/2024/';
    private array $_catalogo;
    private array $_ventas;
    private string $_saborConsultado;
    private string $_tipoConsultado;
    private int $_stockConsultado;
    private float $_precio;
    private string $_vasoConsultado;
    private string $_mail;
    private int $_ID;
    private int $_numeroPedido;
    private string $_fecha;
    private bool $_valid = true;

    public function __construct(string $sabor, string $tipo, int $stock, string $vaso, string $mail, float $precio, string $date="")
    {
        $this->_catalogo = ArchivosJSON::leerJSON(Heladeria::$ruta, Heladeria::$nombreArchivo);
        $this->_ventas = ArchivosJSON::leerJSON(Venta::$ruta, Venta::$nombreArchivo);

        if($date == "")
        {
            $this->_fecha = (new DateTime)->format("d-m-Y");
        }
        else 
        {
            try
            {
                $this->_fecha = date('d-m-Y', strtotime($date));
            }
            catch(Exception $e)
            {
                $this->_valid = false;
                $this->_fecha = "";
            }       
            
        }

        if(!Validador::es_string($sabor) || !Validador::es_string($tipo) || 
        !Validador::es_string($vaso) || !Validador::es_mail_valido($mail) || 
        !Validador::es_entero($stock) || !Validador::es_float($precio))
        {
            $this->_valid = false;
        }

        $this->_saborConsultado = $sabor;
        $this->_tipoConsultado = $tipo;
        $this->_stockConsultado = $stock;
        $this->_vasoConsultado = $vaso;
        $this->_mail = $mail;
        $this->_precio = $precio;
        
        
        if(count($this->_ventas) > 0)
        {
            $this->_ID = end($this->_ventas)["ID"] + 1;
            $this->_numeroPedido = end($this->_ventas)["numero_pedido"] + 1;
        }
        else
        {
            $this->_ID = 1;
            $this->_numeroPedido = 1;
        }

    }

    public function getSabor() : string
    {
        return $this->_saborConsultado;
    }

    public function getTipo() : string
    {
        return $this->_tipoConsultado;
    }

    public function getVaso() : string
    {
        return $this->_vasoConsultado;
    }

    public function getStock() : int
    {
        return $this->_stockConsultado;
    }

    public function getID() : int
    {
        return $this->_ID;
    }

    public function getNumeroPedido() : int
    {
        return $this->_numeroPedido;
    }

    public function setNumeroPedido(int $pedido) : bool
    {
        $resultado = false;
        if(Validador::es_entero($pedido))
        {
            $this->_numeroPedido = $pedido;
            $resultado = true;
        }
        return $resultado;
    }

    public function getMail() : string
    {
        return $this->_mail;
    }

    public function getPrecio() : float
    {
        return $this->_precio;
    }
    public function getValid() : bool
    {
        return $this->_valid;
    }

    public function getNombreUsuario() : string
    {
        $nombreUsuario = strstr($this->_mail, '@', true);
        return $nombreUsuario;
    }

    public function getNombreImagenVenta() : string
    {
        $fileName = $this->_saborConsultado . $this->_tipoConsultado . 
                    $this->_stockConsultado . $this->getNombreUsuario() . $this->_fecha;

        return $fileName;
    }

    public function calcularImporteTotal()
    {
        return $this->getStock() * $this->getPrecio();
    }
    
    public function getData() : array
    {
        $data = array("sabor"=>$this->_saborConsultado, "tipo"=>$this->_tipoConsultado, 
        "stock"=>$this->_stockConsultado, "vaso"=>$this->_vasoConsultado, "mail"=>$this->_mail, 
        "precio"=>$this->_precio, "fecha"=>$this->_fecha, "numero_pedido"=>$this->_numeroPedido, 
        "ID"=>$this->_ID);
        
        return $data;
    }

    public function realizarVenta()
    {
        $this->_catalogo = ArchivosJSON::leerJSON(Heladeria::$ruta, Heladeria::$nombreArchivo);
        $this->_ventas = ArchivosJSON::leerJSON(Venta::$ruta, Venta::$nombreArchivo);
        
        $resutado = false;
        $hay = false;

        foreach($this->_catalogo as $val => $p1)
        {
                        
            if($this->_saborConsultado == $p1["sabor"] && $this->_tipoConsultado == $p1["tipo"] 
                && $p1["stock"] >= $this->_stockConsultado && $this->_vasoConsultado == $p1["vaso"]
                && $this->_precio == $p1["precio"])
            {
                $hay = true;
                $helado = new Heladeria($p1["sabor"], $p1["precio"], $p1["tipo"], $p1["vaso"]);
                break;
            }
        }

        if($hay)
        {
            $helado->setStock(-($this->_stockConsultado));
            $t = $helado->guardarHeladoJSON();
            if($t)
            {
                echo "Vendiendo...";
                $this->guardarVentaJSON();
                $resutado = true;
            }
            
        }
        else
        {
            echo "En este momento no contamos con el producto solicitado. Inténtelo más adelante.";
        }

        return $resutado;
    }

    public function guardarVentaJSON() : bool
    {
        $resultado = false;
             
        $data = $this->getData();

        array_push($this->_ventas, $data);
        
        $t =ArchivosJSON::escribirJSON(self::$ruta, self::$nombreArchivo, $this->_ventas);

        if($t)
        {
            $resultado = true;
        }

        return $resultado;

    } 

    
}

?>