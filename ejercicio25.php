<?php
    use Vtiful\Kernel\Format;
    //AUTOR: FERRARIS EZEQUIEL MANUEL

    /*
    Aplicación No 25 ( AltaProducto)
    Archivo: altaProducto.php
    método:POST
    Recibe los datos del producto(código de barra (6 cifras ),nombre ,tipo, stock, precio )por POST ,
    crea un ID autoincremental(emulado, puede ser un random de 1 a 10.000). crear un objeto y
    utilizar sus métodos para poder verificar si es un producto existente, si ya existe el producto se le
    suma el stock , de lo contrario se agrega al documento en un nuevo renglón
    Retorna un :
    “Ingresado” si es un producto nuevo
    “Actualizado” si ya existía y se actualiza el stock.
    “no se pudo hacer“si no se pudo hacer
    Hacer los métodos necesarios en la clase
    */

    include_once "./producto.php";
    date_default_timezone_set("America/Argentina/Buenos_Aires");

    $carpetaDestino = "./";

    $productoRecibido = new Producto($_POST["codigo"], $_POST["nombre"], $_POST["tipo"], $_POST["stock"], $_POST["precio"]);


    $resultado = $productoRecibido->guardarProductoJSON("./");

    echo "" . $resultado;