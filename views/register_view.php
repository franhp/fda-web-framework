<?php
	$settings = new Settings();
?>

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
					
					$("#results").html("<p style='margin-left: 27px;'><b>Felicidades, te has registrado correctamente :-D</b><br>Ya puedes ingresar desde <a href=\"<?=$settings->siteurl?>/en/login\">aqu&iacute;</a></p>");

				});
				
			}
			else{

				$("#results").html("<p style='margin-left: 27px;'><b>No se ha podido crear el usuario, intenta luego.</b></p>");
			}
		}
	})
	
}
</script>

	
<div id="results"></div>

<div id="register-div">
		
	<h3>Formulario de registro</h3>
	
	<form id="register-form">
	
		<label id="name-label">Ingresa tu nombre<br><input type="text" id="name" name="name" tabindex="1"title="Ingresa tu nombre" class=""> <span></span></label>
		
		<label id="surname-label">Ingresa tu apellido<br><input type="text" id="lastname" name="lastname" tabindex="1" title="Ingresa tu apellido" class=""> <span></span></label>
		
		<label id="nick-label">Ingresa tu usuario<br><input type="text" id="nick" name="nick" tabindex="1" autocomplete="off" title="Ingrese un nombre de usuario único" class=""> <span></span></label>
		
		<label id="passw-label">Contrase&ntilde;a deseada<br><input type="password" id="password" name="password" tabindex="2" autocomplete="off" title="Ingresa una contraseña segura" class=""> <span></span></label>
		
		<label id="passwc-label">Confirme contrase&ntilde;a<br><input type="password" id="password2" name="password2" tabindex="3" autocomplete="off" title="Vuelve a ingresar la contraseña" class=""> <span></span></label>
		
		<label id="email-label">Email<br><input type="text" id="email" name="email" tabindex="4" autocomplete="on" title="Ingresa tu dirección de email" class=""> <span></span></label>
		
		<label id="date-label">Fecha de nacimiento<br>
		
			<select id="dia" name="dia" tabindex="5" autocomplete="off" title="Ingrese día de nacimiento" class=""  style="width: 51px">
				<option value="">D&iacute;a</option>
				<option value="1">1</option>
				<option value="2">2</option>
				<option value="3">3</option>
				<option value="4">4</option>
				<option value="5">5</option>
				<option value="6">6</option>
				<option value="7">7</option>
				<option value="8">8</option>
				<option value="9">9</option>
				<option value="10">10</option>
				<option value="11">11</option>
				<option value="12">12</option>
				<option value="13">13</option>
				<option value="14">14</option>
				<option value="15">15</option>
				<option value="16">16</option>
				<option value="17">17</option>
				<option value="18">18</option>
				<option value="19">19</option>
				<option value="20">20</option>
				<option value="21">21</option>
				<option value="22">22</option>
				<option value="23">23</option>
				<option value="24">24</option>
				<option value="25">25</option>
				<option value="26">26</option>
				<option value="27">27</option>
				<option value="28">28</option>
				<option value="29">29</option>
				<option value="30">30</option>
				<option value="31">31</option>
			</select>
			
			<select id="mes" name="mes" tabindex="6" autocomplete="off" title="Ingrese mes de nacimiento" class=""  style="width: 120px">
				<option value="">Mes</option>
				<option value="1">Enero</option>
				<option value="2">Febrero</option>
				<option value="3">Marzo</option>
				<option value="4">Abril</option>
				<option value="5">Mayo</option>
				<option value="6">Junio</option>
				<option value="7">Julio</option>
				<option value="8">Agosto</option>
				<option value="9">Septiembre</option>
				<option value="10">Octubre</option>
				<option value="11">Noviembre</option>
				<option value="12">Diciembre</option>
			</select>
			
			<select id="anio" name="anio" tabindex="7" autocomplete="off" title="Ingrese año de nacimiento" class=""  style="width: 70px">
				<option value="">A&ntilde;o</option>
				<option value="1993">1993</option>
				<option value="1992">1992</option>
				<option value="1991">1991</option>
				<option value="1990">1990</option>
				<option value="1989">1989</option>
				<option value="1988">1988</option>
				<option value="1987">1987</option>
				<option value="1986">1986</option>
				<option value="1985">1985</option>
				<option value="1984">1984</option>
				<option value="1983">1983</option>
				<option value="1982">1982</option>
				<option value="1981">1981</option>
				<option value="1980">1980</option>
				<option value="1979">1979</option>
				<option value="1978">1978</option>
				<option value="1977">1977</option>
				<option value="1976">1976</option>
				<option value="1975">1975</option>
				<option value="1974">1974</option>
				<option value="1973">1973</option>
				<option value="1972">1972</option>
				<option value="1971">1971</option>
				<option value="1970">1970</option>
				<option value="1969">1969</option>
				<option value="1968">1968</option>
				<option value="1967">1967</option>
				<option value="1966">1966</option>
				<option value="1965">1965</option>
				<option value="1964">1964</option>
				<option value="1963">1963</option>
				<option value="1962">1962</option>
				<option value="1961">1961</option>
				<option value="1960">1960</option>
				<option value="1959">1959</option>
				<option value="1958">1958</option>
				<option value="1957">1957</option>
				<option value="1956">1956</option>
				<option value="1955">1955</option>
				<option value="1954">1954</option>
				<option value="1953">1953</option>
				<option value="1952">1952</option>
				<option value="1951">1951</option>
				<option value="1950">1950</option>
				<option value="1949">1949</option>
				<option value="1948">1948</option>
				<option value="1947">1947</option>
				<option value="1946">1946</option>
				<option value="1945">1945</option>
				<option value="1944">1944</option>
				<option value="1943">1943</option>
				<option value="1942">1942</option>
				<option value="1941">1941</option>
				<option value="1940">1940</option>
				<option value="1939">1939</option>
				<option value="1938">1938</option>
				<option value="1937">1937</option>
				<option value="1936">1936</option>
				<option value="1935">1935</option>
				<option value="1934">1934</option>
				<option value="1933">1933</option>
				<option value="1932">1932</option>
				<option value="1931">1931</option>
				<option value="1930">1930</option>
				<option value="1929">1929</option>
				<option value="1928">1928</option>
				<option value="1927">1927</option>
				<option value="1926">1926</option>
				<option value="1925">1925</option>
				<option value="1924">1924</option>
				<option value="1923">1923</option>
				<option value="1922">1922</option>
				<option value="1921">1921</option>
				<option value="1920">1920</option>
				<option value="1919">1919</option>
				<option value="1918">1918</option>
				<option value="1917">1917</option>
				<option value="1916">1916</option>
				<option value="1915">1915</option>
				<option value="1914">1914</option>
				<option value="1913">1913</option>
				<option value="1912">1912</option>
				<option value="1911">1911</option>
				<option value="1910">1910</option>
				<option value="1909">1909</option>
				<option value="1908">1908</option>
				<option value="1907">1907</option>
				<option value="1906">1906</option>
				<option value="1905">1905</option>
				<option value="1904">1904</option>
				<option value="1903">1903</option>
				<option value="1902">1902</option>
				<option value="1901">1901</option>
				<option value="1900">1900</option>
			</select>
			
		 <span name="fecha"></span></label>
		
		<input type="submit" id="submit" value="Enviar" style="width: 80px; margin-top: 15px" />
		
	</form>

</div>