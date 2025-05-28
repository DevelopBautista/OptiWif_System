<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once("../../models/modelo_usuario.php");

$nombres = htmlspecialchars($_POST['nombres'], ENT_QUOTES, 'UTF-8');
$dir     = htmlspecialchars($_POST['dir'], ENT_QUOTES, 'UTF-8');
$tel     = htmlspecialchars($_POST['tel'], ENT_QUOTES, 'UTF-8');
$user    = htmlspecialchars($_POST['user'], ENT_QUOTES, 'UTF-8');
$pswd    = password_hash($_POST['pswd'], PASSWORD_DEFAULT, ['cost' => 12]); // encriptar contraseña
$ced     = htmlspecialchars($_POST['ced'], ENT_QUOTES, 'UTF-8');
$id_rol  = htmlspecialchars($_POST['id_rol'], ENT_QUOTES, 'UTF-8');

$MU = new modelo_Usuario();
$consulta = $MU->insertar_usuario($nombres, $dir, $tel, $user, $pswd, $ced, $id_rol);

echo $datos = json_encode($consulta);
