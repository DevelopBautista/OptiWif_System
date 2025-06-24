<?php
header('Content-Type: application/json');

$nfactura = isset($_POST['nfactura']) ? $_POST['nfactura'] : '';

if ($nfactura == '') {
    echo json_encode(['existe' => false]);
    exit;
}

$archivo = "../../views/libreporte/reports/facturas/" . $nfactura . ".pdf";

if (file_exists($archivo)) {
    echo json_encode([
        'existe' => true,
        'url' => "../../views/libreporte/reports/facturas/" . $nfactura . ".pdf"
    ]);
} else {
    echo json_encode(['existe' => false]);
}
