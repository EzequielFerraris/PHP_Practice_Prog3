<?php

include_once "ArchivosJSON.php";
include_once "AltaVenta.php";
include_once "TiendaAlta.php";
include_once "PrendaConsultar.php";
class ModificarVenta
{

    public static function EncontrarVenta(int $codigoVenta) : int
    {
        $ventas = ArchivosJSON::leerJSON(Venta::$ruta, Venta::$nombreArchivo);
        $indice = -1;

        if(Validador::es_entero($codigoVenta))
        {
            foreach($ventas as $val => $p1)
            { 
                if($codigoVenta == $p1["numero_pedido"])
                {
                    $indice = $val;
                    break;
                }
            }   
        } 
        return $indice;
    }

    public static function ModificarVenta(Venta $modificada) : bool
    {
        $resultado = false;
        $ventas = ArchivosJSON::leerJSON(Venta::$ruta, Venta::$nombreArchivo);
        $indice = self::EncontrarVenta($modificada->getNumeroPedido());

        if($indice >= 0)
        {
            $ventaOriginal = $ventas[$indice];
            
            $prenda = new TiendaAlta($ventaOriginal["nombre"], $ventaOriginal["precio"], 
                                    $ventaOriginal["tipo"], $ventaOriginal["talla"], 
                                    $ventaOriginal["stock"]);

            
            if($ventaOriginal["nombre"] == $modificada->getNombre())
            {
                $prendasActuales = ConsultasPrenda::consultaCantidad($modificada->getSabor(), $modificada->getTipo());
                $prendasPotenciales = $prendasActuales + $prenda->getStock();

                if($modificada->getStock() >  $prendasPotenciales)
                {
                    echo "No es posible realizar la modificación. No hay suficiente stock.</br>"; 
                }
                else
                {
                    $prenda->guardarPrendaJSON();
                    $modificada->realizarVenta();
                    $ventas = ArchivosJSON::leerJSON(Venta::$ruta, Venta::$nombreArchivo);
                    array_splice($ventas, $indice, 1);
                    ArchivosJSON::escribirJSON(Venta::$ruta, Venta::$nombreArchivo, $ventas);
                    $resultado = true;
                }
                
            }
            //resuelto
            else //Son distintos  
            {
                $r = $modificada->realizarVenta();
                if($r)
                {
                    $prenda->guardarPrendaJSON();
                    $ventas = ArchivosJSON::leerJSON(Venta::$ruta, Venta::$nombreArchivo);
                    array_splice($ventas, $indice, 1);
                    ArchivosJSON::escribirJSON(Venta::$ruta, Venta::$nombreArchivo, $ventas);
                    $resultado = true; 
                }
            }
        } 
        else
        {
            echo "No se encuentra el registro</br>";
        }

        return $resultado;
    }
}

?>