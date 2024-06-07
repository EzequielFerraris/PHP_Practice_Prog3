<?php
include_once "cupones.php";
class ConsultaDevoluciones
{
    public static function devolucionesConCupones()
    {
        $listaCupones = ArchivosJSON::leerJSON(CuponDescuento::$rutaCupones, CuponDescuento::$nombreArchivo);
    }

    public static function cuponesEstado()
    {
        
    }

    public static function devolucionesConCuponesEstado()
    {
        
    }
}



?>