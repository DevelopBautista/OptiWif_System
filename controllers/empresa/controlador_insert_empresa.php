<?php
require '../../models/modelo_empresa.php';
$empresa = new modelo_empresa();

$nombre = $_POST['nombre_empresa'] ?? '';
$direccion = $_POST['direccion'] ?? '';
$telefono = $_POST['tel'] ?? '';
$rnc = $_POST['rnc'] ?? '';
$logo = null;

// Validar y mover el archivo subido solo si fue enviado
if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
    $permitidos = ['image/jpeg', 'image/png', 'image/jpg'];

    if (!in_array($_FILES['logo']['type'], $permitidos)) {
        echo json_encode(["status" => "error", "mensaje" => "Tipo de archivo no permitido"]);
        exit;
    }

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
        echo json_encode(["status" => "error", "mensaje" => "No se pudo subir el archivo"]);
        exit;
    }
}

// Insertar datos (aunque no haya logo)
$respuesta = $empresa->insertar_datos_empresa($nombre, $direccion, $telefono, $rnc, $logo);
echo json_encode($respuesta);
