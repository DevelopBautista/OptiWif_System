<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once("../../models/modelo_pago.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_mensualidad = $_POST['id_mensualidad'];
    $monto = $_POST['monto'];
    $fecha = date('Y-m-d');

    $MP = new modelo_pago();
    $ok = $MP->registrar_pago($id_mensualidad, $monto, $fecha);

    echo json_encode([
        "status" => $ok ? "ok" : "error",
        "mensaje" => $ok ? "Pago registrado correctamente." : "Error al registrar el pago."
    ]);
    exit;
}
