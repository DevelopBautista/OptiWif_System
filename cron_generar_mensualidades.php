<?php
date_default_timezone_set('America/Santo_Domingo');

require_once __DIR__ . '/models/model_mensualidades.php';

// Crear instancia de conexión y modelo
$mensualidad = new modelo_mensualidad();

// Ejecutar generación automática
$mensualidad->generarMensualidadSiguiente();

// Registrar en log
file_put_contents(
    __DIR__ . '/logs/cron_mensualidades.log',
    "[" . date('Y-m-d H:i:s') . "] Generación de mensualidades ejecutada correctamente.\n",
    FILE_APPEND
);
