<?php
$user = new Users();

if(empty($user->id)) 
echo '
User not logged in
<script language="javascript">
	function doLogin(){
		jQuery.post("http://localhost/controllers/login_controller.php", $("#login").serialize());
	}
</script>
<form method="post" id="login">
Username: <input type="text" name="username" id="username"><br>
Password: <input type="password" name="password" id="password"><br>
<input type="submit" onClick="doLogin()">
</form>

';
else {
	echo 'User logged in';
	print_R($user);
}

?>