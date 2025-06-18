<?php
require '../../models/modelo_empresa.php';
$empresa = new modelo_empresa();

$id_empresa = $_POST['id_empresa'] ?? '';
$direccion = $_POST['direccion'] ?? '';
$telefono = $_POST['tel'] ?? '';
$logo = null;

// Validar que venga el ID
if (empty($id_empresa)) {
    echo json_encode(["status" => "error", "mensaje" => "ID de empresa no recibido."]);
    exit;
}

// Verificar si se está subiendo un nuevo logo
if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
    $nombreArchivo = uniqid() . "_" . basename($_FILES['logo']['name']);
    $rutaTemporal = $_FILES['logo']['tmp_name'];
    $carpetaDestino = __DIR__ . "/../../views/logos/";

    // Crear carpeta si no existe
    if (!file_exists($carpetaDestino)) {
        mkdir($carpetaDestino, 0777, true);
    }

    $rutaDestino = $carpetaDestino . $nombreArchivo;

    if (move_uploaded_file($rutaTemporal, $rutaDestino)) {
        $logo = $nombreArchivo;
    } else {
        echo json_encode(["status" => "error", "mensaje" => "No se pudo subir el archivo."]);
        exit;
    }
}

// Llamar al modelo para actualizar
$respuesta = $empresa->update_Empresa($id_empresa,$direccion, $telefono,$logo);
echo json_encode($respuesta);
