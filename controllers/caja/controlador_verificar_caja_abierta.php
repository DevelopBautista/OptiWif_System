<?php
require_once '../../models/modelo_apertura.php';
$apertura = new modelo_apertura();

$respuesta = [
    'caja_abierta' => $apertura->existeCajaAbierta()
];

header('Content-Type: application/json');
echo json_encode($respuesta);
