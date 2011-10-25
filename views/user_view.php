<?php
$settings = new Settings();
$user = new Login();
$username = $settings->urlParameters(3);
$userInfo = $user->getUserInfo($_SESSION['userid']);
if (is_string($username)) {
    if ($user->isLogged()) {
        $settings = new Settings();
        ?>
        <h2>User</h2>
        <script src="<?= $settings->siteurl ?>/js/update_form.js" type="text/javascript"></script>
        <script>

            function doUpdate(){
                $.ajax({
                    type: 'POST',
                    url: '<?= $settings->siteurl ?>/controllers/user_controller.php' ,
                    data: $("#register-form").serialize(),
                    success: function(data) {
                        if(data=='OK' ){
                            $("#results").hide();
                            $("#results").html("<p style='margin-left: 27px; color: green;'><b>Perfil actualizado ;-)</b></p>");
                            $("#results").fadeIn(500);
                                                                            				
                        }
                        else{
                            $("#results").hide();
                            $("#results").html("<p style='margin-left: 27px; color: red;''><b>No se ha podido actualizar, intenta luego.</b></p>");
                            $("#results").fadeIn(500);
                        }
                    }
                })
                                                                            	
            }
        </script>

        <div id="register-div">
            
                    <h3>Update Profile</h3>
            
                    <form id="register-form">
            
                        <label id="name-label">Ingresa tu nombre<br><input type="text" id="name" name="name" value="<?= $userInfo['name'] ?>" tabindex="1"title="Ingresa tu nombre" class=""> <span></span></label>
            
                        <label id="surname-label">Ingresa tu apellido<br><input type="text" id="lastname" name="lastname" value="<?= $userInfo['lastname'] ?>" tabindex="1" title="Ingresa tu apellido" class=""> <span></span></label>
            
                        <label id="location-label">Ingresa tu localidad<br><input type="text" id="location" name="location" value="<?= $userInfo['location'] ?>" tabindex="1" title="Ingresa tu localidad" class=""> <span></span></label>
            
                        <label id="date-label">Fecha de nacimiento<br>
                            <?php
                            $dates = explode("-", $userInfo['birthdate']);
                            ?>
                            <select id="dia" name="dia" tabindex="5" autocomplete="off" title="Ingrese d&iacute;a de nacimiento" class=""  style="width: 51px">
                                <option value="0">D&iacute;a</option>
                                <?php
                                for ($i = 1; $i <= 31; $i++) {
                                    if ((Int) $dates[2] == $i)
                                        echo '<option value="' . $i . '" selected=selected>' . $i . '</option>';
                                    else
                                        echo '<option value="' . $i . '">' . $i . '</option>';
                                }
                                ?>
                            </select>
                
                            <select id="mes" name="mes" tabindex="6" autocomplete="off" title="Ingrese mes de nacimiento" class=""  style="width: 120px">
                                <option value="0">Mes</option>
                                <option value="1" <?php if ((Int) $dates[1] == '1')
                                echo "selected=selected" ?>>Enero</option>
                                <option value="2" <?php if ((Int) $dates[1] == '2')
                                echo "selected=selected" ?>>Febrero</option>
                                <option value="3" <?php if ((Int) $dates[1] == '3')
                                echo "selected=selected" ?>>Marzo</option>
                                <option value="4" <?php if ((Int) $dates[1] == '4')
                                echo "selected=selected" ?>>Abril</option>
                                <option value="5" <?php if ((Int) $dates[1] == '5')
                                echo "selected=selected" ?>>Mayo</option>
                                <option value="6" <?php if ((Int) $dates[1] == '6')
                                echo "selected=selected" ?>>Junio</option>
                                <option value="7" <?php if ((Int) $dates[1] == '7')
                                echo "selected=selected" ?>>Julio</option>
                                <option value="8" <?php if ((Int) $dates[1] == '8')
                                echo "selected=selected" ?>>Agosto</option>
                                <option value="9" <?php if ((Int) $dates[1] == '9')
                                echo "selected=selected" ?>>Septiembre</option>
                                <option value="10" <?php if ((Int) $dates[1] == '10')
                                echo "selected=selected" ?>>Octubre</option>
                                <option value="11" <?php if ((Int) $dates[1] == '11')
                                echo "selected=selected" ?>>Noviembre</option>
                                <option value="12" <?php if ((Int) $dates[1] == '12')
                                echo "selected=selected" ?>>Diciembre</option>
                            </select>
                
                            <select id="anio" name="anio" tabindex="7" autocomplete="off" title="Ingrese a&ntilde;o de nacimiento" class=""  style="width: 70px">
                                <option value="0">A&ntilde;o</option>
                                <?php
                                for ($i = 1995; $i >= 1920; $i--) {
                                    if ((Int) $dates[0] == $i)
                                        echo '<option value="' . $i . '" selected=selected>' . $i . '</option>';
                                    else
                                        echo '<option value="' . $i . '">' . $i . '</option>';
                                }
                                ?>
                            </select>
                
                            <span name="fecha"></span></label>
                        <div id="results" style="display:none;"></div>
                        <input type="submit" id="submit" value="Enviar" style="width: 80px; margin-top: 15px" />
                        <input type="hidden" name="update"/>
            
            
                    </form>
            
                </div>

        <?php
        if ($user->getUserRole() > 2) {
            echo "<h2>Admin options</h2> User list:";
            $user->listUsers();
        }
    }
    else
        include 'views/login_view.php';
}
?>