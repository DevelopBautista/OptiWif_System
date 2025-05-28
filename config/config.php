<?php
// esta sera mi url base con ella podre usarla sin usar los ../../Xruta
const  SERVERURL = "http://localhost/wispManager/";
const COMAPANY = "OptiWiF System";
const MONEDA = "RD$";
//get time and date , after format to string
$fecha_hora  = new DateTime("now", new DateTimeZone("America/Santo_Domingo"));
$fecha_hora_forma = $fecha_hora->format('Y-m-d H:i:s');
