<?php
class Style{
	
	public function head(){
		echo '<html>
		<head>
		<title>FDA</title>
		<script src="js/jquery-1.6.2.min.js"></script>
		</head>
		<body>';
	}
	
	public function header(){
		echo WELCOME.'<br>';
	}
	
	public function footer(){
		echo '<br>'.FOOTER.'</body></html>';	
	}
	
	public function error404(){
		echo '<br>ERROR 404';
	}
}

?>