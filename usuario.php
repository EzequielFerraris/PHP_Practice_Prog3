<?php

class Usuario 
{
    private $_usuario = "";
    private $_password = "";
    private $_mail = "";
    private $_valid = false;
    private $_ID;
    private $_fechaAlta;

    public function __construct(string $usuario, string $password, string $mail, int $ID=0, string $fechaAlta= "")
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
                        if ($ID == 0)
                        {
                            $this->_ID = rand(1, 10000);
                        }
                        else
                        {
                            $this->_ID = $ID;
                        }
                        if ($fechaAlta === "")
                        {
                            $this->_fechaAlta = (new DateTime)->format("d/m/Y");
                        }
                        else
                        {
                            $this->_fechaAlta = $fechaAlta;
                        }
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

    public function getValid(): bool
    {
        return $this->_valid;
    }

    public function getID(): string
    {
        return $this->_ID;
    }

    public function getFecha(): string
    {
        return $this->_fechaAlta;
    }

    private function getPassword(): string
    {
        return $this->_password;
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

    public function getData()
    {
        $u = $this->getUsuario();
        $pass = $this->getPassword();
        $mail = $this->getMail();
        $ID = $this->_ID;
        $fecha = $this->_fechaAlta;

        $data = array("usuario"=>$u, "password"=>$pass, "mail"=>$mail, "ID"=>$ID, "fecha"=>$fecha);
        
        return $data;
    }

    public function imprimirUsuario()
    {

        $data = $this->getData();
        foreach ($data as $key => $value)
        {
            echo "".$key.": ".$value."";
            echo "</br>";
        }
    }

    public function guardarUsuarioCSV()
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

    public static function mostrarUsuariosCSV()
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

    public function guardarUsuarioJSON(string $ruta)
    {

        $mensaje = "Uno o más de los campos está vacío o es incorrecto. Cambie la información necesaria e inténtelo nuevamente.";
        
        if($this->_valid)
        {
            $nombreArchivo = "usuarios.json";
            $archivo_ruta = $ruta . $nombreArchivo;

            if(!file_exists($archivo_ruta))
            {
                try
                {
                    $file = fopen($ruta . $nombreArchivo, "a+");
                    $data = $this->getData();
                    $arrayData = array($data);
                    $json = json_encode($arrayData);
                    $chars = fwrite($file, $json);

                    fclose($file);

                    if($chars > 0)
                    {
                        $mensaje = "Usuario agregado.";
                    }

                }
                catch(Exception $e)
                {
                    echo "<br/>";
                    echo "". $e->getMessage() ."";
                    echo "<br/>";
                }
            }
            else
            {
                $data = $this->getData();
                $arrayGeneral = Usuario::mostrarUsuariosJson();
                array_push($arrayGeneral, $data);
                try
                {

                    $file = fopen($ruta . $nombreArchivo, "w");
                    $json = json_encode($arrayGeneral);
                    $chars = fwrite($file, $json);

                    fclose($file);

                    if($chars > 0)
                    {
                        $mensaje = "Usuario agregado.";
                    }

                }
                catch(Exception $e)
                {
                    echo "<br/>";
                    echo "". $e->getMessage() ."";
                    echo "<br/>";
                }
            }

        }
        return $mensaje;
    }

    public static function mostrarUsuariosJson()
    {
        $resultado = array();

        if(file_exists("./usuarios.json"))
        {
            try
            {
                $file = fopen("./usuarios.json", "r");
                $contenido = fread($file, filesize("./usuarios.json"));
                $arrayDeUsuarios = json_decode($contenido, true);
                
                               
                fclose($file);
                
                $resultado = $arrayDeUsuarios;

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
    
    public static function usuarioDesdeArray(array $usuarioNuevo)
    {
        switch(count($usuarioNuevo))
        {
            case 3:
                $usuarioInstanciado = new Usuario($usuarioNuevo["usuario"], $usuarioNuevo["password"], $usuarioNuevo["mail"]);
            break;
            case 4:
                $usuarioInstanciado = new Usuario($usuarioNuevo["usuario"], $usuarioNuevo["password"], $usuarioNuevo["mail"], $usuarioNuevo["ID"]);
            break;
            case 5:
                $usuarioInstanciado = new Usuario($usuarioNuevo["usuario"], $usuarioNuevo["password"], $usuarioNuevo["mail"], $usuarioNuevo["ID"], $usuarioNuevo["fecha"]);
            break;
            default:
                echo "Error. Parámetro desconocido.";
            break;            
        }
        return $usuarioInstanciado;
    }

    public static function obtenerUnUsuario(string $nombre)
    {
        $usuarioEncontrado = null;
        $listaUsuarios = Usuario::mostrarUsuariosJson();
        foreach ($listaUsuarios as $u)
        {
            if ($u["usuario"] == $nombre)
            { 
                $usuarioEncontrado = Usuario::usuarioDesdeArray($u);
            }
        }
        
        return $usuarioEncontrado;
    }

}

?>