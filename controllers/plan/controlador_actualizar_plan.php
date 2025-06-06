<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once("../../models/modelo_plan.php");

$nombre   = htmlspecialchars($_POST['nombre'] ?? '');
$velocidad   = htmlspecialchars($_POST['velocidad'] ?? '');
$precio  = htmlspecialchars($_POST['precio'] ?? '');
$id    = htmlspecialchars($_POST['id'] ?? '');

$MU = new modelo_Plan();

$consulta = $MU->actualizar_planes($id, $nombre, $velocidad, $precio);

echo json_encode($consulta);
