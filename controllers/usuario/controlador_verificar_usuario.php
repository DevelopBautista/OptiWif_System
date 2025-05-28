<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once("../../models/modelo_usuario.php");

$usuario = htmlspecialchars($_POST['user'], ENT_QUOTES, 'UTF-8');
$password = htmlspecialchars($_POST['pass'], ENT_QUOTES, 'UTF-8');


$MU = new modelo_Usuario();
$consulta = $MU->verificar_usuario($usuario, $password);

$datos = json_encode($consulta);


if (count($consulta) > 0) {
    echo  $datos;
} else {
    echo  0;
}
