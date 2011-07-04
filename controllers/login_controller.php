<?php 

if(!empty($_POST['username'])){
	$user = new Users();
	if($user->doLogin($_POST['username'],$_POST['password'])) echo 'LOL';
	else echo 'WTF';
}
else echo 'Get outta here';



?>