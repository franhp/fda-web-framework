<?php
/**
 * SQLite Database Class para FilaDeAtras Framework
 * 
 * Permite la creación de conexiones con la base de datos, tiene metodos para consultar y volcar resultados.
 *
 * @author Fran Hermoso <franhp@franstelecom.com>
 * @version 1.1
 * @package Database
 * @subpackage SQLite
 */
class sqlite
{
    /**
     * Resource de la base de datos
     * @access private
     * @var resource
    */
    private $db;
    
    /**
    * Variable que contiene la query
    * 
    * @access private
    * @var string
    */
    private $query;
    
    /**
     * Almacena el resultado de la consulta
     * @access private
    */
    private $result;
    
    /**
    * Constructor para crea la conexión con la base de datos.
    *
    */
    public function __construct()
    {
        $settings = new Settings(false);
        $this->db = sqlite_open($settings->dbfile, 0666, $error);
        if (!$this->db){
        $error = (file_exists($settings->dbfile)) ? "Impossible to open, check permissions" : "Impossible to create, check permissions";
            die($error);
        }
    }
    
    /**
    * Esta función permite introducir cualquier query
    *
    * Por ejemplo:
    * <pre>Select * from tabla1,tabla2 where tabla1.nombre=tabla2.nombre</pre>
    *
    * @param string $query Cualquier tipo de query 
    */
    public function query($string)
    {
        $this->result = @ sqlite_query($this->db, $string);
    }
    
   
    
    /**
     * @deprecated
     */
    public function json($nombre){
        header("Content-Type: application/json");
        $arr = array();
        $i=0;
        echo "{\"results\": [";
        while ($row = mysql_fetch_assoc($this->result)) {
            $str = $row[$nombre];
            $arr[] = "{\"id\": \"".$i."\", \"value\": \"".$str."\", \"info\": \"\"}";
            $i++;    
        }
        echo implode(", ", $arr);
        echo "]}";
    }
    
    /**
    * Función que retorna el numero de filas de una consulta
    * 
    * @return int
    */
    public function num_rows(){
        if(!$this->result) return 0;
        else {
            $rows = @sqlite_num_rows($this->result);
        }
        return $rows;
    }
    

    /**
    * Función que retorna un objeto del tipo stdClass que contiene los datos antes pedidos mediante cualquier función de consulta
    *
    * Por ejemplo:
    * <pre>
    *$db = new Database;
    *$db->select('*','table','');
    *$resultado = $db->obj();
    *print_r($resultado);
    * 
    * </pre>
    *
    * @return stdClass
    */
    public function obj(){
        $array = new stdClass();
        
        $i=0;
        while ($row = @sqlite_fetch_object($this->result)){
            $array->$i = new stdClass();
            foreach ($row as $key => $value) {
                $array->$i->$key = $value;
            }
            $i++;
        }
        
        return $array;
    }
    
    /**
    * Función que muestra la Query o su resultado dependiendo del parametro que se le pase
    * Los parametros posibles son "Query" y "Result"
    * 
    * Por ejemplo:
    * <pre>
    * $db = new Database();
    * $db->select('*','tabla','');
    * $db->debug('query');
    *  
    * </pre>
    *
    * Muestra-> Query was: select * from tabla
    * 
    * @param string $type 'result' retorna un var_dump del resultado, 'query' solo la consulta
    */
    public function debug($type){
        switch($type){
            case 'result':
                echo '<br>Result was: <pre>';
                while($row = sqlite_fetch_array($this->result))
                print_r($row);
                echo '</pre><br>';
                break;
            case 'query':
                echo '<br>Query was '.$this->query.'<br>';
                break;
            default:
                echo '<br>Query was '.$this->query.'<br>';
                break;
        }
    }
    
    /**
    * Función que se desconecta de la base de datos
   */
    public function __destruct(){
        sqlite_close($this->db);
    }
    
    /**
     * Función que transforma la manera de entender ID de Mysql a SQLite
     *
     * @return string 
     */
    private function transform($query){
        $pattern = "/id/";
        $replacement = "rowid";
        $query = preg_replace($pattern, $replacement, $query);
        return $query;
    }
    
    /**
     * Función que retorna la cadena segura para poderla insertar en la bbdd
     * @param string $string
     * @return string
     */
    public function clean($string){
    	return sqlite_escape_string($string);
    }
}
?>