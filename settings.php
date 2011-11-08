<?php
/**
 * Clase que contiene todos los parametros para crear la web
 * 
 * @author Fran Hermoso <franhp@franstelecom.com>
 * @author H�ctor Costa <hcostaguzman@franstelecom.com>
 * @version 1.0
 */
class Settings {
	/**
	 * Site URL
	 */
	var $siteurl = "http://localhost/web.xinxat.com";
	/**
	 * Nombre del sitio
	 */
	var $sitename = "Xinxat web";
	
	/**
	 * Idioma por defecto
	 */
	var $default_lang = 'en';
    /**
     * Variable que permite especificar la base de datos a usar
     * Posibles valores: mysql, sqlite, couchdb, mongodb, odbc
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
    		if (session_id() == "") session_start();
    		
			/* Database connection*/
	    	require_once 'includes/database.'.$this->database.'.class.php';
	    	$GLOBALS['db'] = new $this->database();
			
			/* Language, users and style */
			require_once 'includes/users.class.php';
			require_once 'includes/login.class.php';
			require_once 'includes/style.class.php';
			
			if($this->urlParameters(2)!="controllers") { //Discard all requests to controllers
				$lang_dir = scandir("lang/"); //Find all languages in lang dir
				$langs = array_slice($lang_dir, 2);
				foreach ($langs as $lang) {
					$languages[] = str_replace(".php", "", $lang);
				}
				if(in_array($this->urlParameters(2), $languages)) // If it exists, set it
					$_SESSION['lang'] = $this->urlParameters(2);
				else {
					$_SESSION['lang'] = $this->default_lang; // Else redirect to the default
					header("Location: ".$this->siteurl."/".$this->default_lang);
				}
			}
			if(isset($_SESSION['lang'])) require_once 'lang/'.$_SESSION['lang'].'.php';
			
			/* Other functions */
			require_once 'includes/blog.class.php';
                        require_once 'includes/login.oauth.class.php';
                        require_once 'includes/chat.class.php';
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
