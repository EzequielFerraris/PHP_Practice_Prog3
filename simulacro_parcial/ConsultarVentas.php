<?php
include_once "histograma.php";
include_once "ArchivosJSON.php";
include_once "AltaVenta.php";
class ConsultarVentas
{

    public static function HeladosDia(string $fecha="") : array
    {
        $result = array();
        $flag = true;
        if($fecha == "")
        {
            $fecha = date('d-m-Y', strtotime("yesterday"));
        }
        else
        {
            try
            {
                $fecha = strtotime($fecha);
                $fecha = date('d-m-Y', $fecha);
            }
            catch(Exception $e)
            {
                echo "No se ingresó una fecha válida";
                $flag = false;
            }       
        }

        if($flag)
        {
            $ventas = ArchivosJSON::leerJSON(Venta::$ruta, Venta::$nombreArchivo);

            foreach($ventas as $p)
            {
                if(Histograma::chequearFecha($p, $fecha))
                {
                    array_push($result, $p);
                }
            }
        }
        return $result;
    }

    public static function VentasUsuario(string $usuario) : array
    {
        $ventas = ArchivosJSON::leerJSON(Venta::$ruta, Venta::$nombreArchivo);
        $result = array();

        foreach($ventas as $p)
        {
            $nombreUsuario = strstr($p["mail"], '@', true);
            if($nombreUsuario == $usuario)
            {
                array_push($result, $p);
            }
        }
        return $result;
    }
    
    public static function VentasPorNombre(string $f1, string $f2)
    {
        $ventas = ArchivosJSON::leerJSON(Venta::$ruta, Venta::$nombreArchivo);
        $result = array();

        foreach($ventas as $p)
        {
            if(Histograma::filtrarEntreFechas($p, $f1, $f2))
            {
                array_push($result, $p);
            }
        }
        usort($result, "Histograma::compararNombres");

        return $result;
    }

    
    public static function VentasPorSabor(string $sabor)
    {
        $ventas = ArchivosJSON::leerJSON(Venta::$ruta, Venta::$nombreArchivo);
        $result = array();

        if(Validador::es_string($sabor))
        {
            foreach($ventas as $p)
            {
                if($p["sabor"] == $sabor)
                {
                    array_push($result, $p);
                }
            }
        }
        
        return $result;
    }
    
    public static function VentasPorVaso(string $vaso)
    {
        $ventas = ArchivosJSON::leerJSON(Venta::$ruta, Venta::$nombreArchivo);
        $result = array();

        if(Validador::es_string($vaso))
        {
            foreach($ventas as $p)
            {
                if($p["vaso"] == $vaso)
                {
                    array_push($result, $p);
                }
            }
        }
        
        return $result;
    }
}

?>