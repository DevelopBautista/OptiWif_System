<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__."/../../models/model_mensualidades.php";

$MU = new modelo_mensualidad();

$resultado = $MU->generar_Mensualidades();

$response = [
    "status" => "ok",
    "mensaje" => "Mensualidades generadas correctamente.",
    "resultado" => $resultado
];

echo json_encode($response);
