<?php
//AUTOR FERRARIS EZEQUIEL MANUEL LEG.114520 DNI 34270373
//include_once "";

include_once "guardarImagenes.php";
include_once "impresor.php";

date_default_timezone_set("America/Argentina/Buenos_Aires");

if(isset($_GET['pedido'])){
    switch($_SERVER['REQUEST_METHOD']){
        case 'GET':
            switch ($_GET['pedido']){
                case 'consultar':
                    include_once "ConsultarVentas.php";
                    switch ($_GET['item'])
                    {
                        case 'helados_dia':
                            
                            if(isset($_GET['fecha']))
                            {
                                $date = $_GET['fecha'];
                            }
                            else
                            {
                                $date = "";
                            }
                            $ventasDelDia = ConsultarVentas::HeladosDia($date);
                            if(count($ventasDelDia) > 0)
                            {
                                Impresor::imprimirArrayDeArrays($ventasDelDia);
                            }
                            else
                            {
                                echo "No se registran ventas para ese día";
                            }
                        break;
                        case 'ventas_usuario':
                            $ventasUsuario = array();
                            if(isset($_GET['usuario']) && Validador::es_string($_GET['usuario']))
                            {
                                $ventasUsuario = ConsultarVentas::VentasUsuario($_GET['usuario']);
                            }
                            if(count($ventasUsuario) > 0)
                            {
                                Impresor::imprimirArrayDeArrays($ventasUsuario);
                            }
                            else
                            {
                                echo "No se registran ventas para ese usuario";
                            }
                        break;
                        case 'ventas_nombre':
                            $ventasPeriodo = array();
                            if(isset($_GET['fecha_inicio']) && isset($_GET['fecha_final']))
                            {
                                $ventasPeriodo = ConsultarVentas::VentasPorNombre($_GET['fecha_inicio'], $_GET['fecha_final']);
                            }
                            if(count($ventasPeriodo) > 0)
                            {
                                Impresor::imprimirArrayDeArrays($ventasPeriodo);
                            }
                            else
                            {
                                echo "No se registran ventas para ese período";
                            }
                        break;
                        case 'ventas_sabor':
                            $ventasSabor = array();
                            if(isset($_GET['sabor']))
                            {
                                $ventasSabor = ConsultarVentas::VentasPorSabor($_GET['sabor']);
                            }
                            if(count($ventasSabor) > 0)
                            {
                                Impresor::imprimirArrayDeArrays($ventasSabor);
                            }
                            else
                            {
                                echo "No se registran ventas para ese sabor";
                            }
                        break;
                        case 'ventas_cucurucho':
                            $ventasCucu = array();
                            $ventasCucu = ConsultarVentas::VentasPorVaso("Cucurucho");
                            if(count($ventasCucu) > 0)
                            {
                                Impresor::imprimirArrayDeArrays($ventasCucu);
                            }
                            else
                            {
                                echo "No se registran ventas para ese vaso.";
                            }
                        break;
                    }
                break;
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
                    $resultadoPedido = $pedido->guardarHeladoJSON();
                    $nombreArchivo = $pedido->getSabor() . $pedido->getTipo();
                    if(isset($_FILES["archivo"]) && $resultadoPedido)
                    {
                        guardadorDeImagenes::guardarImagen(Heladeria::$rutaImagenes, $nombreArchivo, 20000000);
                    }
                break;
                case 'consulta':
                    include_once "HeladoConsultar.php";
                    $nuevaConsulta = new ConsultaHelado($_POST["sabor"], $_POST["tipo"]);
                    $nuevaConsulta->consulta();
                break;
                case 'venta':
                    include_once "AltaVenta.php";
                    if(isset($_POST["fecha"]))
                    {
                        $nuevaVenta = new Venta($_POST["sabor"], $_POST["tipo"], $_POST["stock"], $_POST["vaso"], $_POST["mail"], $_POST["precio"], $_POST["fecha"]);
                    }
                    else
                    {
                        $nuevaVenta = new Venta($_POST["sabor"], $_POST["tipo"], $_POST["stock"], $_POST["vaso"], $_POST["mail"], $_POST["precio"]);
                    }
                    $resultadoVenta = $nuevaVenta->realizarVenta();
                    if(isset($_FILES["archivo"]) && $resultadoVenta)
                    {
                        $nombreArchivo = $nuevaVenta->getNombreImagenVenta();
                        guardadorDeImagenes::guardarImagen(Venta::$rutaImagenes, $nombreArchivo, 20000000);
                    }
                break;
                case 'devolucion':
                    include_once "DevolverHelado.php";
                    if(isset($_POST["numeroPedido"]) && isset($_POST["causa"]))
                    {
                        $devolucion = new DevolverHelado($_POST["numeroPedido"], $_POST["causa"]);
                        $r = $devolucion->guardarDevolucion();
                        if($r)
                        {
                            if(isset($_FILES["archivo"]))
                            {
                                $cupon = new CuponDescuento($devolucion->getNumeroPedido(), $devolucion->getUsuario(),
                                                            "no usado", 10); 
                                $r2 = $cupon->guardarCupon();
                                if($r2 )
                                {
                                    echo 'Devolución realizada satisfactoriamente.';
                                }
                                else
                                {
                                    echo "Algo falló";
                                }
                            }
                            else
                            {
                                echo 'Devolución realizada satisfactoriamente.';
                            }
                        }
                        else
                        {
                            echo 'Alguno de los datos es incorrecto. Inténtelo nuevamente.';
                        }
                    }
                    else
                    {
                        echo 'No se incluyeron los parámetros necesarios.';
                    }
                break;
                default:
                    echo 'Pedido no permitido';
                break;
            }
            break;
        case 'PUT':
            include_once "AltaVenta.php";
            include_once "ModificarVenta.php";

            parse_str(file_get_contents("php://input"),$put_vars);

            $modificarVenta = new Venta($put_vars["sabor"], $put_vars["tipo"], $put_vars["stock"], 
                                        $put_vars["vaso"], $put_vars["mail"], $put_vars["precio"]);

            $modificarVenta->setNumeroPedido($put_vars["numero_pedido"]);

            if($modificarVenta->getValid())
            {
                $r = ModificarVenta::ModificarVenta($modificarVenta);

                if($r)
                {
                    echo "Registro modificado.";
                }
                else
                {
                    echo "Error.";
                }
            }
        break;
        case "DELETE":
            include_once "borrarVenta.php";
            parse_str(file_get_contents("php://input"),$delete_vars);
            $r3 = BorrarVenta::BorrarVenta($delete_vars["numeroVenta"]);
            if($r3)
            {
                echo "Venta borrada con éxito";
            }
            else
            {
                echo "El número de venta es erróneo.";
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