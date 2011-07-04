<?php 
include_once '../settings.php';
$settings = new Settings(true);

if(!empty($_POST['username'])){
	$user = new Login();
	if($user->doLogin($_POST['username'],$_POST['password'])) echo 'LOL';
	else echo 'WTF';
}
else if (!empty($_POST['logout'])) {
	$user = new Login();
	$user->doLogout();
	echo 'LOGGED OUT';
}
else echo 'Get outta here';



?>