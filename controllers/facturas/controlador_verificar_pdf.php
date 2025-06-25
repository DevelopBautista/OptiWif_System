<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
header('Content-Type: application/json');

require_once "../../config/config.php";

$nfactura = $_POST['nfactura'] ?? '';

// Ruta física
$archivo = __DIR__ . "/../../views/libreporte/reports/facturas/" . $nfactura . ".pdf";

if (file_exists($archivo)) {
    echo json_encode([
        'existe' => true,
        'url' => SERVERURL . "views/libreporte/reports/facturas/" . $nfactura . ".pdf"
    ]);
} else {
    echo json_encode([
        'existe' => false,
        'debug' => [
            'archivo' => $archivo,
            'nfactura' => $nfactura
        ]
    ]);
}
