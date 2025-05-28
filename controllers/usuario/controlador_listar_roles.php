<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once("../../models/modelo_usuario.php");

$MU = new modelo_Usuario();
$consulta = $MU->listar_roles();

echo json_encode($consulta);
