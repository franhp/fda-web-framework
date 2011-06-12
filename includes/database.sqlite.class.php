<?php
/**
 * SQLite Database Class para franstelecom_framework
 * 
 * Permite la creación de conexiones con la base de datos, tiene metodos para consultar y volcar resultados.
 *
 * @author Fran Hermoso <franhp@franstelecom.com>
 * @version 1.0
 * @package Database
 * @subpackage SQLite
 */
class SQLite extends Database
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
    public function conectar()
    {
        $settings = new Settings;
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
    * Esta función permite hacer una selección de una tabla
    *
    * Por ejemplo:
    * <pre>$Database->select('*','tabla1','nombre=\'frans\'');</pre>
    * 
    * @param string $fields Los campos que se van a seleccionar de la tabla
    * @param string $table_name La tabla de la que se van a seleccionar los datos
    * @param string $filters Los parametros para filtrar los resultados (where, limit, ...)
    */
    public function select($fields, $table_name, $filters){
        if(empty($table_name)||empty($fields)) die ('Query Incorrecta');
		
		
        $this->query = "Select rowid as id,".$fields." from ".$table_name;
        
        if (!empty($filters)) $this->query =  $this->query." where ".$filters;
        
        $this->result =  @ sqlite_query ($this->db, $this->query);
    }
    
    /**
    * Esta función permite insertar datos en una tabla
    *
    * Por ejemplo:
    * <pre>$Database->insert('tabla1','nombre,apellidos','\'minombre\',\'miapellido\'');</pre>
    * 
    * Hay que tener cuidado con las comillas si se trata de una cadena de texto es obligatorio rodearlo de comillas
    * 
    * Por ejemplo:
    *<pre>$values = "'minombre','miapellido',2"
    *$values = '\'minombre\',\'miapellido\',2'</pre>
    *
    * @param string $table_name La tabla en la que se van a insertar los datos
    * @param string $fields Especifica los nombres de los campos que se van a especificar en $values
    * @param string $values Especifica los valores que van a ser insertados
    */
    public function insert($table_name, $fields, $values){
        if(empty($table_name)||empty($fields)) die ('Query Incorrecta');
        
        $this->query = 'INSERT INTO \''.$table_name.'\' ('.$fields.') values ('.$values.')';
        
        $this->result =   sqlite_query ($this->db, $this->query);
    }
    
    /**
    * Esta función permite actualizar valores de una tabla
    * 
    * Por ejemplo:
    * <pre>$Database->update('tabla1','nombre','\'minombre\'','where id=1');</pre>
    *
    * @param string $table_name La tabla en la que se van a actualizar los datos
    * @param string $column El nombre de la columna que tiene los datos
    * @param string $value El valor por el cual se va a substituir
    * @param string $filters Los filtros necesarios (where, limit, ...)
    */
    public function update($table_name, $column, $value, $filters){
        if(empty($table_name)||empty($column)||empty($filters)) die ('Query Incorrecta');
        
        $this->query = 'update '.$table_name.' set '.$column.'=\''.$value.'\' where '.$this->transform($filters);
        
        $this->result =  @ sqlite_query ($this->db, $this->query);
    }
    
    /**
    * Esta función permite borrar valores de una tabla
    *
    * Por ejemplo:
    * <pre>$Database->delete('tabla1','nombre=\'fran\'');</pre>
    *
    *
    * @param string $table_name La tabla de la que se borrará el valor
    * @param string $filters Los filtros necesarios (where, limit, ...)
    */
    public function delete($table_name, $filters){
        if(empty($table_name)||empty($filters)) die ('Query Incorrecta');
		
            $this->query = 'delete from '.$table_name.' where '.$this->transform($filters);
            
            $this->result =  @ sqlite_query ($this->db, $this->query);
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
    * Función que permite crear tablas
    *
    * Por ejemplo
    * <pre>
    * $db->createTable('test','nombre varchar(50), apellidos varchar(100)');
    * </pre>
    *NOTA: en SQLite el campo id no hace falta
    * @param string $name El nombre de la tabla
    * @param string $fields Los campos necesarios separados por comas
   */
   public function createTable($name, $fields){
        $this->query('CREATE TABLE \''.$name.'\' ('.$fields.')');
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
    public function desconectar(){
        sqlite_close($this->db);
    }
    
    /**
     * Funcion que transforma la manera de entender ID de Mysql a SQLite
     *
     * @return string 
     */
    private function transform($query){
        $pattern = "/id/";
        $replacement = "rowid";
        $query = preg_replace($pattern, $replacement, $query);
        return $query;
    }
}
?>