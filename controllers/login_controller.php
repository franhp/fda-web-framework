<?php 
include_once '../settings.php';
$settings = new Settings();
$settings->bootstrap();

/* Login case */
if(!empty($_POST['username'])){
	$user = new Login();
	if(isset($_POST['remember'])) {
		if($user->doLogin($_POST['username'],$_POST['password'],TRUE)) 
			echo $_SESSION['username'];
	}
	else if($user->doLogin($_POST['username'],$_POST['password'])) echo $_SESSION['username'];
	else echo 'ERROR';
}

/* Logout case */
else if (!empty($_POST['logout'])) {
	$user = new Login();
	if($user->doLogout()) echo 'OK';
	else echo 'ERROR';
}

else echo 'ERROR';
?>