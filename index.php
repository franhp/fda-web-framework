<?php
require('settings.php');
$settings = new Settings;
$settings->bootstrap();


$style = new Style();
$style->header();


$section = $settings->urlParameters(2);
if($section == "users") include 'users.php';
else if($section == "blog") include 'blog.php';
else if($section == "login") include 'login.php';

$style->footer();

?>