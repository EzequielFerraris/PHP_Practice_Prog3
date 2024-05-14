<?php
//AUTOR FERRARIS EZEQUIEL MANUEL LEG.114520 DNI 34270373
//include_once "";

include_once "guardarImagenes.php";

if(isset($_GET['pedido'])){
    switch($_SERVER['REQUEST_METHOD']){
        case 'GET':
            switch ($_GET['pedido']){
                default:
                    echo 'Pedido no permitido';
                break;
            }
            break;
        case 'POST':
            switch ($_POST['pedido']){
                case 'alta':
                    include_once "HeladeriaAlta.php";
                    $pedido = new Heladeria($_POST["sabor"], $_POST["precio"], $_POST["tipo"], $_POST["vaso"], $_POST["stock"]);
                    $pedido->guardarHeladoJSON("./");
                    $nombreArchivo = $pedido->getSabor() . $pedido->getTipo();
                    guardadorDeImagenes::guardarImagen($nombreArchivo, 20000000);
                break;
                case 'consulta':
                    include_once "HeladoConsultar.php";
                break;
                case 'venta':
                    include_once "AltaVenta.php";
                break;
                default:
                    echo 'Pedido no permitido';
                break;
            }
            break;

        default:
            echo 'Petición no permitida';
            break;
    }
} else {
    echo 'Parámetro "pedido" no enviado';
}


?>