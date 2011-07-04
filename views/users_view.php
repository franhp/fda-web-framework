<?php
$user = new Users();
if($user->role > 2) $user->listUsers();
else echo 'No tienes permisos';
?>