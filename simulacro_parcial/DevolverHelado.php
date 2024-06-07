<?php 
include_once "validador.php";
include_once "cupones.php";
include_once "ModificarVenta.php";
include_once "borrarVenta.php";
class DevolverHelado
{

    public static string $rutaDevoluciones = "./";
    public static string $nombreArchivo = "devoluciones.json";
    
    private int $_numeroPedido;
    private string $_causaDevolucion;
    private string $_usuario;
    private CuponDescuento $cuponDescuento;

    private bool $_valid = true;

    public function __construct(int $numeroPedido, string $causaDevolucion)
    {

        if(!Validador::es_entero($numeroPedido) || !Validador::es_string($causaDevolucion))
        {
            $this->_valid = false;
        }
        if($this->_valid)
        {
            $chequearVenta = ModificarVenta::EncontrarVenta($numeroPedido);
            if($chequearVenta >= 0)
            {
                $listaVentas = ArchivosJSON::leerJSON(Venta::$ruta, Venta::$nombreArchivo);
                $mail = $listaVentas[$chequearVenta]["mail"];
                $this->_usuario = strstr($mail, '@', true);
                $this->_numeroPedido = $numeroPedido;
                $this->_causaDevolucion = $causaDevolucion;
            } 
        }
    }

    public function getNumeroPedido() : int
    {
        return $this->_numeroPedido;
    }

    public function getUsuario() : string
    {
        return $this->_usuario;
    }
   
    public function getData() : array
    {
        $data = array("numeroPedido"=>$this->_numeroPedido, "causaDevolucion"=>$this->_causaDevolucion);
        
        return $data;
    }

    
    public function guardarDevolucion() : bool
    {
        $listaDevoluciones = ArchivosJSON::leerJSON(DevolverHelado::$rutaDevoluciones, DevolverHelado::$nombreArchivo);
        $resultado = false;

        $chequearVenta = ModificarVenta::EncontrarVenta($this->_numeroPedido);
        
        if($chequearVenta >= 0)
        {
            $dataNueva = $this->getData();

            array_push($listaDevoluciones, $dataNueva);

            $t = ArchivosJSON::escribirJSON(self::$rutaDevoluciones, self::$nombreArchivo, $listaDevoluciones);

            if($t)
            {
                //BorrarVenta::BorrarVenta($this->getNumeroPedido());
                $resultado = true;
            }
        }
        

        return $resultado;
    }
}



?>