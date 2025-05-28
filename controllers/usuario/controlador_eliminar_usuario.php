<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once("../../models/modelo_usuario.php");

$id_usuario=$_POST['id_usuario'];

$MU = new modelo_Usuario();
$consulta = $MU->eliminar_usuarios($id_usuario);

echo $datos = json_encode($consulta);

