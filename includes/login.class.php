<?php
/**
 * Login Class para FilaDeAtras Framework
 * 
 * Permite controlar el estado y el rol del usuario
 * 
 * @author Fran Hermoso <franhp@franstelecom.com>
 * @version 1.0
 */
class Login extends Users{
	/**
	 * Función que que comprueba si el usuario existe en la base de datos 
	 * e inserta su id en una variable de sesión
	 * 
	 * @param  $username
	 * @param  $password
	 * @return true si existe, false si no
	 */
	public function doLogin($username,$password){
		$db = &$GLOBALS['db'];
		$db->query('select id,username,password,role from users where 
					username=\''.$username.'\' 
					and 
					password=\''.$password.'\''); //Must salt
		if($db->num_rows() > 0){
			$result = $db->obj();
			foreach($result as $user){
				$_SESSION['userid'] = $user->id;
				$_SESSION['role']= $user->role;
				$_SESSION['username'] = $user->username;
			}
			
			//Cookie?
			
			return true;
		}
		else return false;
	}
	
	/**
	 * Comprueba si el usuario ya ha hecho login
	 *
	 * @param $username
	 * @return true si ha hecho login, false si no
	 */
	public function isLogged($username){
		if(isset($_SESSION['userid'])&&!empty($_SESSION['userid'])){
			return true;
		}
		return false;
	}
	
	/**
	 * Destruye todo rastro de la sesión
	 */
	public function doLogout(){
		$_SESSION = array();
		session_unset();
		session_destroy();
	}
	
}
?>