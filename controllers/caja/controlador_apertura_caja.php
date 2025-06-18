<?php

require '../../models/modelo_apertura.php';
session_start();

$monto = $_POST['monto_inicial'];
$usuario = $_SESSION['id_user'];

$apertura = new modelo_apertura();

if ($apertura->existeCajaAbierta()) {
    echo json_encode(['status' => 'error', 'mensaje' => 'Ya hay una caja abierta.']);
    exit;
}

if ($apertura->abrirCaja($monto, $usuario)) {
    echo json_encode(['status' => 'ok', 'mensaje' => 'Caja abierta correctamente.']);
} else {
    echo json_encode(['status' => 'error', 'mensaje' => 'No se pudo abrir la caja.']);
}
