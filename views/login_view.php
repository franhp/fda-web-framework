<?php
$user = new Users();
if(empty($user->id)) {

	echo '
	<form method="post" id="form_id">
	Username: <input type="text" name="username" id="username"><br>
	Password: <input type="password" name="password" id="password"><br>
	<input type="submit">
	</form>
		User not logged in
	';
	
	
	
	echo "
	<script language=\"javascript\">
		$('#form_id').submit(function() {
				$.ajax({
					type: 'POST',
					url: 'http://localhost/controllers/login_controller.php' ,
					data: $(this).serialize(),
					success: function(data) {
						if(data!=''){
							alert(data);
						}
						else{
							alert(data);
						}
					}
				})
			return false;
		});

	</script>";
}
else {
	echo 'User logged in';
	echo '<form id="logout"><input type="submit" value="'.LOGOUT.'"></form>';
	
		echo "
	<script language=\"javascript\">
		$('#logout').submit(function() {
				$.ajax({
					type: 'POST',
					url: 'http://localhost/controllers/login_controller.php' ,
					data:  { logout: 'true' },
					success: function(data) {
						if(data!=''){
							alert(data);
						}
						else{
							alert(data);
						}
					}
				})
			return false;
		});

	</script>";
}

?>