<?php
require '../../models/modelo_apertura.php';
session_start();

// Validación básica
if (!isset($_POST['monto_inicial']) || !is_numeric($_POST['monto_inicial'])) {
    echo json_encode(['status' => 'error', 'mensaje' => 'Monto inválido.']);
    exit;
}

$monto = $_POST['monto_inicial'];
$usuario = $_SESSION['id_user'];

$apertura = new modelo_apertura();

header('Content-Type: application/json');

// Verificar si ya hay una caja abierta
if ($apertura->existeCajaAbierta()) {
    echo json_encode([
        'status' => 'error',
        'mensaje' => 'Ya hay una caja abierta.'
    ]);
    return;
}

// Intentar abrir la caja
if ($apertura->abrirCaja($monto, $usuario)) {
    echo json_encode([
        'status' => 'ok',
        'mensaje' => 'Caja abierta correctamente.'
    ]);
} else {
    echo json_encode([
        'status' => 'error',
        'mensaje' => 'No se pudo abrir la caja.'
    ]);
}
