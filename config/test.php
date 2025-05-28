<?php 


/*$pass=password_hash("admin12345",PASSWORD_DEFAULT,['cost'=>12]);
echo $pass;*/

$dt = new DateTime("now", new DateTimeZone("America/Santo_Domingo"));
echo $dt->format('Y-m-d H:i:s');
?>