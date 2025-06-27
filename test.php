<?php
// Mostrar errores por si algo falla
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Requiere tu modelo y conexión
require_once 'models/model_mensualidades.php'; // <- ajusta esta ruta

$MU = new modelo_mensualidad();

try {
    $MU->generarMensualidadSiguiente();
    echo "✅ Mensualidades generadas correctamente si correspondía.<br>";
} catch (Exception $e) {
    echo "❌ Error al generar mensualidades: " . $e->getMessage();
}
