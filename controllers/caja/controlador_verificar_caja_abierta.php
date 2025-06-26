<?php
require_once '../../models/modelo_apertura.php';
$apertura = new modelo_apertura();
$status=$apertura->existeCajaAbierta();

$_SESSION['STATUS_BOX']=$status;

$respuesta = [
    'caja_abierta' => $status
];

header('Content-Type: application/json');
echo json_encode($respuesta);
