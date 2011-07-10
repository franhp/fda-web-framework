<?php
$settings = new Settings();
$user = new Login();
$username = $settings->urlParameters(3);

if(is_string($username)){
	print_r($user);
}

else {
	if($user->isLogged()&&$user->getUserRole()>2) $user->listUsers();
	else include 'views/login_view.php';
}
?>