<?php
class Database {
    
    private $database;
    private $clase;
    
    public function __construct(){
        $settings = new Settings;
        $this->database = $settings->database;
        switch($this->database){
            case 'sqlite':
		$this->clase = "SQLite";
                @SQLite::conectar();
                break;
            case 'mysql':
		$this->clase = "MySQL";
                @MySQL::conectar();
                break;
        }
    }
    
    public  function query($query){
        $clase = $this->clase;
        @$clase::query($query);
    }
    

    public  function json($nombre){
        $clase = $this->clase;
        @$clase::json($nombre);
    }
    
    
    public  function obj(){
		$clase = $this->clase;
        $content = @$clase::obj();
		return $content;
    }
    
    public   function debug($type){
        $clase = $this->clase;
        @$clase::debug($type);
    }
    
    
    public  function num_rows(){
        $clase = $this->clase;
		@$rows = $clase::num_rows();
		return $rows;
    }
    
    
    public function __destruct(){
        $clase = $this->clase;
		@$clase::desconectar();
    }
}
?>