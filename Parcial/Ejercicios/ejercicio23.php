<?php
use Vtiful\Kernel\Format;
//AUTOR: FERRARIS EZEQUIEL MANUEL
/*
Aplicación No 23 (Registro JSON)
Archivo: registro.php
método:POST
Recibe los datos del usuario(nombre, clave,mail )por POST ,
crea un ID autoincremental(emulado, puede ser un random de 1 a 10.000). crear un dato con la
fecha de registro , toma todos los datos y utilizar sus métodos para poder hacer el alta,
guardando los datos en usuarios.json y subir la imagen al servidor en la carpeta
Usuario/Fotos/.
retorna si se pudo agregar o no.
Hacer los métodos necesarios en la clase usuario.
*/

include_once "./usuario.php";
date_default_timezone_set("America/Argentina/Buenos_Aires");

$carpetaDestino = "./Usuario/Fotos/";

$usuarioRecibido = new Usuario($_POST["nombre"], $_POST["pass"], $_POST["mail"]);

$usuarioRecibido->guardarUsuarioJSON("./");

if(isset($_FILES['archivo']))
{
    $carpetaImg = './';
    $fileName = $_FILES['archivo']['name'];
    $fileType = $_FILES['archivo']['type'];
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

?>