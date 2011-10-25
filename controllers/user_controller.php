<?php

include_once '../settings.php';
$settings = new Settings();
$settings->bootstrap();

$user = new Users();

if (isset($_POST['update'])) {

    if ($user->updateUser(
                    $_POST['name'], $_POST['lastname'], $_POST['anio'] . "-" . $_POST['mes'] . "-" . $_POST['dia'], $_POST['location'])) {
        echo "OK";
    }
    else
        echo "ERROR";
}
?>