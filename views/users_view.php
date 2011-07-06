<?php
$user = new Login();
if($user->isLogged()&&$user->getUserRole()>2) $user->listUsers();
else echo 'No tienes permisos';
?>