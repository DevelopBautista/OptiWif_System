<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once("../../models/modelo_empresa.php");

$direccion   = htmlspecialchars($_POST['direccion'] ?? '');
$tel   = htmlspecialchars($_POST['tel'] ?? '');
$id    = htmlspecialchars($_POST['id'] ?? '');

$MU = new modelo_empresa();

$consulta = $MU->actualizar_datos_empresa($direccion, $tel, $id);

echo json_encode($consulta);
