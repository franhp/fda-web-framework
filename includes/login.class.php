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
	 * @param $username
	 * @param $password
	 * @param $cookie
	 * @param $remember
	 * @return true si existe, false si no
	 */
	public function doLogin($username, $password, $remember = FALSE, $cookieAuth = FALSE){
		$db = &$GLOBALS['db'];
		$settings = new Settings();
		
		if($cookieAuth) $db->query('select id,username,password,role from users where 
					username=\''.$db->clean($username).'\' 
					and 
					password=\''.$db->clean($password).'\'');
		
		else $db->query('select id,username,password,role from users where 
					username=\''.$db->clean($username).'\' 
					and 
					password=\''.$db->clean(md5($password.'V1V4fDA')).'\'');
		
		if($db->num_rows() > 0){
			$result = $db->obj();
			foreach($result as $user){
				$_SESSION['userid'] = $user->id;
				$_SESSION['role']= $user->role;
				$_SESSION['username'] = $user->username;
			}
			if ($remember){
				setcookie('remember',true,time()+3600*24*15,'/',str_replace("http://", "", $settings->siteurl));
				setcookie('username',$user->username,time()+3600*24*15,'/',str_replace("http://", "", $settings->siteurl));
				setcookie('password',$user->password,time()+3600*24*15,'/',str_replace("http://", "", $settings->siteurl));
			}
			return true;
		}
		else return false;
	}
	
	/**
	 * Comprueba si el usuario ya ha hecho login
	 *
	 * @return true si ha hecho login, false si no
	 */
	public function isLogged(){
		if(isset($_COOKIE['remember'])) {
			if($this->doLogin($_COOKIE['username'], $_COOKIE['password'], false,true)){
				return true;
			}
			else {
				if($this->doLogout()) return false;
			}
		}
		else if(isset($_SESSION['userid'])&&!empty($_SESSION['userid'])){
			return true;
		}
		else return false;
	}
	
	/**
	 * Destruye todo rastro de la sesión
	 */
	public function doLogout(){
		$settings = new Settings();
		
		$_SESSION = array();
		session_unset();
		session_destroy();
		
		setcookie('remember',false,time()-3600,'/',str_replace("http://", "", $settings->siteurl));
		setcookie('username',"",time()-3600,'/',str_replace("http://", "", $settings->siteurl));
		setcookie('password',"",time()-3600,'/',str_replace("http://", "", $settings->siteurl));
		
		return true;
	}
	
}
?>