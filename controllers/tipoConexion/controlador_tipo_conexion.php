<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once("../../models/modelo_tipo_conexion.php");

$MU = new modelo_tipo_conexion();
$consulta = $MU->listar_tipo_conexion();

echo json_encode($consulta);

