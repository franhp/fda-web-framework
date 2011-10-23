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

if(!empty($_POST['name'])){
	if(!$user->getUserId($_POST['nick'])){
		if($user->createUser($_POST['nick'], 
                            $_POST['password'], 
                            $_POST['name'], 
                            $_POST['lastname'], 
                            $_POST['anio']."-".$_POST['mes']."-".$_POST['dia'],
                            $_POST['email'],
                            1)){
			echo "OK";
		}
		else echo "ERROR";
	}
	else echo "EXISTS";
}

?>
