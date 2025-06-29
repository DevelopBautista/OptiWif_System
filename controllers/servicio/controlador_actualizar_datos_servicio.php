<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once("../../models/modelo_servicio.php");

$id_cliente   = htmlspecialchars($_POST['id_cliente'] ?? '');
$id_plan   = htmlspecialchars($_POST['id_plan'] ?? '');
$id_tipo_conexion  = htmlspecialchars($_POST['id_tipo_conexion'] ?? '');
$acceso_cliente   = htmlspecialchars($_POST['acceso_cliente'] ?? '');
$nueva_fecha_pago   = htmlspecialchars($_POST['nueva_fecha_pago'] ?? '');
$id_cs = htmlspecialchars($_POST['id_contrato'] ?? '');

$MU = new modelo_servicio();


$consulta = $MU->actualizar_datos_servicio(
    $id_cliente,
    $id_plan,
    $id_tipo_conexion,
    $acceso_cliente,
    $nueva_fecha_pago,
    $id_cs
);

echo json_encode($consulta);
