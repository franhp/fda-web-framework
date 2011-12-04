<?php

$user = new Login();
$settings = new Settings();
if ($user->isLogged() && $user->getUserRole() > 1) {
    echo '<ul>';
    echo '<li><a href="'.$settings->siteurl.'/'.$settings->urlParameters(1).'/admin/client">Chat Client Admin</a></li>';
    echo '<li><a href="'.$settings->siteurl.'/'.$settings->urlParameters(1).'/admin/blog">Blog Admin</a></li>';
    //echo '<li><a href="'.$settings->siteurl.'/'.$settings->urlParameters(1).'/admin/users">Users Admin</a></li>';
    echo '<ul>';
}
else echo ERROR;




?>