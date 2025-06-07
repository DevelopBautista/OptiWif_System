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
$acceso_cliente = htmlspecialchars($_POST['acceso_cliente'], ENT_QUOTES, 'UTF-8');
$observaciones = htmlspecialchars($_POST['observaciones'], ENT_QUOTES, 'UTF-8');
$fecha_contrato = htmlspecialchars($_POST['fecha_contrato'], ENT_QUOTES, 'UTF-8');
$dias_mas = htmlspecialchars($_POST['dias_mas'], ENT_QUOTES, 'UTF-8');
$cargo_extra = htmlspecialchars($_POST['cargo_extra'], ENT_QUOTES, 'UTF-8');
//...............................................................................................

$MU = new modelo_servicio();

$consulta = $MU->contrato_servicio(
    $id_cliente,
    $id_plan,
    $id_tipo_conexion,
    $id_servicio,
    $fecha_contrato,
    $acceso_cliente,
    $observaciones,
    $dias_mas,
    $cargo_extra
);

echo $datos = json_encode($consulta);
