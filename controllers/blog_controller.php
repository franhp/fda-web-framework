<?php 
include_once '../settings.php';
$settings = new Settings();
$settings->bootstrap();
$user = new Login();

if($user->isLogged()){
	echo $user->username.' '.ON.' '.time().'<p>'.$_POST['text'].'</p>';
}
else{
	echo $user->username.' '.ON.' '.time().'<p>'.$_POST['text'].'</p>';
}
?>