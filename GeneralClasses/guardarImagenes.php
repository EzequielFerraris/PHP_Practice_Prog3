<?php

include_once "validador.php";
class guardadorDeImagenes
{

    public function guardarImagen(string $fileName, int $max_size)
    {
        if(is_string($fileName) && Validador::es_entero_positivo($max_size))
        {
            if(isset($_FILES['archivo']))
            {
                $carpetaImg = './ImagenesDeHelados/2024/';
                $fileType = $_FILES['archivo']['type'];
                $fileSize = $_FILES['archivo']['size'];

                $route = $carpetaImg . $fileName;

                if (!((strpos($fileType, "png") || strpos($fileType, "jpeg")))) 
                {
                    echo "Tipo de archivo no aceptado. Se permiten archivos .png o .jpg";
                }
                else if (($fileSize > $max_size))
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
        else
        {
            echo "Error, Alguno de los parámetros ingresados es inválido. Inténtelo nuevamente.";
        }
        
    }
}


?>