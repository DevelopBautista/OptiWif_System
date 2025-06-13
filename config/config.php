<?php
// esta sera mi url base con ella podre usarla sin usar los ../../Xruta
const  SERVERURL = "http://localhost/wispManager/";
const COMAPANY = "OptiWiF System";
const MONEDA = "RD$";
//get time and date , after format to string
define('FECHA_HORA', (new DateTime("now", new DateTimeZone("America/Santo_Domingo")))->format('Y-m-d H:i:s'));
