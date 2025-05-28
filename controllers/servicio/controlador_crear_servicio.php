<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once("../../config/config.php");
require_once("../../models/modelo_servicio.php");

$id_cliente = htmlspecialchars($_POST['IdCliente'], ENT_QUOTES);
$id_plan = htmlspecialchars($_POST['cmb_planes'], ENT_QUOTES);
$referencia_direccion = htmlspecialchars($_POST['referenciaDir'], ENT_QUOTES, 'UTF-8');
$id_tipoConexion = htmlspecialchars($_POST['cmb_conexion'], ENT_QUOTES);
$datos_conexion = htmlspecialchars($_POST['datos_conexion'], ENT_QUOTES); ///porque ?, porque tengo por entendido que es las vasr de ajax
$fecha_creacion = $fecha_hora_forma;


$MU = new modelo_servicio();
$consulta = $MU->crear_servicio_modelo($id_cliente, $id_plan, $referencia_direccion, $id_tipoConexion, $datos_conexion, $fecha_creacion);

echo $datos = json_encode($consulta);
