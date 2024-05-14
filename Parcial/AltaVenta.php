<?php 

include_once "HeladeriaAlta.php";
include_once "ArchivosJSON.php";
class Venta
{
    private array $catalogo;
    private string $saborConsultado;
    private string $tipoConsultado;
    private int $stockConsultado;
    private int $ID;
    private int $numeroPedido;
    private string $mail;
    private $fecha;

    public function __construct(string $sabor, string $tipo, int $stock, string $mail, $fecha = new DateTime, int $numeroPedido = 0)
    {
        $this->catalogo = ArchivosJSON::leerJSON("./", "heladeria.json");
        $this->saborConsultado = $sabor;
        $this->tipoConsultado = $tipo;
        $this->stockConsultado = $stock;
        $this->mail = $mail;
        if($numeroPedido == 0)
        {
            $this->numeroPedido = rand(1, 10000);
        }
        else 
        {
            $this->numeroPedido = $numeroPedido;
        }
        $this->ID = rand(1, 10000);
        if ($fecha instanceof DateTime) 
        {
            $this->_fecha = $fecha->format("d/m/Y");
        }
        else if (is_string($fecha)) 
        {
            $this->_fecha = $fecha;
        }
        else
        {
            $this->_fecha = (new DateTime)->format("d/m/Y");
        }
    }

    public function getData() : array
    {
        $data = array("sabor"=>$this->saborConsultado, "tipo"=>$this->tipoConsultado, 
        "stock"=>$this->stockConsultado, "mail"=>$this->mail, 
        "fecha"=>$this->fecha, "numero_pedido"=>$this->numeroPedido);
        
        return $data;
    }
    public function realizarVenta()
    {
        $hay = false;

        foreach($this->catalogo as $val => $p1)
        {
                        
            if($this->saborConsultado == $p1["sabor"] && $this->tipoConsultado == $p1["tipo"] && $p1["stock"] >= $this->stockConsultado)
            {
                $hay = true;
                $helado = new Heladeria($p1["sabor"], $p1["precio"], $p1["tipo"], $p1["vaso"]);
                break;
            }
        }

        if($hay)
        {
            $helado->setStock(-$this->stockConsultado);
            $helado->guardarHeladoJSON('./');
            $this->guardarVentaJSON();
        }
    }

    public function guardarVentaJSON()
    {
        $nombreArchivo = "ventas.json";
        $mensaje = "Uno o más de los campos está vacío o es incorrecto. Cambie la información necesaria e inténtelo nuevamente.";
        
        $listaVentas = Venta::mostrarVentasJson();
        
        try
        {
            $file = fopen("./" . $nombreArchivo, "w");
            if(count($listaVentas) == 0)
            {
                $data = array($this->getData());
                $listaVentas = $data;
            }
            else
            {
                $data = $this->getData();
                array_push($listaVentas, $data);
            }

            $json = json_encode($listaVentas);
            $chars = fwrite($file, $json);

            fclose($file);

            if($chars > 0)
            {
                $mensaje = "Venta AGREGADA.";
            }

        }
        catch(Exception $e)
        {
            echo "<br/>";
            echo "". $e->getMessage() ."";
            echo "<br/>";
        }   
            
    
        return $mensaje;
                
    }

    public static function mostrarVentasJson()
    {
        $resultado = array();

        if (file_exists("./ventas.json"))
        {
            try
            {
                $file = fopen("./ventas.json", "r");
    
                $contenido = fread($file, filesize("./ventas.json"));
                $arrayDeVentas = json_decode($contenido, true);
                
                               
                fclose($file);
                
                $resultado = $arrayDeVentas;

            }
            catch(Exception $e)
            {
                echo "<br/>";
                echo "". $e->getMessage() ."";
                echo "<br/>";
            }
        }
        
        return $resultado;
    }

    public function guardarImagen()
    {
        if(isset($_FILES['archivo']))
        {
            $carpetaImg = './ImagenesDeLaVenta/2024/';
            $fileType = $_FILES['archivo']['type'];
            $nombreUsuario = strstr($this->mail, '@', true);
            $fileName = $this->saborConsultado . $this->tipoConsultado . $this->stockConsultado . $nombreUsuario . $this->fecha;
            $fileSize = $_FILES['archivo']['size'];

            $route = $carpetaImg . $fileName;

            if (!((strpos($fileType, "png") || strpos($fileType, "jpeg")))) 
            {
                echo "Tipo de archivo no aceptado. Se permiten archivos .png o .jpg";
            }
            else if (($fileSize > 200000000))
            {
                echo "El archivo es demasiado pesado. Intente con uno menor a 2 Mb.";
            }
            else
            {
                if (move_uploaded_file($_FILES['archivo']['tmp_name'],  $route))
                {
                    echo "El archivo ha sido cargado correctamente.";
                }else
                {
                    echo "Error, el archivo no ha podido guardarse. Inténtelo nuevamente.";
                }
            }
        }
    }

}

$nuevaVenta = new Venta($_POST["sabor"], $_POST["tipo"], $_POST["stock"], $_POST["mail"]);
$nuevaVenta->realizarVenta();
$nuevaVenta->guardarImagen();

?>