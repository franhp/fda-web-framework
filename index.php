<?php

require('settings.php');
$settings = new Settings();
$settings->bootstrap();
$style = new Style();
$section = $settings->urlParameters(2);
if ($section != 'client') {
    $style->head();
    $style->header();

    if ($section == 'users')
        include 'views/user_view.php';
    else if ($section == 'blog')
        include 'views/blog_view.php';
    else if ($section == 'login')
        include 'views/login_view.php';
    else if ($section == 'register')
        include 'views/register_view.php';

    else if ($section == 'admin') {
        if ($settings->urlParameters(3) == 'blog')
            include 'views/admin/admin_blog_view.php';
        else if ($settings->urlParameters(3) == 'client')
            include 'views/admin/admin_client_view.php';
    }
    else if ($section == 'user') {
        if ($settings->urlParameters(2) == 'user')
            include 'views/user_view.php';
    }
    else if ($section == 'test') {
        if ($settings->urlParameters(2) == 'test')
            include 'views/test_view.php';
    }
    else {
        include 'views/home_view.php';
    }
    $style->footer();
} else if (!empty($_SESSION['userid'])) {
    $style->headClient();
    $style->headerClient();
    if ($settings->urlParameters(3) != "") {
        include 'views/client/client_view.php';
    } else {
        include 'views/client/home_view.php';
    }
    $style->footerClient();
} else {
    header('Location: ' . $settings->siteurl . '');
}
?>