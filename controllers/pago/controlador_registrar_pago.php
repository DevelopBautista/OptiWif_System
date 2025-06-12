<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once '../../models/modelo_pago.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validar y capturar datos
    $id_mensualidad  = $_POST['id_mensualidad'];
    $monto_total_pagar    = $_POST['monto_total_pagar'];
    $fecha_pago      = $_POST['fecha_pago'] ?? date('Y-m-d'); // la fecha que se hace el pago
    $metodo_pago     = $_POST['metodo_pago'];
    $referencia_pago = $_POST['referencia_pago'] ?? '';
    $observaciones   = $_POST['observaciones'] ?? '';
    $dias_mas = $_POST['dias_mas'] ?? '';
    $cargo_extra   = $_POST['cargo_extra'] ?? '';

    $pagoServicio = new modelo_pago();

    if ($pagoServicio->verificar_pago_existe($id_mensualidad)) {
        return;
    }

    $resultado = $pagoServicio->registrar_pago(
        $id_mensualidad,
        $monto_total_pagar,
        $fecha_pago,
        $metodo_pago,
        $referencia_pago,
        $observaciones,
        $dias_gracia,
        $cargo_extra
    );



    if ($resultado) {
        echo json_encode([
            'exito' => true,
            'mensaje' => 'Pago registrado exitosamente'
        ]);
    } else {
        echo json_encode([
            'exito' => false,
            'mensaje' => 'Error al registrar el pago'
        ]);
    }
}
