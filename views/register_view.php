<?php
	$settings = new Settings();
?>
<h2>Register</h2>
<script src="<?=$settings->siteurl?>/js/register_form.js" type="text/javascript"></script>
<script>

function Check() {
	_user = $("#nick").val();
	$.ajax({
		type: 'POST',
		url: '<?=$settings->siteurl?>/controllers/register_controller.php' ,
		data: { exist_user: _user},
		success: function(data) {
			if(data=='NO'){
				
				$("#nick").next("span").attr('class', 'correcto');
				
				$("#nick").next("span").html("OK");
				
			}
			else if (data=='YES'){
				
				$("#nick").next("span").attr('class', 'incorrecto');
				
				$("#nick").next("span").html("Ya existe");
				
			}
		}
	})
}

function doRegistration(){
	$.ajax({
		type: 'POST',
		url: '<?=$settings->siteurl?>/controllers/register_controller.php' ,
		data: $("#register-form").serialize(),
		success: function(data) {
			if(data=='OK' ){
				
				$("#register-div").fadeOut(1000, function(){
					$("#results").hide();
					$("#results").html("<p style='margin-left: 27px; color: green;'><b>Felicidades, te has registrado correctamente :-D</b></p><p>Ya puedes ingresar desde <a href=\"<?=$settings->siteurl?>/en/login\">aqu&iacute;</a></p>");
                                        $("#results").fadeIn(500);
				});
				
			}
			else{
                                $("#results").hide();
				$("#results").html("<p style='margin-left: 27px; color: red;'><b>No se ha podido crear el usuario, intenta luego.</b></p>");
                                $("#results").fadeIn(500);
			}
		}
	})
	
}
</script>

	
<div id="results"></div>

<div id="register-div">
		
	<h3>Formulario de registro</h3>
	
	<form id="register-form">

            
		<label id="nick-label">Ingresa tu usuario<br><input type="text" id="nick" name="nick" tabindex="1" autocomplete="off" title="Ingrese un nombre de usuario" class=""> <span></span></label>
		
		<label id="passw-label">Contrase&ntilde;a deseada<br><input type="password" id="password" name="password" tabindex="2" autocomplete="off" title="Ingresa una contrase&ntilde;a segura" class=""> <span></span></label>
		
		<label id="passwc-label">Confirme contrase&ntilde;a<br><input type="password" id="password2" name="password2" tabindex="3" autocomplete="off" title="Vuelve a ingresar la contrase&ntilde;a" class=""> <span></span></label>
		
		<label id="email-label">Email<br><input type="text" id="email" name="email" tabindex="4" autocomplete="on" title="Ingresa tu direccion de email" class=""> <span></span></label>
		
		<input type="submit" id="submit" value="Enviar" style="width: 80px; margin-top: 15px" />
		
	</form>

</div>