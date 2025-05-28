<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once("../../models/modelo_plan.php");

$MU = new modelo_Plan();
$consulta = $MU->listar_plannes();

echo json_encode($consulta);

