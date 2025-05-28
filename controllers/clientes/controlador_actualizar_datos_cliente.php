<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once("../../models/modelo_cliente.php");

$dir  = htmlspecialchars($_POST['dir'] ?? '');
$tel  = htmlspecialchars($_POST['tel'] ?? '');
$id    = htmlspecialchars($_POST['id'] ?? '');

$MU = new modelo_cliente();

$consulta = $MU->actualizar_datos_cliente($id, $dir, $tel);

echo json_encode($consulta);
