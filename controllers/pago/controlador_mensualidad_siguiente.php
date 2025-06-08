<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once("../../models/model_mensualidades.php");

try {
    $modelo = new modelo_mensualidad();
    $modelo->generarMensualidadSiguiente();
    echo "Mensualidades generadas correctamente.\n";
} catch (Exception $e) {
    echo "Error al generar mensualidades: " . $e->getMessage();
}
