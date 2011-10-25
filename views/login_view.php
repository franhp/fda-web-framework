<?php

$settings = new Settings();
$options = $settings->urlParameters(4);
if (!empty($options)) {

    $user = new Oauth();

    if ($options == "twitter") {
        $user->doLoginTwitter();
    } else if ($options == "facebook") {
        $user->doLoginFacebook();
    } else if ($options == "getTwitterData") {
        $user->getTwitterData();
    }
} else {

    $user = new Login();

    if (!$user->isLogged()) {

        echo '
	<h2>Login</h2><div id="loginBox" class="loginBox">
	<form method="post" id="login">
	Username: <input type="text" name="username" id="username"><br>
	Password: <input type="password" name="password" id="password"><br>
	Remember: <input type="checkbox" name="remember" id="remember"><br>
	<input type="submit" value="' . LOGIN . '">
	</form>
	<form id="logout" style="display: none;">
		<input type="submit" value="' . LOGOUT . '">
	</form>
	</div><span id="result"></span>
	';

        logout_script();
        login_script();
    } else {
        echo '
	<h2>Logout</h2><div id="loginBox"  class="loginBox">
	<form method="post" id="login" style="display: none;">
	Username: <input type="text" name="username" id="username"><br>
	Password: <input type="password" name="password" id="password"><br>
	Remember: <input type="checkbox" name="remember" id="remember"><br>
	<input type="submit" value="' . LOGIN . '">
	</form>
	<form id="logout">
		' . WELCOME . $user->username . '
		<input type="submit" value="' . LOGOUT . '">
	</form>
	</div><span id="result"></span>';

        login_script();
        logout_script();
    }
}

function logout_script() {
    $settings = new Settings();
    echo "
	<script language=\"javascript\">
		$('#logout').submit(function() {
				$.ajax({
					type: 'POST',
					url: '" . $settings->siteurl . "/controllers/login_controller.php' ,
					data:  { logout: 'true' },
					success: function(data) {
						if(data=='OK'){
							/*$('#logout').html('" . LOGGEDOUT . "');
							$('#login').show();
                                                        $('#logout').html('');*/
                                                        $(window.location).attr('href', '" . $settings->siteurl . "/en/login');
						}
						else{
                                                        $('#result').html('');
							$('#result').append('" . ERROR . "');
						}
					}
				})
			return false;
		});

	</script>";
}

function login_script() {
    $settings = new Settings();
    echo "
	<script language=\"javascript\">
		$('#login').submit(function() {
				$.ajax({
					type: 'POST',
					url: '" . $settings->siteurl . "/controllers/login_controller.php' ,
					data: $(this).serialize(),
					success: function(data) {
						if(data!='ERROR'){
                                                        /*$('#loginBox').fadeOut(500);
							$('#result').html('" . WELCOME . "'+data);
							$('#logout').show();*/
                                                        $(window.location).attr('href', '" . $settings->siteurl . "/en/client');
						}
						else{
                                                $('#result').html('')
                                                        $('#result').html('');
							$('#result').append('" . INVALID_LOGIN . "');
						}
					}
				})
			return false;
		});

	</script>";
}