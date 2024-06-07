<?php
//AUTOR: FERRARIS EZEQUIEL MANUEL
/*
Aplicación No 22 ( Login)
Archivo: Login.php
método:POST
Recibe los datos del usuario(clave,mail )por POST ,
crear un objeto y utilizar sus métodos para poder verificar si es un usuario registrado, Retorna
un :
“Verificado” si el usuario existe y coincide la clave también.
“Error en los datos” si esta mal la clave.
“Usuario no registrado si no coincide el mail“
Hacer los métodos necesarios en la clase usuario.
*/

include_once "./classUsuario.php";

$nuevoUsuario = new Usuario($_POST["usuario"], $_POST["pass"], $_POST["mail"]);

echo "<h1>Usuario:</h1><br/>";
echo "<ul>" . "<li>Usuario: " . $nuevoUsuario->getUsuario() . "</li></br>";
echo "<li>Mail: " . $nuevoUsuario->getMail() . "</li></br>";
echo "<li>VALIDACIÓN:</li></br>";

switch ($nuevoUsuario->validarUsuario()) 
{
    case "-1":
        echo "<p>El usuario no se encuentra registrado en la base de datos. Cree un usuario nuevo.</p>";
    break;
    case "0":
        echo "<p>Error en los datos. Password incorrecto.</p>";
    break;
    case "1":
        echo "<p>Verificado.</p>";
    break;
}


?>