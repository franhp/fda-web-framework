<?php
/**
 * MySQL Database Class para FilaDeAtras Framework
 * 
 * Permite la creación de conexiones con la base de datos, tiene metodos para consultar y volcar resultados.
 *
 * @author Héctor Costa <hcostaguzman@franstelecom.com>
 * @author Fran Hermoso <franhp@franstelecom.com>
 * @version 1.1
 * @package Database
 * @subpackage MySQL
 */
class mysql
{
	
	/**
	 * Variable que contiene la query
	 * 
	 * @access private
	 * @var string
	 */
	private $query;
	
	/**
	 * Variable que contiene el resultado de la query
	 * 
	 * @access private
	 * @var object
	 */
	private $result;
	
	/**
	 * Constructor para crea la conexión con la base de datos.
	 *
	 */
	public function __construct()
	{
		$settings = new Settings();
		@ mysql_connect($settings->dbhostname,$settings->dbusername, $settings->dbpassword) or die ('Error conectando a mysql');
		@ mysql_selectdb($settings->dbase) or die ('Error seleccionando tabla');
	}

	
	
	/**
	 * Esta función permite introducir cualquier query
	 *
	 * Por ejemplo:
	 * <pre>Select * from tabla1,tabla2 where tabla1.nombre=tabla2.nombre</pre>
	 *
	 * @param string $query Cualquier tipo de query 
	 */
	public function query($query){
		$this->query = $query;
		
		$this->result =  @ mysql_query ($this->query);
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
	public function obj() {
		$array = array();  
		while ($fila = @mysql_fetch_object($this->result)) {
			$array[] = $fila;
		} 
		return (object)$array;
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
	public function debug($type) {
		switch($type){
			case 'query':
				echo "Query was: ".$this->query."<br>";
				break;
			case 'result':
				echo '<br>Result was:<br>
				<pre>';
				$array = array();  
				while ($fila = @mysql_fetch_object($this->result)) {
					$array[] = $fila;  	
				} 
				print_r($array);
				echo '</pre><br>';
				break;
			default:
				echo "Query was: ".$this->query."<br>";
				break;
		}
		
		
	}
	
	/**
	 * Función que retorna el numero de filas de una consulta
	 * 
	 * @return int
	 */
	public function num_rows(){
		if(!$this->result) return 0;
		else {
			$rows = @mysql_num_rows($this->result);
		}
		return $rows;
	}
	
	/**
	 * Función que retorna la cadena segura para insertar en la base de datos
	 * @param string $text
	 */
	public function clean($text){
		return mysql_real_escape_string($text);
	}


	/**
	 * Función que se desconecta de la base de datos
	*/
	public function __destruct()
	{
		@ mysql_free_result($this->result);			
		@ mysql_close();
	}
	
}
?>