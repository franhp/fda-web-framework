<?php
class Settings {
    /**
     * Variable que permite especificar la base de datos a usar
     * Posibles valores: mysql, sqlite, couchdb, mongodb
     */
    var $database = "mysql";  //mysql, sqlite, couchdb
    
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
    
    public function __construct(){
		require_once('includes/database.'.$this->database.'.class.php');
    }
}

?>