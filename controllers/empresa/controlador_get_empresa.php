<?php
require_once '../../models/modelo_empresa.php';

$modelo = new modelo_empresa();
$empresa = $modelo->obtener_empresa(); // método que debes tener

if ($empresa) {
    echo json_encode([
        'existe' => true,
        'empresa' => [
            'id_empresa' => $empresa['id_empresa'],
            'nombre' => $empresa['nombre'],
            'direccion' => $empresa['direccion'],
            'telefono' => $empresa['telefono'],
            'rnc' => $empresa['rnc'],
            'logo' => $empresa['logo'] ?? ''
        ]
    ]);
} else {
    echo json_encode(['existe' => false]);
}
