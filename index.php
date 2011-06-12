<?php
//Idioma por defecto
require('lang/en_US.php');
require('settings.php');
require('includes/database.class.php');

$settings = new Settings();
if(!empty($settings->database))include('includes/database.'.$settings->database.'.class.php');
echo WELCOME.'<br>';


$db = new Database();
$db->query("select * from projects");
echo '<br>Number of results = '.$db->num_rows();
$db->debug('result');




echo '<br>'.FOOTER;

?>