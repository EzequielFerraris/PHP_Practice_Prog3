<?php 

include_once "TiendaAlta.php";
include_once "ArchivosJSON.php";
include_once "validador.php";


date_default_timezone_set("America/Argentina/Buenos_Aires");
class Venta
{
    public static string $ruta = "./";
    public static string $nombreArchivo = "ventas.json";
    public static string $rutaImagenes = './ImagenesDeVenta/2024/';
    private array $_catalogo;
    private array $_ventas;
    private string $_mail;
    private string $_nombreConsultado;
    private string $_tipoConsultado;
    private string $_tallaConsultada;
    private int $_stockConsultado;
    private int $_ID;
    private int $_numeroPedido;
    private float $_precio;
    private string $_fecha;
    private bool $_valid = true;

    public function __construct(string $nombre, string $tipo, string $talla, int $stock, float $precio, string $mail, string $date="")
    {
        $this->_catalogo = ArchivosJSON::leerJSON(TiendaAlta::$ruta, TiendaAlta::$nombreArchivo);
        $this->_ventas = ArchivosJSON::leerJSON(self::$ruta, self::$nombreArchivo);

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

        if(!Validador::es_string($nombre) || !Validador::es_string($tipo) || 
        !Validador::es_string($talla) || !Validador::es_mail_valido($mail) || 
        !Validador::es_entero($stock))
        {
            $this->_valid = false;
        }

        $this->_nombreConsultado = $nombre;
        $this->_tipoConsultado = $tipo;
        $this->_stockConsultado = $stock;
        $this->_tallaConsultada = $talla;
        $this->_precio = $precio;
        $this->_mail = $mail;    
        
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

    public function getNombre() : string
    {
        return $this->_nombreConsultado;
    }

    public function getTipo() : string
    {
        return $this->_tipoConsultado;
    }

    public function getTalla() : string
    {
        return $this->_tallaConsultada;
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

    
    public function getData() : array
    {
        $data = array("nombre"=>$this->_nombreConsultado, "tipo"=>$this->_tipoConsultado, 
        "stock"=>$this->_stockConsultado, "talla"=>$this->_tallaConsultada, "precio"=>$this->_precio,
        "mail"=>$this->_mail, "fecha"=>$this->_fecha, "numero_pedido"=>$this->_numeroPedido, "ID"=>$this->_ID);
        
        return $data;
    }

    public function getNombreUsuario() : string
    {
        $nombreUsuario = strstr($this->_mail, '@', true);
        return $nombreUsuario;
    }

    public function getNombreImagenVenta() : string
    {
        $fileName = $this->_nombreConsultado . $this->_tipoConsultado . 
                    $this->_stockConsultado . $this->getNombreUsuario() . $this->_fecha;

        return $fileName;
    }
    public function realizarVenta()
    {
        $this->_catalogo = ArchivosJSON::leerJSON(TiendaAlta::$ruta, TiendaAlta::$nombreArchivo);
        $this->_ventas = ArchivosJSON::leerJSON(self::$ruta, self::$nombreArchivo);
        
        $resutado = false;
        $hay = false;

        foreach($this->_catalogo as $val => $p1)
        {
                        
            if($this->_nombreConsultado == $p1["nombre"] && $this->_tipoConsultado == $p1["tipo"] 
                && $p1["stock"] >= $this->_stockConsultado && $this->_tallaConsultada == $p1["talla"])
            {
                $hay = true;
                $prenda = new TiendaAlta($p1["nombre"], $p1["precio"], $p1["tipo"], $p1["talla"], $p1["color"]);
                break;
            }
        }

        if($hay)
        {
            $prenda->setStock(-($this->_stockConsultado));
            $t = $prenda->guardarPrendaJSON();
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