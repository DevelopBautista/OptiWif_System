<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once("../../models/model_mensualidades.php");

$MU = new modelo_mensualidad();

try {
    // Solo necesitas llamar a esta, ya incluye todo lo demás
    $MU->generarMensualidadSiguiente();

    $response = [
        "status" => "ok",
        "mensaje" => "Mensualidades generadas correctamente."
    ];
} catch (Exception $e) {
    $response = [
        "status" => "error",
        "mensaje" => "Error al generar mensualidades: " . $e->getMessage()
    ];
}

header('Content-Type: application/json');
echo json_encode($response);
