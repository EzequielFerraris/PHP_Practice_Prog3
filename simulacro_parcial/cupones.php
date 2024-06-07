<?php
include_once "validador.php";
class CuponDescuento
{
    public static string $rutaCupones = "./";
    public static string $nombreArchivo = "cupones.json";
    private int $_porcentajeDescuento;
    private int $_devolucion_id;
    private string $_usuario;
    private string $_estado;
    private int $_ID;
    private bool $_valid = true;

    public function __construct(int $devolucion_id, string $usuario, string $estado, int $descuento)
    {
        if(!Validador::es_entero($descuento) || !Validador::es_string($estado) 
            || !Validador::es_entero($devolucion_id) || !Validador::es_string($usuario))
        {
            $this->_valid = false; 
        }

        $listaCupones = ArchivosJSON::leerJSON(self::$rutaCupones, self::$nombreArchivo);

        if(count($listaCupones) > 0)
        {
            $this->_ID = end($listaCupones)["ID"] + 1;
        }
        else
        {
            $this->_ID = 1;
        }

        $this->_usuario = $usuario;
        $this->_porcentajeDescuento = $descuento;
        $this->_devolucion_id = $devolucion_id;
        $this->_estado = $estado;      
    }

    public function getPorcentajeDescuento()
    {
        return $this->_porcentajeDescuento;
    }

    public function getID()
    {
        return $this->_ID;
    }

    public function getEstado()
    {
        return $this->_estado;
    }

    public function getUsuario()
    {
        return $this->_usuario;
    }

    public function getDevolucionId()
    {
        return $this->_devolucion_id;
    }
    public function getData() : array
    {
        $data = array("ID"=>$this->_ID, "devolucion_id"=>$this->_devolucion_id,
                        "porcentajeDescuento"=>$this->_porcentajeDescuento, "estado"=>$this->_estado);
        
        return $data;
    }
    public function guardarCupon()
    {
        $listaCupones = ArchivosJSON::leerJSON(self::$rutaCupones, self::$nombreArchivo);
        $resultado = false;

        $dataNueva = $this->getData();

        array_push($listaCupones, $dataNueva);

        $t =ArchivosJSON::escribirJSON(self::$rutaCupones, self::$nombreArchivo, $listaCupones);

        if($t)
        {
            $resultado = true;
        }

        return $resultado;
    }

}
?>