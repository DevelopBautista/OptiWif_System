<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once("../../config/config.php");
require_once("../../models/modelo_servicio.php");

$id_cliente = htmlspecialchars($_POST['id_cliente'], ENT_QUOTES);
$id_plan = htmlspecialchars($_POST['id_plan'], ENT_QUOTES);
$id_tipo_conexion = htmlspecialchars($_POST['id_tipo_conexion'], ENT_QUOTES, 'UTF-8');
$id_servicio = htmlspecialchars($_POST['id_servicio'], ENT_QUOTES);
$direccion_referencia = htmlspecialchars($_POST['direccion_referencia'], ENT_QUOTES);
$fecha_inicio =date('Y-m-d H:i:s');


$MU = new modelo_servicio();
$consulta = $MU->crear_servicio_modelo($id_cliente, $id_plan, $id_tipo_conexion, $id_servicio, $direccion_referencia, $fecha_inicio);

echo $datos = json_encode($consulta);
