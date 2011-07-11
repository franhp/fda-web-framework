<?php
class Style{
	
	public function head(){
		$settings = new Settings();
		
		echo '<html>
		<head>
		<title>'.$settings->sitename.'</title>
		<link href="'.$settings->siteurl.'/css/style.css" media="screen" rel="stylesheet" type="text/css" />
		<script src="'.$settings->siteurl.'/js/jquery-1.6.2.min.js" type="text/javascript"></script>
		<script type="text/javascript" src="'.$settings->siteurl.'/external/ckeditor/ckeditor.js"></script>
		<script type="text/javascript" src="'.$settings->siteurl.'/external/ckeditor/adapters/jquery.js"></script>
		</head>
		<body>';
	}
	
	public function header(){
		echo WELCOME_TEST.'<br>';
	}
	
	public function footer(){
		echo '<br>'.FOOTER_TEST.'</body></html>';	
	}
	
	public function error404(){
		echo '<br>ERROR 404';
	}
}

?>