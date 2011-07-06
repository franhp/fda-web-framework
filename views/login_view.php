<?php

$user = new Login();
if(!$user->isLogged()) {

	echo '
	<div class="loginBox">
	<form method="post" id="login">
	Username: <input type="text" name="username" id="username"><br>
	Password: <input type="password" name="password" id="password"><br>
	Remember: <input type="checkbox" name="remember" id="remember"><br>
	<input type="submit" value="'.LOGIN.'">
	</form>
	<form id="logout" style="display: none;">
		<input type="submit" value="'.LOGOUT.'">
	</form>
	</div>
	';
	
	logout_script();
	login_script();
}
else {
	echo '
	<div class="loginBox">
	<form method="post" id="login" style="display: none;">
	Username: <input type="text" name="username" id="username"><br>
	Password: <input type="password" name="password" id="password"><br>
	Remember: <input type="checkbox" name="remember" id="remember"><br>
	<input type="submit" value="'.LOGIN.'">
	</form>
	<form id="logout">
		'.WELCOME.$user->username.'
		<input type="submit" value="'.LOGOUT.'">
	</form>
	</div>';
	
	login_script();
	logout_script();

}


function logout_script(){
	$settings = new Settings();
	echo "
	<script language=\"javascript\">
		$('#logout').submit(function() {
				$.ajax({
					type: 'POST',
					url: '".$settings->siteurl."/controllers/login_controller.php' ,
					data:  { logout: 'true' },
					success: function(data) {
						if(data=='OK'){
							$('#logout').html('".LOGGEDOUT."');
							$('#login').show();
						}
						else{
							$('#logout').append('".ERROR."');
						}
					}
				})
			return false;
		});

	</script>";
}

function login_script(){
	$settings = new Settings();
	echo "
	<script language=\"javascript\">
		$('#login').submit(function() {
				$.ajax({
					type: 'POST',
					url: '".$settings->siteurl."/controllers/login_controller.php' ,
					data: $(this).serialize(),
					success: function(data) {
						if(data!='ERROR'){
							$('#login').html('".WELCOME."'+data);
							$('#logout').show();
						}
						else{
							$('#login').append('".INVALID_LOGIN."');
						}
					}
				})
			return false;
		});

	</script>";
}
?>