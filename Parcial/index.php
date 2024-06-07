<?php
//AUTOR FERRARIS EZEQUIEL MANUEL LEG.114520 DNI 34270373
//include_once "";

include_once "validador.php";
include_once "guardarImagenes.php";
include_once "impresor.php";

date_default_timezone_set("America/Argentina/Buenos_Aires");

if(isset($_GET['pedido']))
{
    switch($_SERVER['REQUEST_METHOD'])
    {
        case 'GET':
            switch ($_GET['pedido']){
                case 'consultar':
                    include_once "ConsultarVentas.php";
                    switch ($_GET['item'])
                    {
                        case 'prendas_dia':
                            
                            if(isset($_GET['fecha']))
                            {
                                $date = $_GET['fecha'];
                            }
                            else
                            {
                                $date = "";
                            }
                            $ventasDelDia = ConsultarVentas::PrendasDia($date);
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
                            $ventasPrenda = array();
                            if(isset($_GET['nombre']))
                            {
                                $ventasPrenda = ConsultarVentas::VentasPorTipo($_GET['nombre']);
                            }
                            if(count($ventasPrenda) > 0)
                            {
                                Impresor::imprimirArrayDeArrays($ventasPrenda);
                            }
                            else
                            {
                                echo "No se registran ventas de esa prenda";
                            }
                        break;
                        case 'ventas_entre_precios':
                            $ventasUsuario = array();
                            if(isset($_GET['precio1']) && isset($_GET['precio2']))
                            {
                                $ventasEntreValores = ConsultarVentas::VentasEntrePrecios($_GET['precio1'], $_GET['precio2']);
                            }
                            if(count($ventasEntreValores ) > 0)
                            {
                                Impresor::imprimirArrayDeArrays($ventasEntreValores );
                            }
                            else
                            {
                                echo "No se registran prendas entre esos valores";
                            }
                        break;
                        case 'ingresosPorDia':
                            if(isset($_GET['fecha']))
                            {
                                $ventasPorDia = ConsultarVentas::IngresosPorDia($_GET['fecha']);
                            }
                            else
                            {
                                $ventasPorDia = ConsultarVentas::IngresosPorDia();
                            }
                            if(count($ventasPorDia) > 0)
                            {
                                echo "Ventas por día:</br>";
                                Impresor::imprimirArray($ventasPorDia);
                            }
                            else
                            {
                                echo "No se registran prendas entre esos valores";
                            }
                        break;
                        case 'productoMasVendido':
                            $productoMasVendido = ConsultarVentas::ProductoMasVendido();
                            if(count($productoMasVendido) > 0)
                            {
                                echo "Producto más vendido:</br>";
                                Impresor::imprimirArray($productoMasVendido);
                            }
                            else
                            {
                                echo "No se registran prendas vendidas.";
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
            switch ($_POST['pedido'])
            {
                case 'alta':
                    include_once "TiendaAlta.php";
                    $pedido = new TiendaAlta($_POST["nombre"], $_POST["precio"], $_POST["tipo"], $_POST["talla"], $_POST["color"], $_POST["stock"]);
                    $resultadoPedido = $pedido->guardarPrendaJSON();
                    $nombreArchivo = $pedido->getNombre() . $pedido->getTipo();
                    if(isset($_FILES["archivo"]) && $resultadoPedido)
                    {
                        guardadorDeImagenes::guardarImagen(TiendaAlta::$rutaImagenes, $nombreArchivo, 20000000);
                    }
                break;
                case 'consulta':
                    include_once "PrendaConsultar.php";
                    $nuevaConsulta = new ConsultasPrenda($_POST["nombre"], $_POST["tipo"], $_POST["color"]);
                    $nuevaConsulta->consulta();
                break;
                case 'venta':
                    include_once "AltaVenta.php";
                    if(isset($_POST["fecha"]))
                    {
                        $nuevaVenta = new Venta($_POST["nombre"], $_POST["tipo"], $_POST["talla"], $_POST["stock"], $_POST["precio"], $_POST["mail"], $_POST["fecha"]);
                    }
                    else
                    {
                        $nuevaVenta = new Venta($_POST["nombre"], $_POST["tipo"], $_POST["talla"], $_POST["stock"], $_POST["precio"], $_POST["mail"]);
                    }

                    $resultadoVenta = $nuevaVenta->realizarVenta();
                    
                    if(isset($_FILES["archivo"]) && $resultadoVenta)
                    {
                        $nombreArchivo = $nuevaVenta->getNombreImagenVenta();
                        guardadorDeImagenes::guardarImagen(Venta::$rutaImagenes, $nombreArchivo, 20000000);
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
    
                $modificarVenta = new Venta($put_vars["nombre"], $put_vars["tipo"], $put_vars["talla"],$put_vars["stock"], 
                                            $put_vars["precio"], $put_vars["mail"]);
    
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
        default:
        echo 'Petición no permitida';
        break;
    }
} 
else 
{
    echo 'Parámetro "pedido" no enviado';
}

?>