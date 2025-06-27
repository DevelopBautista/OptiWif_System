<?php
date_default_timezone_set('America/Santo_Domingo');
require_once __DIR__ . '/models/model_mensualidades.php';

$mensualidad = new modelo_mensualidad();
$mensualidad->generarMensualidadSiguiente();

file_put_contents(__DIR__ . '/logs/cron_mensualidades.log', "[" . date('Y-m-d H:i:s') . "] Ejecutado correctamente\n", FILE_APPEND);