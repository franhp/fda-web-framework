<?php 
include_once '../settings.php';
$settings = new Settings();
$settings->bootstrap();
$user = new Login();
$blog = new Blog();

if($user->isLogged()){
	if($blog->addComment($_POST['id'], $_POST['text'], $user->id)) {
		echo COMMENTEDBY.' '.$user->username.' <i>just now</i><p>'.$_POST['text'].'</p>';	
	}
	else echo ERROR;
}
else{
	if($blog->addComment($_POST['id'], $_POST['text'])){
		echo COMMENTEDBY.' Anonymous <i>just now</i><p>'.$_POST['text'].'</p>';	
	}
	else echo ERROR;
	
}

?>