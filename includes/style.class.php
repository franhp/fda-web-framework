<?php

class Style {

    public function head($metatags = FALSE) {
        $settings = new Settings();

        echo '<html>
		<head>
		<title>' . $settings->sitename . '</title>
		<link href="' . $settings->siteurl . '/css/style.css" media="screen" rel="stylesheet" type="text/css" />
		<script src="' . $settings->siteurl . '/js/jquery-1.6.2.min.js" type="text/javascript"></script>
		<script src="' . $settings->siteurl . '/js/jquery-ui-1.8.14.min.js" type="text/javascript"></script>
		<link href="' . $settings->siteurl . '/css/jquery-ui-1.8.14.css" media="screen" rel="stylesheet" type="text/css" />
		<script src="' . $settings->siteurl . '/js/jquery.scrollTo.js" type="text/javascript"></script>
		<script type="text/javascript" src="' . $settings->siteurl . '/external/ckeditor/ckeditor.js"></script>
		<script type="text/javascript" src="' . $settings->siteurl . '/external/ckeditor/adapters/jquery.js"></script>
		</head>
		<body>
                <div id="wrapper">';
    }

    public function header() {
        $settings = new Settings();

        //array con el menu
        $menu = array(
            array('section' => '', 'title' => 'Home', 'url' => '/web.xinxat.com/en/', 'show' => 'always'),
            array('section' => 'blog', 'title' => 'Blog', 'url' => '/web.xinxat.com/en/blog', 'show' => 'always'),
            array('section' => 'register', 'title' => 'Register', 'url' => '/web.xinxat.com/en/register', 'show' => 'nologged'),
            array('section' => 'user', 'title' => 'User', 'url' => '/web.xinxat.com/en/user', 'show' => 'logged'),
            array('section' => 'client', 'title' => 'Client', 'url' => '/web.xinxat.com/en/client', 'show' => 'logged'),
            array('section' => 'login', 'title' => 'Login', 'url' => '/web.xinxat.com/en/login', 'show' => 'nologged'),
            array('section' => 'login', 'title' => 'Logout', 'url' => '/web.xinxat.com/en/login', 'show' => 'logged')
        );

        echo '<div id="header">
                    <h1 class="logo">xinxat.com beta web</h1>
              </div>
                    <div id="menu">
                        <ul class="ulMenu">';

        $section = $settings->urlParameters(3);

        foreach ($menu as $seccion) {

            if (empty($_SESSION['userid'])) {
                if ($seccion['show'] == 'always' || $seccion['show'] == 'nologged') {
                    if ($seccion['section'] == $section)
                        echo '<li class="liMenu"><a href="' . $seccion['url'] . '" class="active">' . $seccion['title'] . '</a></li>';
                    else
                        echo '<li class="liMenu"><a href="' . $seccion['url'] . '" >' . $seccion['title'] . '</a></li>';
                }
            } else if ($seccion['show'] == 'always' || $seccion['show'] == 'logged') {
                if ($seccion['section'] == $section)
                    echo '<li class="liMenu"><a href="' . $seccion['url'] . '" class="active">' . $seccion['title'] . '</a></li>';
                else
                    echo '<li class="liMenu"><a href="' . $seccion['url'] . '" >' . $seccion['title'] . '</a></li>';
            }
        }

        echo '</ul>
                    </div>
                    <div id="content">' . WELCOME_TEST . '';
    }

    public function footer() {
        echo '</div><div id="footer">' . FOOTER_TEST . '</div></div></body></html>';
    }

    public function error404() {
        echo '<br>ERROR 404';
    }

    public function headClient() {
        $settings = new Settings();

        echo '<html>
        <head>
            <title>' . $settings->sitename . '</title>
            <link href="' . $settings->siteurl . '/css/style.css" media="screen" rel="stylesheet" type="text/css" />
            <script src="' . $settings->siteurl . '/js/jquery-1.6.2.min.js" type="text/javascript"></script>
            <script src="' . $settings->siteurl . '/js/jquery-ui-1.8.14.min.js" type="text/javascript"></script>
            <link href="' . $settings->siteurl . '/css/jquery-ui-1.8.14.css" media="screen" rel="stylesheet" type="text/css" />
            <script src="' . $settings->siteurl . '/js/jquery.scrollTo.js" type="text/javascript"></script>
            <script type="text/javascript" src="' . $settings->siteurl . '/external/ckeditor/ckeditor.js"></script>
            <script type="text/javascript" src="' . $settings->siteurl . '/external/ckeditor/adapters/jquery.js"></script>
            <!--<script type="text/javascript" src="' . $settings->siteurl . '/js/scripts-private.js"></script>-->
            <script type="text/javascript" src="/web.xinxat.com/js/scripts-private.js"></script>
            <style>#requestMonitor{width: 750px; height: 12px; background-color: antiquewhite; border: 2px solid black; margin: auto; text-align: center; color: red; font-size: 12px; padding: 5px; margin-bottom: 10px;}
                    #chatWrapper{width: 750px; height: 300px; background-color: aliceblue; border: 2px solid black; margin: auto; text-align: left; padding: 5px;}
                    #monitor{width: 686px; height: 265px; padding: 5px; overflow-x: hidden; overflow-y: auto;}
                    #monitor p{margin: 0; padding: 0px 0px 5px 0px;}
                    .chatText{width: 655px;}
                    .sentButton, .requestButton{ width: 60px; margin-left: 6px;}
                    .updateMonitorSpan{color: green;}
            </style>
        </head>
        <body>
        <div id="wrapper">';
    }

    public function headerClient() {
        $settings = new Settings();

        //array con el menu
        $menu = array(
            array('section' => '', 'title' => 'Home', 'url' => '/web.xinxat.com/en/'),
            array('section' => 'user', 'title' => 'User', 'url' => '/web.xinxat.com/en/user'),
            array('section' => 'client', 'title' => 'Client', 'url' => '/web.xinxat.com/en/client'),
            array('section' => 'login', 'title' => 'Logout', 'url' => '/web.xinxat.com/en/login')
        );

        echo '<div id="header">
                    <h1 class="logo">xinxat beta client</h1>
              </div>
                    <div id="menu">
                        <ul class="ulMenu">';

        $section = $settings->urlParameters(3);

        foreach ($menu as $seccion) {

            if ($seccion['section'] == $section)
                echo '<li class="liMenu"><a href="' . $seccion['url'] . '" class="active">' . $seccion['title'] . '</a></li>';
            else
                echo '<li class="liMenu"><a href="' . $seccion['url'] . '" >' . $seccion['title'] . '</a></li>';
        }

        echo '</ul>
            </div>
        <div id="content">
            ';
    }

    public function footerClient() {
        echo '</div><div id="footer"><i><a href="http://xinxat.com">Xinxat.com</a> * Programming Dept.</i></div></div></body></html>';
    }

}

?>