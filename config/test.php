<?php 

$pass=password_hash("admin12345",PASSWORD_DEFAULT,['cost'=>12]);
echo $pass;