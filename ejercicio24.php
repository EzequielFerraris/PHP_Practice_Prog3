<?php
use Vtiful\Kernel\Format;
//AUTOR: FERRARIS EZEQUIEL MANUEL
/*
Aplicación No 24 ( Listado JSON y array de usuarios)
Archivo: listado.php
método:GET
Recibe qué listado va a retornar(ej:usuarios,productos,vehículos,etc.),por ahora solo tenemos
usuarios).
En el caso de usuarios carga los datos del archivo usuarios.json.
se deben cargar los datos en un array de usuarios.
Retorna los datos que contiene ese array en una lista.
Hacer los métodos necesarios en la clase usuario
*/

if(isset($_GET['listado'])){
    switch($_SERVER['REQUEST_METHOD']){
        case 'GET':
            switch ($_GET['listado']){
                case 'usuarios':
                    include 'usuario.php';
                    $usuarios = Usuario::mostrarUsuariosJson();
                    
                    foreach ($usuarios as $usuario)
                    {
                        $usuarioN = Usuario::usuarioDesdeArray($usuario);
                        $usuarioN->imprimirUsuario();
                    }    
                    
                    break;
                default:
                    echo 'Listado no permitido';
                    break;
            }
            break;
        default:
            echo 'Petición no permitida';
            break;
    }
} else {
    echo 'Parámetro "listado" no enviado';
}