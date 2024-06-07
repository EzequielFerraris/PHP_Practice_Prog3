<?php
//AUTOR: FERRARIS EZEQUIEL MANUEL
/*
Aplicación No 21 ( Listado CSV y array de usuarios)
Archivo: listado.php
método:GET
Recibe qué listado va a retornar(ej:usuarios,productos,vehículos,...etc),por ahora solo tenemos
usuarios).
En el caso de usuarios carga los datos del archivo usuarios.csv.
se deben cargar los datos en un array de usuarios.
Retorna los datos que contiene ese array en una lista

<ul>
<li>Coffee</li>
<li>Tea</li>
<li>Milk</li>
</ul>
Hacer los métodos necesarios en la clase usuario
*/

class Usuario 
{
    private $_usuario = "";
    private $_password = "";
    private $_mail = "";
    private $_valid = false;

    public function __construct(string $usuario, string $password, string $mail)
    {

        $parameters = array($usuario, $password, $mail);

        for ($i = 0; $i < count($parameters); $i++)
        {
            if (is_string($parameters[$i]) && strlen($parameters[$i]) > 0) 
            {
                switch ($i)
                {
                    case "0":
                        $this->_usuario = $parameters[$i];
                        break;
                    case "1":
                        $this->_password = $parameters[$i];
                        break;
                    case "2":
                        $this->_mail = $parameters[$i];
                        $this->_valid = true;
                        break;
                }
            }
            else
            {
                echo "<br/>";
                echo "Uno o más de los campos está vacío. Cambie la información necesaria e inténtelo nuevamente.";
                echo "<br/>";
                break;
            }
        }
        
    }

    public function getUsuario(): string
    {
        return $this->_usuario;
    }

    public function getMail(): string
    {
        return $this->_mail;
    }

    public function validarPassword(string $input): bool
    {
        $result = false;
        if ($this->_password === $input & $this->_password !== "") 
        { 
            $result = true;
        }
        return $result;   
    }

    public function guardarUsuario()
    {
        $mensaje = "Uno o más de los campos está vacío. Cambie la información necesaria e inténtelo nuevamente.";
        if($this->_valid)
        {
            try
            {
                $file = fopen("./usuarios.csv", "a+");

                $string = $this->getUsuario() . "," . $this->_password . "," . $this->getMail() . "\n";

                $chars = fwrite($file, $string);
        
                if($chars > 0)
                {
                    $mensaje = "Usuario agregado.";
                }

                fclose($file);

            }
            catch(Exception $e)
            {
                echo "<br/>";
                echo "". $e->getMessage() ."";
                echo "<br/>";
            }
        }

        return $mensaje;
    }

    public static function mostrarUsuarios()
    {
        $resultado = array();

        try
        {
            $file = fopen("./usuarios.csv", "r");

            while(!feof($file))
            {
                $usuarioLeido = fgetcsv($file, 0, ",");
                if($usuarioLeido !== false)
                {
                    $nuevoUsuario = new Usuario($usuarioLeido[0], $usuarioLeido[1], $usuarioLeido[2]);
                    array_push($resultado, $nuevoUsuario);
                }
            }
            
            fclose($file);
            
        }
        catch(Exception $e)
        {
            echo "<br/>";
            echo "". $e->getMessage() ."";
            echo "<br/>";
        }
        
        return $resultado;
    }
}

//LOGICA DEL SITIO

if (isset($_GET["listado"])) 
{
    switch($_GET["listado"])
    {
        case "usuarios":
            $usuarios = Usuario::mostrarUsuariosCSV();
            echo "<h1>Usuarios</h1>";
            echo "<ul>";
            foreach ($usuarios as $usuario)
            {
                echo "<li>" . $usuario->getUsuario() ."</li>";
                echo "<li>" . $usuario->getMail() ."</li>";
            }
            echo "</ul>";
            
        break;
        default:
            echo "<h1>Listado no encontrado.</h1></br><p>Ingrese otra opción e inténtelo nuevamente</p>";
        break;
    }
}
else
{
    echo "<h1>No se ha recibido información</h1>";
}

?>