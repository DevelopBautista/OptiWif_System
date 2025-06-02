<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once("../../models/modelo_pago.php");

$MU = new modelo_pago();
$consulta = $MU->listar_pagos();

header('Content-Type: application/json');//esto sirve para asegurar que sea una respuesta Json

if ($consulta) {
    echo json_encode(['data' => $consulta], JSON_UNESCAPED_UNICODE);
} else {
    echo json_encode(['data' => []]);
}
