<?php
//Idioma por defecto
require('lang/en_US.php');
require('settings.php');
$settings = new Settings();

echo WELCOME.'<br>';


$db = new $settings->database();
$db->query("select * from paginas");
echo '<br>Number of results = '.$db->num_rows();
$db->debug('result');




echo '<br>'.FOOTER;

?>