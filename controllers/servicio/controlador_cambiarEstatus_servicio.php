<?php
require_once("../../models/modelo_servicio.php");

$id = $_POST['id'];
$estatus = $_POST['estatus'];

$MU = new modelo_servicio();
$consulta = $MU->cambiar_estado_servicio($id, $estatus);

header('Content-Type: application/json');

if ($consulta) {
    echo json_encode([
        'success' => true,
        'message' => 'Estatus cambiado correctamente'
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'No se pudo cambiar el estatus'
    ]);
}
