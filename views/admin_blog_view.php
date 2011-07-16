<?php
$user = new Login(); 
if($user->isLogged()&&$user->getUserRole()>1){
	echo 'uou';
}


?>