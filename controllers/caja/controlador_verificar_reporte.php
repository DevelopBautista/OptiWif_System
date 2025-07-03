<?php
require_once '../../models/modelo_BuscarFecha_caja.php';
$MC = new modelo_BuscarFecha_caja;

$fecha = $_GET['fecha'] ?? '';
$resultado = $MC->buscarCierrePorFecha($fecha);

if ($resultado) {
    echo json_encode(['exito' => true]);
} else {
    echo json_encode(['exito' => false]);
}
exit;
