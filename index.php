<?php
require('settings.php');
$settings = new Settings();
$settings->bootstrap();

$style = new Style();
$style->head();
$style->header();
$section = $settings->urlParameters(2);
if($section == "users") include 'views/users_view.php';
else if($section == "blog") include 'views/blog.php';
else if($section == "login") include 'views/login_view.php';

$style->footer();

?>