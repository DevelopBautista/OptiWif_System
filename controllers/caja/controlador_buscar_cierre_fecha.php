<?php
require_once '../../models/modelo_BuscarFecha_caja.php';
$MC = new modelo_BuscarFecha_caja;

$fecha = $_POST['fecha'];
$resultado = $MC->buscarCierrePorFecha($fecha);

if ($resultado) {
    echo json_encode([
        'exito' => true,
        'datos' => $resultado
    ]);
} else {
    echo json_encode([
        'exito' => false,
        'mensaje' => 'No se encontró cierre para esa fecha.'
    ]);
}
