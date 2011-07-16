<?php 
include_once '../settings.php';
$settings = new Settings();
$settings->bootstrap();
$user = new Login();
$blog = new Blog();

?>