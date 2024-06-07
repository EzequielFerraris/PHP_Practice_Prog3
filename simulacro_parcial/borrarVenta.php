<?php
include_once "ModificarVenta.php";
include_once "AltaVenta.php";
class BorrarVenta
{

    public static string $carpetaBack = "./ImagenesBackupVentas/2024/"; 
    public static function BorrarVenta(int $codigoVenta) : bool
    {
        $resultado = false;

        $listaVentas = ArchivosJSON::leerJSON(Venta::$ruta, Venta::$nombreArchivo);

        $indice = ModificarVenta::EncontrarVenta($codigoVenta);

        if($indice >= 0)
        {
            $ventaOriginal = $listaVentas[$indice];

            $img = self::moverImagenABackUp($ventaOriginal);
            if($img)
            {
                echo "Se ha movido la imagen al back up correspondiente";
            }

            $helado = new Heladeria($ventaOriginal["sabor"], $ventaOriginal["precio"], 
                                    $ventaOriginal["tipo"], $ventaOriginal["vaso"], $ventaOriginal["stock"]);

            $helado->guardarHeladoJSON();
            array_splice($listaVentas, $indice, 1);
            ArchivosJSON::escribirJSON(Venta::$ruta, Venta::$nombreArchivo, $listaVentas);
            $resultado = true;
        }
        return $resultado;
    }

    public static function moverImagenABackUp(array $venta) : bool
    {
        $resultado = false;

        $fileName = $venta["sabor"] . $venta["tipo"] . 
                    $venta["stock"] . strstr($venta["mail"], '@', true) . $venta["fecha"];

        $ruta = Venta::$rutaImagenes;

        $pahtCompleto = $ruta . $fileName;


        if(file_exists($pahtCompleto . ".jpg"))
        {
            rename($pahtCompleto . ".jpg", self::$carpetaBack . $fileName . ".jpg");
            $resultado = true;
        }
        else if (file_exists($pahtCompleto . ".png"))
        {
            rename($pahtCompleto . ".png", self::$carpetaBack . $fileName . ".png");
            $resultado = true;
        }
        
        return $resultado;

    }

}

?>