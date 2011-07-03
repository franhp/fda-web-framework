<?php
class Style{
	public function header(){
		echo WELCOME.'<br>';
	}
	
	public function footer(){
		echo '<br>'.FOOTER;	
	}
	
	public function error404(){
		echo '<br>ERROR 404';
	}
}

?>