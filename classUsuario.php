<?php

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

    public function validarUsuario() : int
    {
        $resultado = -1;
        $usuarios = Usuario::mostrarUsuarios();

        foreach($usuarios as $usuarioEscaneado)
        {
            if($this->getUsuario() == $usuarioEscaneado->getUsuario() & $this->getMail() == $usuarioEscaneado->getMail())
            {
                $resultado = 0; 
                if($usuarioEscaneado->validarPassword($this->getPassword()))
                {
                    $resultado = 1;
                }
                break;
            } 
        }
        return $resultado;
    }
}

?>