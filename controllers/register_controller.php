<?php 
include_once '../settings.php';
$settings = new Settings();
$settings->bootstrap();

/* Create user */

$user = new Users();

if(!empty($_POST['exist_user'])){
	if($user->getUserId($_POST['exist_user'])) echo "YES";
	else echo "NO";
	
}

if(!empty($_POST['nick'])){
	if(!$user->getUserId($_POST['nick'])){
		if($user->createUser($_POST['nick'], 
                            $_POST['password'], 
                            $_POST['email'])){
			echo "OK";
		}
		else echo "ERROR";
	}
	else echo "EXISTS";
}



?>
