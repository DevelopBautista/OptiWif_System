<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');
require_once '../../models/modelo_pago.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validar y capturar datos
    $id_mensualidad    = $_POST['id_mensualidad'];
    $monto_total_pagar = $_POST['monto_total_pagar'];
    $fecha_pago        = $_POST['fecha_pago'] ?? date('Y-m-d');
    $metodo_pago       = $_POST['metodo_pago'];
    $referencia_pago   = $_POST['referencia_pago'] ?? '';
    $observaciones     = $_POST['observaciones'] ?? '';
    $dias_mas          = $_POST['dias_mas'] ?? '';
    $cargo_extra       = $_POST['cargo_extra'] ?? '';

    $pagoServicio = new modelo_pago();

    // Validar si hay caja abierta
    if (!$pagoServicio->hayCajaAbierta()) {
        echo json_encode([
            'exito' => false,
            'mensaje' => 'No se puede registrar el pago porque no hay una caja abierta.'
        ]);
        exit;
    }

    $estadoPago = $pagoServicio->verificar_pago_existe($id_mensualidad);

    if ($estadoPago === true) {
        echo json_encode([
            'exito' => false,
            'mensaje' => 'Esta mensualidad ya fue pagada anteriormente.'
        ]);
        exit;
    } elseif ($estadoPago === null) {
        echo json_encode([
            'exito' => false,
            'mensaje' => 'Mensualidad no encontrada.'
        ]);
        exit;
    }

    $resultado = $pagoServicio->registrar_pago(
        $id_mensualidad,
        $monto_total_pagar,
        $fecha_pago,
        $metodo_pago,
        $referencia_pago,
        $observaciones,
        $dias_mas,
        $cargo_extra
    );

    $response = json_decode($resultado);

    if (isset($response->success) && $response->success) {
        echo json_encode([
            'exito' => true,
            'mensaje' => 'Pago registrado exitosamente',
            'nfactura' => $response->nfactura
        ]);
    } else {
        echo json_encode([
            'exito' => false,
            'mensaje' => 'Error al registrar el pago'
        ]);
    }
}
