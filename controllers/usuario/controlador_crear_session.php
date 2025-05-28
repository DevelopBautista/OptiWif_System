<?php
//get vars from ajax
$id_user = $_POST['id_user'];
$user = $_POST['user'];
$rol = $_POST['rol'];

//start session
session_start();
$_SESSION['id_user'] = $id_user;
$_SESSION['user'] = $user;
$_SESSION['rol'] = $rol;
