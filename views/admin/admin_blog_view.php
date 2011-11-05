<?php

$user = new Login();
if ($user->isLogged() && $user->getUserRole() > 1) {
    echo 'uou';
} else {
    echo 'No tienes permiso para acceder a esta seccion :-(';
}
?>