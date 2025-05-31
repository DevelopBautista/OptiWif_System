<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once("../../models/modelo_plan.php");

$nombre_plan = htmlspecialchars($_POST['nombre_plan'], ENT_QUOTES, 'UTF-8');
$velocidad     = htmlspecialchars($_POST['velocidad'], ENT_QUOTES, 'UTF-8');
$precio     = htmlspecialchars($_POST['precio'], ENT_QUOTES, 'UTF-8');

$MU = new modelo_Plan();
$consulta = $MU->crear_plan($nombre_plan, $velocidad, $precio);

echo $datos = json_encode($consulta);
