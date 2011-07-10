<?php
require('settings.php');
$settings = new Settings();
$settings->bootstrap();

$style = new Style();
$style->head();
$style->header();
$section = $settings->urlParameters(2);
if($section == 'users') include 'views/user_view.php';
else if($section == 'blog') include 'views/blog_view.php';
else if($section == 'login') {
	if($settings->urlParameters(3) == 'oauth') include 'vies/login_oauth_view.php';
	else if($settings->urlParameters(3) == 'openid') include 'vies/login_openid_view.php';
	else include 'views/login_view.php';
}
else if($section == 'register') include 'views/register_view.php';

$style->footer();


?>