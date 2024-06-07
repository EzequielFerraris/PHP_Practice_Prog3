<?php
include_once "histograma.php";
include_once "ArchivosJSON.php";
include_once "AltaVenta.php";
class ConsultarVentas
{

    public static function PrendasDia(string $fecha="") : array
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
    
    public static function VentasPorTipo(string $tipo)
    {
        $ventas = ArchivosJSON::leerJSON(Venta::$ruta, Venta::$nombreArchivo);
        $result = array();

        if(Validador::es_string($tipo))
        {
            foreach($ventas as $p)
            {
                if($p["nombre"] == $tipo)
                {
                    array_push($result, $p);
                }
            }
        }
        
        return $result;
    }

    public static function VentasEntrePrecios(float $f1, float $f2)
    {
        $prendas = ArchivosJSON::leerJSON(TiendaAlta::$ruta, TiendaAlta::$nombreArchivo);
        $result = array();

        foreach($prendas as $p)
        {
            if($p["precio"] >= $f1 && $p["precio"] <= $f2)
            {
                array_push($result, $p);
            }
        }

        return $result;
    }

    public static function IngresosPorDia(string $fecha="") : array
    {
        $result = array();
        $flag = true;
        if($fecha == "")
        {
            $ventas = ArchivosJSON::leerJSON(Venta::$ruta, Venta::$nombreArchivo);

            foreach($ventas as $p)
            {
                $ganancia = ($p["precio"] * $p["stock"]);
                $fechaVenta = $p["fecha"];
                if(!isset($result[$fechaVenta]))
                {
                    $result[$fechaVenta] = $ganancia;
                }
                else
                {
                    $result[$fechaVenta] += $ganancia;
                }
            }
            return $result;
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

            if($flag)
            {
                $ventas = ArchivosJSON::leerJSON(Venta::$ruta, Venta::$nombreArchivo);

                foreach($ventas as $p)
                {
                    if(Histograma::chequearFecha($p, $fecha))
                    {
                        $ganancia = ($p["precio"] * $p["stock"]);
                        $fechaVenta = $p["fecha"];
                        if(!isset($result[$fechaVenta]))
                        {
                            $result[$fechaVenta] = $ganancia;
                        }
                        else
                        {
                            $result[$fechaVenta] += $ganancia;
                        }
                    }
                }
            }
        }

        
        return $result;
    }

    public static function ProductoMasVendido() : array
    {
        $result = array();
        $ventas = ArchivosJSON::leerJSON(Venta::$ruta, Venta::$nombreArchivo);
        $categorias = array();

        foreach($ventas as $item)
        {
            if(!in_array($item["nombre"], $categorias))
            {
                array_push($categorias, $item["nombre"]);
            }
        }

        foreach($categorias as $valor)
        {
            $ventasCategoria = 0;
            foreach($ventas as $venta)
            {
                if($valor == $venta["nombre"])
                {
                    $ventasCategoria += $venta["stock"];
                }
                
            }
            $result[$valor] = $ventasCategoria;

        }

        $key = "";
        $valor = Null;

        foreach($result as $x=>$y)
        {
            if(is_null($valor) || $valor < $y)
            {
                $key = $x;
                $valor = $y;
            }
        }

        $resultadoFinal = array($key=>$valor);
        return $resultadoFinal;
    }
        
}

?>