<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once("../../models/modelo_plan.php");

$nombre = htmlspecialchars($_POST['nombre'], ENT_QUOTES, 'UTF-8');
$descripcion     = htmlspecialchars($_POST['descripcion'], ENT_QUOTES, 'UTF-8');
$precio     = htmlspecialchars($_POST['precio'], ENT_QUOTES, 'UTF-8');

$MU = new modelo_Plan();
$consulta = $MU->crear_plan($nombre, $descripcion, $precio);

echo $datos = json_encode($consulta);
