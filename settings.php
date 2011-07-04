<?php
/**
 * Clase que contiene todos los parametros para crear la web
 * 
 * @author Fran Hermoso <franhp@franstelecom.com>
 * @author Héctor Costa <hcostaguzman@franstelecom.com>
 * @version 1.0
 */
class Settings {
	/**
	 * Idioma por defecto
	 */
	var $default_lang = 'en';
    /**
     * Variable que permite especificar la base de datos a usar
     * Posibles valores: mysql, sqlite, couchdb, mongodb
     */
    var $database = "mysql";
    
    /**
     * Parametros de una base de datos MySQL
     */
    var $dbhostname = "localhost";
    var $dbusername = "root";
    var $dbpassword = "";
    var $dbase = "framework";
    
    /**
     * Parametros de una base de datos SQLite
     */
    //var $dbfile = "database/database.sqlite";
    
    
    /**
     * Clase  en la que se incluyen todas las clases necesarias
     */
    public function bootstrap(){
    	session_start();
    	
		/* Database connection*/
    	require_once 'includes/database.'.$this->database.'.class.php';
		$GLOBALS['db'] = new $this->database();
		
		/* Language, users and style */
		require_once 'includes/users.class.php';
		require_once 'includes/login.class.php';
		require_once 'includes/style.class.php';
		if($this->urlParameters(1)!=false) require_once 'lang/'.$this->urlParameters(1).'.php';
		else require_once 'lang/'.$this->default_lang.'.php';
		
		
		/* Other functions */
		require_once 'includes/blog.class.php';
    }
    
    /**
     * Retorna el segmento especificado de la URL
     * @param $segment
     */
    public function urlParameters($segment){
		$navString = $_SERVER['REQUEST_URI']; // Gets the URL
		$parts = explode('/', $navString); // Explodes using "/"
		if($segment<count($parts)) return $parts[$segment];
		else return FALSE;
    }
}

?>