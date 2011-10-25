<?php

require('settings.php');
$settings = new Settings();
$settings->bootstrap();

$style = new Style();
$section = $settings->urlParameters(3);
if ($section != 'client') {
    $style->head();
    $style->header();

    if ($section == 'users')
        include 'views/user_view.php';
    else if ($section == 'blog')
        include 'views/blog_view.php';
    else if ($section == 'login') {
        if ($settings->urlParameters(3) == 'oauth')
            include 'views/login_oauth_view.php';
        else if ($settings->urlParameters(3) == 'openid')
            include 'views/login_openid_view.php';
        else
            include 'views/login_view.php';
    }
    else if ($section == 'register')
        include 'views/register_view.php';
    else if ($section == 'admin') {
        if ($settings->urlParameters(3) == 'blog')
            include 'views/admin_blog_view.php';
    }
    else if ($section == 'user') {
        if ($settings->urlParameters(3) == 'user')
            include 'views/user_view.php';
    }
    else {
        include 'views/home_view.php';
    }

    $style->footer();
} else if(!empty($_SESSION['userid'])){
    $style->headClient();
    $style->headerClient();
    include 'views/client/home_view.php';
    $style->footerClient();
}
else{
    header( 'Location: '.$settings->siteurl.'');
}
?>