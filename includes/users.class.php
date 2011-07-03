<?php
/**
 * Users Class para FilaDeAtras Framework
 * 
 * Permite gestionar los usuarios
 * 
 * @author Fran Hermoso <franhp@franstelecom.com>
 * @version 1.0
 *
 */
class Users {
	
	var $username;
	var $password;
	var $role;
	
	
	/**
	 * Comprueba si el usuario existe y si no lo crea
	 * 
	 * @param  $nickname
	 * @param  $password
	 * @param  $role
	 * @return
	 */
	public function createUser($nickname,$password,$role){
	
		
	}
	
	public function modifyUser($param,$value){
		
	}
	
	/**
	 * Lista todos los usuarios
	 */
	public function listUsers(){
		$db = &$GLOBALS['db'];
		
		$db->query('select id,username,password,name,lastname,IP,location,birthdate,email from users');
		echo '<br>Number of users = '.$db->num_rows();
		$db->debug('result');
		
	}
	
	public function delUser($idUser){
		
	}
	
	/**
	 * Retorna la id del usuario
	 * @param $username 
	 * @return userid
	 */
	public function getUserId($username){
		$db = &$GLOBALS['db'];
		$db->query('select id from users where username=\''.$username.'\'');
		$result = $db->obj();
		foreach ($result as $user) $userid = $user->id;
		return $userid;
	}
}
?>