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
		<link href="' . $settings->siteurl . '/css/jquery-ui-1.8.16.custom.css" media="screen" rel="stylesheet" type="text/css" />
                <!--<link href="' . $settings->siteurl . '/css/jquery-ui-1.8.14.css" media="screen" rel="stylesheet" type="text/css" />-->
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
            array('section' => '', 'title' => 'Home', 'url' => $settings->siteurl, 'show' => 'always'),
            array('section' => 'blog', 'title' => 'Blog', 'url' => $settings->siteurl.'/en/blog', 'show' => 'always'),
            array('section' => 'register', 'title' => 'Register', 'url' => $settings->siteurl.'/en/register', 'show' => 'nologged'),
            array('section' => 'user', 'title' => 'User', 'url' => $settings->siteurl.'/en/user', 'show' => 'logged'),
            array('section' => 'client', 'title' => 'Client', 'url' => $settings->siteurl.'/en/client', 'show' => 'logged'),
            array('section' => 'admin', 'title' => 'Admin', 'url' => $settings->siteurl.'/en/admin/client', 'show' => 'logged'),
            array('section' => 'login', 'title' => 'Login', 'url' => $settings->siteurl.'/en/login', 'show' => 'nologged'),
            array('section' => 'login', 'title' => 'Logout', 'url' => $settings->siteurl.'/en/login', 'show' => 'logged')
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
            <link href="' . $settings->siteurl . '/css/jquery-ui-1.8.16.custom.css" media="screen" rel="stylesheet" type="text/css" />
            <!--<link href="' . $settings->siteurl . '/css/jquery-ui-1.8.14.css" media="screen" rel="stylesheet" type="text/css" />-->
            <script src="' . $settings->siteurl . '/js/jquery.scrollTo.js" type="text/javascript"></script>
            <script type="text/javascript" src="' . $settings->siteurl . '/external/ckeditor/ckeditor.js"></script>
            <script type="text/javascript" src="' . $settings->siteurl . '/external/ckeditor/adapters/jquery.js"></script>
            <!--<script type="text/javascript" src="' . $settings->siteurl . '/js/scripts-private.js"></script>-->
            <style>
		#chatWrapper{width: 771px; height: 300px; text-align: left; font-family: courier; }
		#chatDiv{float: left; width: 518px; height: 272px; text-align: left;}
		#chatRoster{float: left; width: 130px; height: 277px; border-left: 1px solid #AAAAAA; text-align: left; overflow-y: auto; margin-bottom: 1px;}
		#chatMonitor{width: 510px; height: 278px; overflow-x: hidden; overflow-y: auto;}
		#chatMonitor p{margin: 0; padding: 2px 0px 0px 5px;}
                .ui-dialog p{margin: 0; padding: 1px 3px;}
		#chatRooms{float: left; width: 120px; height: 300px;  border-right: 1px solid #AAAAAA; text-align: left; overflow-y: auto;}
		.chatText{width: 648px; margin:0;padding:0; height: 21px; font-size: 14px; float:right; margin-right: 1px;}
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
            array('section' => '', 'title' => 'Home', 'url' => $settings->siteurl),
            array('section' => 'user', 'title' => 'User', 'url' => $settings->siteurl.'/en/user'),
            array('section' => 'client', 'title' => 'Client', 'url' => $settings->siteurl.'/en/client'),
            array('section' => 'admin', 'title' => 'Admin', 'url' => $settings->siteurl.'/en/admin/client'),
            array('section' => 'login', 'title' => 'Logout', 'url' => $settings->siteurl.'/en/login')
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
        $settings = new Settings();
        echo '</div><div id="footer"><i><a href="'.$settings->siteurl.'">Xinxat.com</a> * Programming Dept.</i></div></div></body></html>';
    }

}

?>