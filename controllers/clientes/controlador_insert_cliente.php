<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once("../../models/modelo_cliente.php");

$nombres = htmlspecialchars($_POST['nombres'], ENT_QUOTES, 'UTF-8');
$ced     = htmlspecialchars($_POST['ced'], ENT_QUOTES, 'UTF-8');
$dir     = htmlspecialchars($_POST['dir'], ENT_QUOTES, 'UTF-8');
$tel     = htmlspecialchars($_POST['tel'], ENT_QUOTES, 'UTF-8');


$MU = new modelo_cliente();
$consulta = $MU->insertar_cliente($nombres, $ced, $dir, $tel);

echo $datos = json_encode($consulta);

