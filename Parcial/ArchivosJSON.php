<?php

class ArchivosJSON
{
    private string $_ruta;
    private string $_nombreArchivo;
    private array $_dataAEscribir;
    private bool $is_valid = true;

    public static function leerJSON(string $ruta, string $nombreArchivo) : array
    {
        $is_valid = true;
        $resultado = array();

        if(!is_string($ruta) || !is_string($nombreArchivo))
        {
            $is_valid = false;
        }

        if($is_valid)
        {
            $rutaCompleta = $ruta . $nombreArchivo;

            if (file_exists($rutaCompleta) && filesize($rutaCompleta) > 0)
            {
                try
                {
                    $file = fopen($rutaCompleta, "r");
        
                    $contenido = fread($file, filesize($rutaCompleta));
                    $arrayDeDatos = json_decode($contenido, true);
                            
                    fclose($file);
                    
                    $resultado = $arrayDeDatos;

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
                try
                {
                    $file = fopen($rutaCompleta, "w");
        
                    fclose($file);

                }
                catch(Exception $e)
                {
                    echo "<br/>";
                    echo "". $e->getMessage() ."";
                    echo "<br/>";
                }
            }
            
        }
        return $resultado;
    }
        
    public static function escribirJSON(string $ruta, string $nombreArchivo, array $dataAEscribir) : bool
    {
        $resultado = false;
        $is_valid = true;

        if(!is_string($ruta) || !is_string($nombreArchivo) 
            || !is_array($dataAEscribir) || count($dataAEscribir) == 0)
        {
            $is_valid = false;
        }

        if($is_valid)
        {
            try
            {
                $file = fopen($ruta . $nombreArchivo, "w");
                
                $json = json_encode($dataAEscribir);
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
        }
        
        return $resultado;
    }  
}


?>