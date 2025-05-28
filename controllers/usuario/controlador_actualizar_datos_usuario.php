<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once("../../models/modelo_usuario.php");

$dir_up   = htmlspecialchars($_POST['dir_up'] ?? '');
$tel_up   = htmlspecialchars($_POST['tel_up'] ?? '');
$user_up  = htmlspecialchars($_POST['user_up'] ?? '');
$pwsd_up  = $_POST['pwsd_up'];
$rol_up   = htmlspecialchars($_POST['rol_up'] ?? '');
$id_up    = htmlspecialchars($_POST['id_up'] ?? '');

$MU = new modelo_Usuario();

$claveHash = '';
if (!empty($pwsd_up)) {
    $claveHash = password_hash($pwsd_up, PASSWORD_DEFAULT, ['cost' => 12]);
}

// Llamada al método con la contraseña hasheada
$consulta = $MU->actualizar_datos_usuarios($id_up, $tel_up, $dir_up, $user_up, $claveHash, $rol_up);

echo json_encode($consulta);
