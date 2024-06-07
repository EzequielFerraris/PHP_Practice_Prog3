<?php
use Vtiful\Kernel\Format;
//AUTOR: FERRARIS EZEQUIEL MANUEL

/*
Aplicación No 26 (RealizarVenta)
Archivo: RealizarVenta.php
método:POST
Recibe los datos del producto(código de barra), del usuario (el id )y la cantidad de ítems ,por
POST .
Verificar que el usuario y el producto exista y tenga stock.
crea un ID autoincremental(emulado, puede ser un random de 1 a 10.000). carga
los datos necesarios para guardar la venta en un nuevo renglón.
Retorna un :
“venta realizada”Se hizo una venta
“no se pudo hacer“si no se pudo hacer
Hacer los métodos necesaris en las clases
*/

include_once "./producto.php";
include_once "./usuario.php";

date_default_timezone_set("America/Argentina/Buenos_Aires");

$carpetaDestino = "./";

function realizarVenta(array $venta)
{
    $resultado = false;
        
    $nombreArchivo = "./ventas.json";
    
    try
    {
        $file = fopen($nombreArchivo, "a+");
        $json = json_encode($venta);
        $chars = fwrite($file, $json);
        
        fclose($file);  

        if($chars > 0)
        {
            $resultado = true;
        }
    }
    catch(Exception $e)
    {
        echo "<br/>";
        echo "". $e->getMessage() ."";
        echo "<br/>";
    }    

    return $resultado;
}


$usuarioComprador = $_POST["nombreUsuario"];
$productoSolicitado = $_POST["codigo"];
$items = $_POST["items"];
$IDCompra = rand(1, 10000);

$usuario = Usuario::obtenerUnUsuario($usuarioComprador);
$producto = Producto::obtenerUnProductoParaVender($productoSolicitado);

$venta = array();

if(!(is_null($usuario)) & !(is_null($producto)))
{
    if($producto->getStock() >= $items)
    {
        $nuevoStock = $producto->getStock() - $items;
        $producto->setStock($nuevoStock);
        $producto->guardarProductoJSON("./");
        $venta["usuario"] = $usuario->getUsuario();
        $venta["producto"] = $producto->getNombre();
        $venta["cantidad"] = $items;
        $venta["precio"] = $producto->getPrecio();
        $venta["monto"] = $producto->getPrecio() * $items;
    }
}

if(count($venta) > 0)
{
    $nuevaVenta = realizarVenta($venta);
    if($nuevaVenta)
    {
        echo "La venta se realizó satisfactoriamente";
    }
    else
    {
        echo "La venta NO pudo realizarse";
    }
}
else
{
    echo "La venta no pudo realizarse";
}




