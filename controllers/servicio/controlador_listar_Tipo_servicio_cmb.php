<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once("../../models/modelo_servicio.php");

$MU = new modelo_servicio();
$consulta = $MU->listar_Servicios_modelo();

echo json_encode($consulta);
