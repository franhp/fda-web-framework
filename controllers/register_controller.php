<?php

include_once '../settings.php';
$settings = new Settings();
$settings->bootstrap();

/* Create user */

$user = new Users();

if (!empty($_POST['exist_user'])) {
    if ($user->getUserId($_POST['exist_user']))
        echo "YES";
    else
        echo "NO";
}

if (!empty($_POST['nick'])) {

    if ($_POST['oauth'] == 'facebook' || $_POST['oauth'] == 'twitter')
        $oauth = $_POST['oauth'];
    else
        $oauth = 'none';

    if (!$user->getUserId($_POST['nick'])) {
        if ($user->createUser($_POST['nick'], $_POST['password'], $_POST['email'], $oauth)) {
            if ($oauth == 'facebook' || $oauth == 'twitter') echo $oauth;
            else echo "OK";
        }
        else
            echo "ERROR";
    }
    else
        echo "EXISTS";
}

?>