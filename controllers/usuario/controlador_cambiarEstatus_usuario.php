<?php
require_once("../../models/modelo_usuario.php");

$id_usuario = $_POST['id_usuario'];
$estatus = $_POST['estatus'];

$MU = new modelo_Usuario();
$consulta = $MU->cambiar_estatus_usuario($id_usuario, $estatus);

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
